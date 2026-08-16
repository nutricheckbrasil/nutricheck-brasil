<?php

require_once APP_PATH . '/controllers/BaseController.php';

class DemonstracoesController extends BaseController {
    
    public function solicitar() {
        if ($this->isPost()) {
            $nome_completo = $this->getPost('nome');
            $cargo_funcao = $this->getPost('cargo');
            $instituicao = $this->getPost('instituicao');
            $cnpj = $this->getPost('cnpj');
            $email = $this->getPost('email');
            $telefone = $this->getPost('telefone');
            $quantidade_medicos = $this->getPost('quantidade_medicos');
            $interesse_principal = $this->getPost('interesse');
            $mensagem = $this->getPost('mensagem');
            $aceite = $this->getPost('aceite');
            
            // Validações
            $errors = [];
            
            if (empty($nome_completo)) {
                $errors[] = "Nome completo é obrigatório";
            }
            
            if (empty($cargo_funcao)) {
                $errors[] = "Cargo/função é obrigatório";
            }
            
            if (empty($instituicao)) {
                $errors[] = "Nome da instituição é obrigatório";
            }
            
            if (empty($email)) {
                $errors[] = "Email é obrigatório";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email inválido";
            }
            
            if (empty($telefone)) {
                $errors[] = "Telefone é obrigatório";
            }
            
            if (empty($aceite)) {
                $errors[] = "É necessário aceitar receber contato";
            }
            
            if (empty($errors)) {
                // Inserir na tabela de demonstrações
                $sql = "INSERT INTO demonstracoes (nome_completo, cargo_funcao, instituicao, cnpj, email, telefone, quantidade_medicos, interesse_principal, mensagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $this->db->query($sql, [
                    $nome_completo,
                    $cargo_funcao,
                    $instituicao,
                    $cnpj,
                    $email,
                    $telefone,
                    $quantidade_medicos,
                    $interesse_principal,
                    $mensagem
                ]);
                
                // Log da atividade
                $this->logActivity('solicitacao_demonstracao', "Nova solicitação de demonstração: $nome_completo - $instituicao");
                
                // Redirecionar com mensagem de sucesso
                $_SESSION['flash_message'] = "Solicitação enviada com sucesso! Entraremos em contato em breve.";
                $_SESSION['flash_type'] = 'success';
                $this->redirect('/');
            }
        }
        
        // Se chegou aqui, renderizar a página de solicitação
        $this->render('auth/register', [
            'title' => 'Solicitar Demonstração - NutriCheck',
            'errors' => $errors ?? [],
            'dados' => $_POST ?? []
        ]);
    }
    
    public function listar() {
        // Verificar se é admin ou instituição
        if (!isset($_SESSION['user_id']) || ($_SESSION['perfil_id'] != 1 && $_SESSION['perfil_id'] != 2)) {
            $this->redirect('/auth/login');
            return;
        }
        
        $page = max(1, (int)$this->getGet('page', 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        // Buscar demonstrações
        $sql = "SELECT * FROM demonstracoes ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $demonstracoes = $this->db->fetchAll($sql, [$limit, $offset]);
        
        // Contar total
        $total_result = $this->db->fetch("SELECT COUNT(*) as total FROM demonstracoes");
        $total = $total_result['total'];
        $total_pages = ceil($total / $limit);
        
        $this->render('demonstracoes/listar', [
            'title' => 'Solicitações de Demonstração - NutriCheck',
            'demonstracoes' => $demonstracoes,
            'total' => $total,
            'page' => $page,
            'total_pages' => $total_pages
        ]);
    }
    
    public function atualizarStatus($id) {
        if (!isset($_SESSION['user_id']) || ($_SESSION['perfil_id'] != 1 && $_SESSION['perfil_id'] != 2)) {
            $this->redirect('/auth/login');
            return;
        }
        
        if ($this->isPost()) {
            $status = $this->getPost('status');
            $observacoes = $this->getPost('observacoes');
            
            $sql = "UPDATE demonstracoes SET status = ?, observacoes_internas = ?, responsavel_contato = ?, data_contato = NOW() WHERE id = ?";
            $this->db->query($sql, [$status, $observacoes, $_SESSION['user_name'], $id]);
            
            $_SESSION['flash_message'] = "Status atualizado com sucesso!";
            $_SESSION['flash_type'] = 'success';
            $this->redirect('/demonstracoes/listar');
        }
    }
} 