<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/RBAC.php';

class PerfilController extends BaseController {
    private $rbac;
    
    public function __construct() {
        parent::__construct();
        $this->rbac = new RBAC();
    }
    
    /**
     * Exibir perfil baseado no tipo de usuário
     */
    public function index() {
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            $this->redirect('/auth/login');
            return;
        }
        
        // Verificar se é instituição
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            $this->perfilInstituicao();
        } else {
            $this->perfilUsuario();
        }
    }
    
    /**
     * Perfil da instituição
     */
    private function perfilInstituicao() {
        $instituicao_id = $_SESSION['user_id'];
        
        // Buscar dados da instituição
        $instituicao = $this->db->fetch("
            SELECT * FROM instituicoes 
            WHERE id = ? AND status = 'ativo'
        ", [$instituicao_id]);
        
        if (!$instituicao) {
            $_SESSION['flash_message'] = 'Instituição não encontrada.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('/dashboard');
            return;
        }
        
        // Buscar estatísticas da instituição
        $stats = $this->getStatsInstituicao($instituicao_id);
        
        $data = [
            'title' => 'Meu Perfil - Instituição',
            'instituicao' => $instituicao,
            'stats' => $stats,
            'user_type' => 'instituicao'
        ];
        
        ob_start();
        include APP_PATH . '/views/perfil/instituicao.php';
        $content = ob_get_clean();
        
        include APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Perfil do usuário (anestesista, funcionário, etc.)
     */
    private function perfilUsuario() {
        $user_id = $_SESSION['user_id'];
        
        // Buscar dados do usuário
        $usuario = $this->db->fetch("
            SELECT u.*, p.nome as perfil_nome, p.descricao as perfil_descricao,
                   i.nome as instituicao_nome
            FROM usuarios u
            LEFT JOIN perfis p ON p.id = u.perfil_id
            LEFT JOIN instituicoes i ON i.id = u.instituicao_id
            WHERE u.id = ? AND u.status = 'ativo'
        ", [$user_id]);
        
        if (!$usuario) {
            $_SESSION['flash_message'] = 'Usuário não encontrado.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('/dashboard');
            return;
        }
        
        // Buscar estatísticas do usuário
        $stats = $this->getStatsUsuario($user_id, $usuario['perfil_id']);
        
        $data = [
            'title' => 'Meu Perfil - ' . ucfirst($usuario['perfil_nome']),
            'usuario' => $usuario,
            'stats' => $stats,
            'user_type' => 'usuario'
        ];
        
        ob_start();
        include APP_PATH . '/views/perfil/usuario.php';
        $content = ob_get_clean();
        
        include APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Atualizar perfil da instituição
     */
    public function updateInstituicao() {
        $this->requiresAuth();
        
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'instituicao') {
            $this->redirect('/dashboard');
            return;
        }
        
        if ($this->isPost()) {
            $nome = $this->getPost('nome');
            $email = $this->getPost('email');
            $telefone = $this->getPost('telefone');
            $endereco = $this->getPost('endereco');
            $cnpj = $this->getPost('cnpj');
            
            $errors = [];
            
            if (empty($nome)) $errors[] = 'Nome é obrigatório.';
            if (empty($email)) $errors[] = 'Email é obrigatório.';
            
            // Verificar se email já existe em outra instituição
            if (!empty($email)) {
                $existing = $this->db->fetch("
                    SELECT id FROM instituicoes 
                    WHERE email = ? AND id != ?
                ", [$email, $_SESSION['user_id']]);
                
                if ($existing) {
                    $errors[] = 'Este email já está em uso por outra instituição.';
                }
            }
            
            if (empty($errors)) {
                $sql = "UPDATE instituicoes SET nome = ?, email = ?, telefone = ?, endereco = ?, cnpj = ?, updated_at = NOW() WHERE id = ?";
                $this->db->query($sql, [$nome, $email, $telefone, $endereco, $cnpj, $_SESSION['user_id']]);
                
                $_SESSION['user_name'] = $nome;
                $_SESSION['flash_message'] = 'Perfil atualizado com sucesso!';
                $_SESSION['flash_type'] = 'success';
                $this->redirect('/perfil');
            } else {
                $_SESSION['flash_message'] = implode('<br>', $errors);
                $_SESSION['flash_type'] = 'danger';
                $this->redirect('/perfil');
            }
        } else {
            $this->redirect('/perfil');
        }
    }
    
    /**
     * Atualizar perfil do usuário
     */
    public function updateUsuario() {
        $this->requiresAuth();
        
        if ($this->isPost()) {
            $nome = $this->getPost('nome');
            $email = $this->getPost('email');
            $telefone = $this->getPost('telefone');
            $crm = $this->getPost('crm');
            
            $errors = [];
            
            if (empty($nome)) $errors[] = 'Nome é obrigatório.';
            if (empty($email)) $errors[] = 'Email é obrigatório.';
            
            // Verificar se email já existe em outro usuário
            if (!empty($email)) {
                $existing = $this->db->fetch("
                    SELECT id FROM usuarios 
                    WHERE email = ? AND id != ?
                ", [$email, $_SESSION['user_id']]);
                
                if ($existing) {
                    $errors[] = 'Este email já está em uso por outro usuário.';
                }
            }
            
            if (empty($errors)) {
                $sql = "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, crm = ?, updated_at = NOW() WHERE id = ?";
                $this->db->query($sql, [$nome, $email, $telefone, $crm, $_SESSION['user_id']]);
                
                $_SESSION['user_name'] = $nome;
                $_SESSION['flash_message'] = 'Perfil atualizado com sucesso!';
                $_SESSION['flash_type'] = 'success';
                $this->redirect('/perfil');
            } else {
                $_SESSION['flash_message'] = implode('<br>', $errors);
                $_SESSION['flash_type'] = 'danger';
                $this->redirect('/perfil');
            }
        } else {
            $this->redirect('/perfil');
        }
    }
    
    /**
     * Buscar estatísticas da instituição
     */
    private function getStatsInstituicao($instituicao_id) {
        $stats = [
            'total_anestesistas' => $this->db->fetch("
                SELECT COUNT(*) as total FROM usuarios 
                WHERE instituicao_id = ? AND perfil_id = 3 AND status = 'ativo'
            ", [$instituicao_id])['total'],
            
            'total_pacientes' => $this->db->fetch("
                SELECT COUNT(*) as total FROM pacientes 
                WHERE instituicao_id = ?
            ", [$instituicao_id])['total'],
            
            'total_agendamentos' => 0, // Temporariamente 0 até implementar agendamentos
            
            'agendamentos_hoje' => 0 // Temporariamente 0 até implementar agendamentos
        ];
        
        return $stats;
    }
    
    /**
     * Buscar estatísticas do usuário
     */
    private function getStatsUsuario($user_id, $perfil_id) {
        $stats = [];
        
        // Se for anestesista
        if ($perfil_id == 3) {
            $stats = [
                'total_pacientes' => $this->db->fetch("
                    SELECT COUNT(*) as total FROM pacientes 
                    WHERE anestesista_id = ?
                ", [$user_id])['total'],
                
                'agendamentos_hoje' => 0, // Temporariamente 0 até implementar agendamentos
                
                'agendamentos_semana' => 0 // Temporariamente 0 até implementar agendamentos
            ];
        } else {
            // Para outros perfis (sem tabela de logs por enquanto)
            $stats = [
                'total_acessos' => 0,
                'ultimo_acesso' => null
            ];
        }
        
        return $stats;
    }
}
