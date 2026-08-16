<?php

require_once __DIR__ . '/../config/database.php';

class RBAC {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Verifica se um usuário tem permissão para acessar uma página
     */
    public function hasPermission($usuario_id, $pagina_nome) {
        // Se for instituição, verificar permissões diretamente
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            return $this->hasPermissionForInstitution($pagina_nome);
        }
        
        $sql = "
            SELECT pp.permitido 
            FROM permissoes_paginas pp
            JOIN usuarios u ON u.perfil_id = pp.perfil_id
            JOIN paginas_sistema ps ON ps.id = pp.pagina_id
            WHERE u.id = ? AND ps.nome = ? AND u.status = 'ativo' AND ps.ativo = TRUE
        ";
        
        $result = $this->db->fetch($sql, [$usuario_id, $pagina_nome]);
        return $result && $result['permitido'] == 1;
    }
    
    /**
     * Verifica permissões para instituições
     */
    public function hasPermissionForInstitution($pagina_nome) {
        $sql = "
            SELECT pp.permitido 
            FROM permissoes_paginas pp
            JOIN paginas_sistema ps ON ps.id = pp.pagina_id
            WHERE pp.perfil_id = 1 AND ps.nome = ? AND ps.ativo = TRUE
        ";
        
        $result = $this->db->fetch($sql, [$pagina_nome]);
        return $result && $result['permitido'] == 1;
    }
    
    /**
     * Verifica se um usuário tem permissão para acessar uma rota
     */
    public function hasPermissionByRoute($usuario_id, $rota) {
        $sql = "
            SELECT pp.permitido 
            FROM permissoes_paginas pp
            JOIN usuarios u ON u.perfil_id = pp.perfil_id
            JOIN paginas_sistema ps ON ps.id = pp.pagina_id
            WHERE u.id = ? AND ps.rota = ? AND u.status = 'ativo' AND ps.ativo = TRUE
        ";
        
        $result = $this->db->fetch($sql, [$usuario_id, $rota]);
        return $result && $result['permitido'] == 1;
    }
    
    /**
     * Obtém todas as permissões de um usuário
     */
    public function getUserPermissions($usuario_id) {
        $sql = "
            SELECT ps.nome, ps.rota, ps.descricao, pp.permitido
            FROM permissoes_paginas pp
            JOIN usuarios u ON u.perfil_id = pp.perfil_id
            JOIN paginas_sistema ps ON ps.id = pp.pagina_id
            WHERE u.id = ? AND u.status = 'ativo' AND ps.ativo = TRUE
        ";
        
        return $this->db->fetchAll($sql, [$usuario_id]);
    }
    
    /**
     * Obtém todas as páginas do sistema
     */
    public function getAllPages() {
        $sql = "SELECT * FROM paginas_sistema WHERE ativo = TRUE ORDER BY nome";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtém todas as permissões (matriz páginas x perfis)
     */
    public function getPermissionsMatrix() {
        $sql = "
            SELECT 
                ps.id as pagina_id,
                ps.nome as pagina_nome,
                ps.rota as pagina_rota,
                p.id as perfil_id,
                p.nome as perfil_nome,
                COALESCE(pp.permitido, FALSE) as permitido
            FROM paginas_sistema ps
            CROSS JOIN perfis p
            LEFT JOIN permissoes_paginas pp ON ps.id = pp.pagina_id AND p.id = pp.perfil_id
            WHERE ps.ativo = TRUE
            ORDER BY ps.nome, p.nome
        ";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Salva as permissões (matriz completa)
     */
    public function savePermissions($permissions) {
        try {
            $this->db->getConnection()->beginTransaction();
            
            // Limpar permissões existentes
            $this->db->query("DELETE FROM permissoes_paginas");
            
            // Inserir novas permissões
            foreach ($permissions as $permission) {
                if ($permission['permitido']) {
                    $sql = "INSERT INTO permissoes_paginas (pagina_id, perfil_id, permitido) VALUES (?, ?, ?)";
                    $this->db->query($sql, [
                        $permission['pagina_id'],
                        $permission['perfil_id'],
                        $permission['permitido']
                    ]);
                }
            }
            
            $this->db->getConnection()->commit();
            return true;
        } catch (Exception $e) {
            $this->db->getConnection()->rollback();
            throw $e;
        }
    }
    
    /**
     * Registra uma nova página no sistema
     */
    public function registerPage($nome, $rota, $descricao, $modulo) {
        $sql = "
            INSERT INTO paginas_sistema (nome, rota, descricao, modulo) 
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            rota = VALUES(rota),
            descricao = VALUES(descricao),
            modulo = VALUES(modulo),
            updated_at = CURRENT_TIMESTAMP
        ";
        
        return $this->db->query($sql, [$nome, $rota, $descricao, $modulo]);
    }
    
    /**
     * Sincroniza páginas automaticamente (busca por rotas não registradas)
     */
    public function syncPages() {
        // Esta função seria chamada para registrar automaticamente novas rotas
        // Por enquanto, retorna as páginas já registradas
        return $this->getAllPages();
    }
    
    /**
     * Log de acesso (permitido ou negado)
     */
    public function logAccess($usuario_id, $pagina_id, $acao, $ip_address = null, $user_agent = null) {
        $sql = "
            INSERT INTO logs_permissoes (usuario_id, pagina_id, acao, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?)
        ";
        
        return $this->db->query($sql, [$usuario_id, $pagina_id, $acao, $ip_address, $user_agent]);
    }
    
    /**
     * Middleware para verificar permissões
     */
    public function checkPermission($usuario_id, $pagina_nome, $ip_address = null, $user_agent = null) {
        $permission = $this->hasPermission($usuario_id, $pagina_nome);
        
        // Log da verificação
        $pagina = $this->db->fetch("SELECT id FROM paginas_sistema WHERE nome = ?", [$pagina_nome]);
        if ($pagina) {
            $this->logAccess(
                $usuario_id, 
                $pagina['id'], 
                $permission ? 'acesso_permitido' : 'acesso_negado',
                $ip_address,
                $user_agent
            );
        }
        
        return $permission;
    }
    
    /**
     * Verifica se o usuário é admin
     */
    public function isAdmin($usuario_id) {
        $sql = "
            SELECT p.nome 
            FROM usuarios u 
            JOIN perfis p ON u.perfil_id = p.id 
            WHERE u.id = ? AND u.status = 'ativo'
        ";
        
        $result = $this->db->fetch($sql, [$usuario_id]);
        return $result && in_array($result['nome'], ['admin', 'instituicao']);
    }
    
    /**
     * Obtém o perfil do usuário
     */
    public function getUserProfile($usuario_id) {
        $sql = "
            SELECT p.nome as perfil, p.descricao as perfil_descricao
            FROM usuarios u 
            JOIN perfis p ON u.perfil_id = p.id 
            WHERE u.id = ? AND u.status = 'ativo'
        ";
        
        return $this->db->fetch($sql, [$usuario_id]);
    }
    
    /**
     * Obtém as páginas permitidas para o usuário atual (para sidebar)
     */
    public function getSidebarPages() {
        $pages = [];
        
        // Se for instituição
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            $sql = "
                SELECT ps.nome, ps.rota, ps.descricao, ps.ordem
                FROM permissoes_paginas pp
                JOIN paginas_sistema ps ON ps.id = pp.pagina_id
                WHERE pp.perfil_id = 1 AND pp.permitido = 1 AND ps.ativo = TRUE
                ORDER BY ps.ordem ASC, ps.nome ASC
            ";
            $pages = $this->db->fetchAll($sql);
        } else {
            // Para usuários normais
            $user_id = $_SESSION['user_id'] ?? null;
            if ($user_id) {
                $sql = "
                    SELECT ps.nome, ps.rota, ps.descricao, ps.ordem
                    FROM permissoes_paginas pp
                    JOIN usuarios u ON u.perfil_id = pp.perfil_id
                    JOIN paginas_sistema ps ON ps.id = pp.pagina_id
                    WHERE u.id = ? AND pp.permitido = 1 AND u.status = 'ativo' AND ps.ativo = TRUE
                    ORDER BY ps.ordem ASC, ps.nome ASC
                ";
                $pages = $this->db->fetchAll($sql, [$user_id]);
            }
        }
        
        return $pages;
    }
}
