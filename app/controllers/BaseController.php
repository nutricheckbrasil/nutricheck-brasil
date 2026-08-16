<?php

// BASE_URL deve ser definida em app/config/constants.php ou public/index.php

class BaseController {
    protected $db;
    protected $user;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->checkAuth();
    }
    
    protected function checkAuth() {
        // Verificar se o usuário está logado
        if (!isset($_SESSION['user_id']) && $this->requiresAuth()) {
            $this->redirect('/auth/login');
        }
    }
    
    protected function requiresAuth() {
        // Por padrão, requer autenticação
        return true;
    }
    
    protected function render($view, $data = []) {
        // Extrair dados para variáveis
        extract($data);
        
        // Incluir a view
        $view_file = APP_PATH . '/views/' . $view . '.php';
        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            throw new Exception("View não encontrada: $view");
        }
    }
    
    protected function redirect($url) {
        // Use APP_URL se definido
        $base = defined('APP_URL') ? APP_URL : (defined('BASE_URL') ? BASE_URL : '');
        // Se a URL começar com '/', concatene com base
        if (strpos($url, '/') === 0) {
            $url = rtrim($base, '/') . $url;
        }
        header("Location: $url");
        exit;
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    
    protected function getPost($key, $default = null) {
        return $_POST[$key] ?? $default;
    }
    
    protected function getGet($key, $default = null) {
        return $_GET[$key] ?? $default;
    }
    
    protected function validateRequired($data, $fields) {
        $errors = [];
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                $errors[] = "Campo '$field' é obrigatório";
            }
        }
        return $errors;
    }
    
    protected function logActivity($action, $details = '') {
        // Só registrar log se for um usuário (não instituição)
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'usuario') {
            $sql = "INSERT INTO logs_ativade (usuario_id, paciente_id, acao, detalhes, ip_address, user_agent) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $this->db->query($sql, [
                $_SESSION['user_id'] ?? null,
                $_SESSION['paciente_id'] ?? null,
                $action,
                $details,
                $_SERVER['REMOTE_ADDR'] ?? '',
                $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
        }
    }
} 