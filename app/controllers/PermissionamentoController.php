<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/RBAC.php';

class PermissionamentoController extends BaseController {
    private $rbac;
    
    public function __construct() {
        parent::__construct();
        $this->rbac = new RBAC();
    }
    
    /**
     * Página principal do permissionamento
     */
    public function index() {
        $user_id = $this->getCurrentUserId();
        
        // Verificar se é instituição ou admin
        $is_instituicao = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao';
        $is_admin = $this->rbac->isAdmin($user_id);
        
        if (!$is_instituicao && !$is_admin) {
            $this->redirect('/dashboard', 'Acesso negado. Apenas administradores podem gerenciar permissões.', 'error');
            return;
        }
        
        // Definir variáveis para a view
        $title = 'Permissionamento de Páginas';
        $permissions = $this->rbac->getPermissionsMatrix();
        $pages = $this->rbac->getAllPages();
        $profiles = $this->getProfiles();
        
        
        
        // Calcular estatísticas
        $stats = [
            'total_paginas' => count($pages),
            'total_permissoes' => $this->getTotalPermissoes(),
            'total_perfis' => count($profiles),
            'paginas_sem_permissoes' => $this->getPaginasSemPermissoes()
        ];
        
        // Capturar o conteúdo da view
        ob_start();
        // Garantir que as variáveis estejam disponíveis
        extract([
            'title' => $title,
            'paginas' => $pages,
            'perfis' => $profiles,
            'permissoes' => $permissions,
            'stats' => $stats
        ]);
        include APP_PATH . '/views/permissionamento/index.php';
        $content = ob_get_clean();
        
        // Incluir o layout principal
        require_once APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Salvar permissões
     */
    public function save() {
        $user_id = $this->getCurrentUserId();
        
        // Verificar se é instituição ou admin
        $is_instituicao = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao';
        $is_admin = $this->rbac->isAdmin($user_id);
        
        if (!$is_instituicao && !$is_admin) {
            $this->jsonResponse(['success' => false, 'message' => 'Acesso negado']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        try {
            $permissions_data = $_POST['permissions'] ?? '';
            
            // Decodificar JSON se necessário
            if (is_string($permissions_data)) {
                $permissions = json_decode($permissions_data, true) ?? [];
            } else {
                $permissions = $permissions_data;
            }
            
            // Validar dados
            $validated_permissions = [];
            foreach ($permissions as $permission) {
                if (isset($permission['pagina_id'], $permission['perfil_id'], $permission['permitido'])) {
                    $validated_permissions[] = [
                        'pagina_id' => (int)$permission['pagina_id'],
                        'perfil_id' => (int)$permission['perfil_id'],
                        'permitido' => (bool)$permission['permitido']
                    ];
                }
            }
            
            $this->rbac->savePermissions($validated_permissions);
            
            // Log da ação
            $this->logActivity('PERMISSOES_SALVAS', 'Permissões de páginas atualizadas');
            
            $this->jsonResponse([
                'success' => true, 
                'message' => 'Permissões salvas com sucesso!'
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false, 
                'message' => 'Erro ao salvar permissões: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Sincronizar páginas (registrar novas rotas)
     */
    public function sync() {
        $user_id = $this->getCurrentUserId();
        
        // Verificar se é instituição ou admin
        $is_instituicao = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao';
        $is_admin = $this->rbac->isAdmin($user_id);
        
        if (!$is_instituicao && !$is_admin) {
            $this->jsonResponse(['success' => false, 'message' => 'Acesso negado']);
            return;
        }
        
        try {
            $pages = $this->rbac->syncPages();
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Páginas sincronizadas com sucesso!',
                'pages_count' => count($pages)
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Erro ao sincronizar páginas: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Registrar uma nova página
     */
    public function registerPage() {
        // Verificar se o usuário é admin
        if (!$this->rbac->isAdmin($this->getCurrentUserId())) {
            $this->jsonResponse(['success' => false, 'message' => 'Acesso negado']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        try {
            $nome = $_POST['nome'] ?? '';
            $rota = $_POST['rota'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $modulo = $_POST['modulo'] ?? '';
            
            if (empty($nome) || empty($rota) || empty($modulo)) {
                throw new Exception('Nome, rota e módulo são obrigatórios');
            }
            
            $this->rbac->registerPage($nome, $rota, $descricao, $modulo);
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Página registrada com sucesso!'
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Erro ao registrar página: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Obter perfis do sistema
     */
    private function getProfiles() {
        $sql = "SELECT * FROM perfis ORDER BY id";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Middleware para verificar permissões
     */
    protected function checkPermission($page_name) {
        $user_id = $this->getCurrentUserId();
        
        if (!$user_id) {
            $this->redirect('/auth/login', 'Faça login para continuar', 'error');
            return false;
        }
        
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        $has_permission = $this->rbac->checkPermission($user_id, $page_name, $ip_address, $user_agent);
        
        if (!$has_permission) {
            $this->redirect('/dashboard', 'Você não tem permissão para acessar esta página', 'error');
            return false;
        }
        
        return true;
    }
    
    /**
     * Obter total de permissões configuradas
     */
    private function getTotalPermissoes() {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM permissoes_paginas"
        );
        return $result['total'] ?? 0;
    }
    
    /**
     * Obter páginas sem permissões configuradas
     */
    private function getPaginasSemPermissoes() {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM paginas_sistema ps 
             LEFT JOIN permissoes_paginas pp ON ps.id = pp.pagina_id 
             WHERE pp.pagina_id IS NULL"
        );
        return $result['total'] ?? 0;
    }
    
    /**
     * Obter ID do usuário atual (implementar conforme seu sistema de autenticação)
     */
    private function getCurrentUserId() {
        // Implementar conforme seu sistema de sessão
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Log de atividade
     */
    protected function logActivity($action, $details = '') {
        $sql = "
            INSERT INTO logs_ativade (usuario_id, acao, detalhes, ip_address, user_agent, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ";
        
        $user_id = $this->getCurrentUserId();
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        $this->db->query($sql, [$user_id, $action, $details, $ip_address, $user_agent]);
    }
}
