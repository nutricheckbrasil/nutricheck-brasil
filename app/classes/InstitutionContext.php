<?php

require_once __DIR__ . '/../config/database.php';

class InstitutionContext {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Define o contexto de instituição para um usuário
     */
    public function setContext($usuario_id, $instituicao_id, $token_sessao, $ip_address = null, $user_agent = null) {
        // Limpar contexto anterior do usuário
        $this->clearContext($usuario_id);
        
        // Obter perfil do usuário
        $user_sql = "SELECT perfil_id FROM usuarios WHERE id = ?";
        $user = $this->db->fetch($user_sql, [$usuario_id]);
        
        if (!$user) {
            return false;
        }
        
        // Definir novo contexto
        $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $sessao_id = uniqid('sess_', true);
        
        $sql = "
            INSERT INTO sessoes_usuario (usuario_id, instituicao_id, perfil_id, sessao_id, token_sessao, ip_address, user_agent, expires_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";
        
        return $this->db->query($sql, [$usuario_id, $instituicao_id, $user['perfil_id'], $sessao_id, $token_sessao, $ip_address, $user_agent, $expires_at]);
    }
    
    /**
     * Obtém o contexto de instituição de um usuário
     */
    public function getContext($usuario_id) {
        $sql = "
            SELECT 
                s.instituicao_id,
                s.token_sessao,
                s.expires_at,
                i.nome as instituicao_nome,
                i.slug as instituicao_slug
            FROM sessoes_usuario s
            JOIN instituicoes i ON s.instituicao_id = i.id
            WHERE s.usuario_id = ? AND s.expires_at > NOW()
            ORDER BY s.created_at DESC
            LIMIT 1
        ";
        
        return $this->db->fetch($sql, [$usuario_id]);
    }
    
    /**
     * Verifica se o usuário tem acesso a uma instituição
     */
    public function hasAccessToInstitution($usuario_id, $instituicao_id) {
        // Admin tem acesso a todas as instituições
        $rbac = new RBAC();
        if ($rbac->isAdmin($usuario_id)) {
            return true;
        }
        
        // Verificar se o usuário pertence à instituição
        $sql = "
            SELECT COUNT(*) as count
            FROM usuarios u
            WHERE u.id = ? AND u.instituicao_id = ? AND u.status = 'ativo'
        ";
        
        $result = $this->db->fetch($sql, [$usuario_id, $instituicao_id]);
        return $result && $result['count'] > 0;
    }
    
    /**
     * Obtém todas as instituições que um usuário pode acessar
     */
    public function getUserInstitutions($usuario_id) {
        $rbac = new RBAC();
        
        if ($rbac->isAdmin($usuario_id)) {
            // Admin vê todas as instituições
            $sql = "SELECT * FROM instituicoes WHERE ativo = TRUE ORDER BY nome";
            return $this->db->fetchAll($sql);
        } else {
            // Usuário vê apenas sua instituição
            $sql = "
                SELECT i.*
                FROM instituicoes i
                JOIN usuarios u ON u.instituicao_id = i.id
                WHERE u.id = ? AND u.status = 'ativo' AND i.ativo = TRUE
            ";
            return $this->db->fetchAll($sql, [$usuario_id]);
        }
    }
    
    /**
     * Limpa o contexto de um usuário
     */
    public function clearContext($usuario_id) {
        $sql = "DELETE FROM sessoes_usuario WHERE usuario_id = ?";
        return $this->db->query($sql, [$usuario_id]);
    }
    
    /**
     * Limpa contextos expirados
     */
    public function clearExpiredContexts() {
        $sql = "DELETE FROM sessoes_usuario WHERE expires_at <= NOW()";
        return $this->db->query($sql);
    }
    
    /**
     * Obtém o ID da instituição atual do usuário
     */
    public function getCurrentInstitutionId($usuario_id) {
        $context = $this->getContext($usuario_id);
        
        if ($context) {
            return $context['instituicao_id'];
        }
        
        // Se não há contexto definido, usar a instituição do usuário
        $sql = "SELECT instituicao_id FROM usuarios WHERE id = ? AND status = 'ativo'";
        $result = $this->db->fetch($sql, [$usuario_id]);
        
        if ($result) {
            // Definir como contexto
            $token = bin2hex(random_bytes(32));
            $this->setContext($usuario_id, $result['instituicao_id'], $token);
            return $result['instituicao_id'];
        }
        
        return null;
    }
    
    /**
     * Valida se uma operação pode ser executada no contexto da instituição
     */
    public function validateInstitutionAccess($usuario_id, $target_institution_id) {
        $rbac = new RBAC();
        
        // Admin pode acessar qualquer instituição
        if ($rbac->isAdmin($usuario_id)) {
            return true;
        }
        
        // Verificar se o usuário pertence à instituição
        return $this->hasAccessToInstitution($usuario_id, $target_institution_id);
    }
    
    /**
     * Filtra query por instituição (para admin seleciona contexto, para outros usa sua instituição)
     */
    public function addInstitutionFilter($usuario_id, $query, $institution_field = 'instituicao_id') {
        $rbac = new RBAC();
        
        if ($rbac->isAdmin($usuario_id)) {
            // Admin pode ver dados de qualquer instituição (baseado no contexto)
            $current_institution_id = $this->getCurrentInstitutionId($usuario_id);
            if ($current_institution_id) {
                return $query . " AND {$institution_field} = {$current_institution_id}";
            }
        } else {
            // Usuário normal vê apenas dados de sua instituição
            $user_institution_id = $this->getUserInstitutionId($usuario_id);
            if ($user_institution_id) {
                return $query . " AND {$institution_field} = {$user_institution_id}";
            }
        }
        
        // Se não conseguiu determinar a instituição, retorna query vazia
        return $query . " AND 1=0";
    }
    
    /**
     * Obtém o ID da instituição do usuário
     */
    private function getUserInstitutionId($usuario_id) {
        $sql = "SELECT instituicao_id FROM usuarios WHERE id = ? AND status = 'ativo'";
        $result = $this->db->fetch($sql, [$usuario_id]);
        return $result ? $result['instituicao_id'] : null;
    }
    
    /**
     * Obtém informações da instituição atual
     */
    public function getCurrentInstitution($usuario_id) {
        $context = $this->getContext($usuario_id);
        
        if ($context) {
            return [
                'id' => $context['instituicao_id'],
                'nome' => $context['instituicao_nome'],
                'slug' => $context['instituicao_slug']
            ];
        }
        
        // Fallback para instituição do usuário
        $sql = "
            SELECT i.id, i.nome, i.slug
            FROM instituicoes i
            JOIN usuarios u ON u.instituicao_id = i.id
            WHERE u.id = ? AND u.status = 'ativo' AND i.ativo = TRUE
        ";
        
        return $this->db->fetch($sql, [$usuario_id]);
    }
}
