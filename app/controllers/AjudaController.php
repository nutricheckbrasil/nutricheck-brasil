<?php

class AjudaController extends BaseController {
    
    protected function requiresAuth() {
        return false; // Temporariamente sem autenticação para debug
    }
    
    private function getCurrentUserData() {
        // Se estiver logado, usar dados da sessão
        if (isset($_SESSION['user_id'])) {
            return [
                'user_id' => $_SESSION['user_id'],
                'institution_id' => $_SESSION['institution_id'] ?? $this->getUserInstitution($_SESSION['user_id'])
            ];
        }
        
        // Se não estiver logado, buscar primeiro usuário ativo no banco
        $user = $this->db->fetch("SELECT id, instituicao_id FROM usuarios WHERE status = ? LIMIT 1", [STATUS_USUARIO_ATIVO]);
        if ($user) {
            return [
                'user_id' => $user['id'],
                'institution_id' => $user['instituicao_id']
            ];
        }
        
        // Se não houver usuários no banco, retornar null para evitar erro
        return null;
    }
    
    private function getUserInstitution($userId) {
        $user = $this->db->fetch("SELECT instituicao_id FROM usuarios WHERE id = ?", [$userId]);
        return $user ? $user['instituicao_id'] : null;
    }
    
    public function index() {
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/ajuda/index.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    public function abrir_chamado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Processar formulário de abertura de chamado
            $categoria = $_POST['categoria'] ?? '';
            $urgencia = $_POST['urgencia'] ?? '';
            $assunto = $_POST['assunto'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            
            // Validar dados
            if (empty($assunto) || empty($descricao)) {
            // A view já inclui o layout principal
            include APP_PATH . '/views/ajuda/abrir_chamado.php';
                return;
            }
            
            // Buscar dados do usuário atual
            $userData = $this->getCurrentUserData();
            
            // Verificar se conseguiu obter dados do usuário
            if (!$userData) {
            // A view já inclui o layout principal
            include APP_PATH . '/views/ajuda/abrir_chamado.php';
                return;
            }
            
            // Gerar número do ticket
            $numeroTicket = 'TKT-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
            
            // Inserir no banco de dados
            $sql = "INSERT INTO chamados_suporte (usuario_id, instituicao_id, titulo, descricao, prioridade, status) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $this->db->query($sql, [
                $userData['user_id'],
                $userData['institution_id'],
                $assunto,
                $descricao,
                $urgencia,
                'aberto'
            ]);
            
            // Obter o ID do chamado criado
            $chamadoId = $this->db->lastInsertId();
            
            // Atualizar o chamado com o número do ticket
            $sqlUpdate = "UPDATE chamados_suporte SET numero_chamado = ? WHERE id = ?";
            $this->db->query($sqlUpdate, [$numeroTicket, $chamadoId]);
            
            // Redirecionar para página de sucesso
            header('Location: ' . BASE_URL . '/ajuda/meus-chamados?success=1');
            exit;
        }
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/ajuda/abrir_chamado.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    public function meus_chamados() {
        $userData = $this->getCurrentUserData();
        
        if (!$userData) {
            // A view já inclui o layout principal
            include APP_PATH . '/views/ajuda/meus_chamados.php';
            return;
        }
        
        $sql = "SELECT * FROM chamados_suporte WHERE usuario_id = ? ORDER BY created_at DESC";
        $chamados = $this->db->fetchAll($sql, [$userData['user_id']]);
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/ajuda/meus_chamados.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    public function visualizar_chamado() {
        $ticketId = $_GET['id'] ?? null;
        
        if (!$ticketId) {
            header('Location: ' . BASE_URL . '/ajuda/meus-chamados');
            exit;
        }
        
        $userData = $this->getCurrentUserData();
        
        if (!$userData) {
            header('Location: ' . BASE_URL . '/ajuda/meus-chamados');
            exit;
        }
        
        // Buscar dados do chamado
        $sql = "SELECT * FROM chamados_suporte WHERE id = ? AND usuario_id = ?";
        $chamado = $this->db->fetch($sql, [$ticketId, $userData['user_id']]);
        
        if (!$chamado) {
            header('Location: ' . BASE_URL . '/ajuda/meus-chamados');
            exit;
        }
        
        // Buscar respostas do chamado
        $sql = "SELECT * FROM respostas_chamados WHERE chamado_id = ? ORDER BY created_at ASC";
        $respostas = $this->db->fetchAll($sql, [$ticketId]);
        
        // A view já inclui o layout principal
        include APP_PATH . '/views/ajuda/visualizar_chamado.php';
    }
    
    public function responder_chamado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticketId = $_POST['ticket_id'] ?? null;
            $resposta = $_POST['resposta'] ?? '';
            
            if (!$ticketId || empty($resposta)) {
                header('Location: ' . BASE_URL . '/ajuda/visualizar-chamado?id=' . $ticketId);
                exit;
            }
            
            $userData = $this->getCurrentUserData();
            
            if (!$userData) {
                header('Location: ' . BASE_URL . '/ajuda/meus-chamados');
                exit;
            }
            
            // Inserir resposta
            $sql = "INSERT INTO respostas_chamados (chamado_id, usuario_id, resposta, tipo, created_at) 
                    VALUES (?, ?, ?, ?, NOW())";
            $this->db->query($sql, [$ticketId, $userData['user_id'], $resposta, TIPO_RESPOSTA_USUARIO]);
            
            // Atualizar status do chamado para "aguardando resposta"
            $sql = "UPDATE chamados_suporte SET status = ? WHERE id = ?";
            $this->db->query($sql, [STATUS_CHAMADO_AGUARDANDO_RESPOSTA, $ticketId]);
            
            header('Location: ' . BASE_URL . '/ajuda/visualizar-chamado?id=' . $ticketId);
            exit;
        }
        
        header('Location: ' . BASE_URL . '/ajuda/meus-chamados');
        exit;
    }
} 