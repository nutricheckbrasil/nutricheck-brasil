<?php

require_once __DIR__ . '/BaseController.php';

class FinanceiroController extends BaseController {
    
    public function __construct() {
        parent::__construct();
        // Verificar se é instituição
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'instituicao') {
            $this->redirect('/dashboard');
        }
    }
    
    /**
     * Página principal do módulo financeiro
     */
    public function index() {
        $instituicao_id = $_SESSION['user_id'];
        
        // Buscar assinatura atual
        $assinatura = $this->getAssinaturaAtual($instituicao_id);
        
        // Buscar planos disponíveis
        $planos = $this->db->fetchAll("
            SELECT * FROM planos 
            WHERE ativo = 1 
            ORDER BY ordem ASC, preco_mensal ASC
        ");
        
        // Buscar histórico de pagamentos (últimos 10)
        $historico = $this->db->fetchAll("
            SELECT p.*, pl.nome as plano_nome
            FROM pagamentos p
            JOIN assinaturas a ON p.assinatura_id = a.id
            JOIN planos pl ON a.plano_id = pl.id
            WHERE a.instituicao_id = ?
            ORDER BY p.created_at DESC
            LIMIT 10
        ", [$instituicao_id]);
        
        // Estatísticas
        $stats = [
            'total_pagamentos' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM pagamentos p
                JOIN assinaturas a ON p.assinatura_id = a.id
                WHERE a.instituicao_id = ?
            ", [$instituicao_id])['count'],
            'total_pago' => $this->db->fetch("
                SELECT COALESCE(SUM(valor), 0) as total 
                FROM pagamentos p
                JOIN assinaturas a ON p.assinatura_id = a.id
                WHERE a.instituicao_id = ? AND p.status = 'aprovado'
            ", [$instituicao_id])['total'] ?? 0,
            'pacientes_usados' => $assinatura ? $assinatura['pacientes_usados'] : 0,
            'pacientes_gratis_usados' => $this->db->fetch("
                SELECT COALESCE(pacientes_gratis_usados, 0) as total 
                FROM instituicoes 
                WHERE id = ?
            ", [$instituicao_id])['total'] ?? 0
        ];
        
        $title = 'Financeiro - Assinaturas e Pagamentos';
        
        ob_start();
        require_once APP_PATH . '/views/financeiro/index.php';
        $content = ob_get_clean();
        
        require_once APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Página de detalhes da assinatura
     */
    public function assinatura() {
        $instituicao_id = $_SESSION['user_id'];
        $assinatura = $this->getAssinaturaAtual($instituicao_id);
        
        if (!$assinatura) {
            $_SESSION['flash_message'] = 'Nenhuma assinatura encontrada.';
            $_SESSION['flash_type'] = 'warning';
            $this->redirect('/financeiro');
            return;
        }
        
        // Buscar plano
        $plano = $this->db->fetch("
            SELECT * FROM planos WHERE id = ?
        ", [$assinatura['plano_id']]);
        
        $title = 'Detalhes da Assinatura';
        
        ob_start();
        require_once APP_PATH . '/views/financeiro/assinatura.php';
        $content = ob_get_clean();
        
        require_once APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Página de histórico de pagamentos
     */
    public function historico() {
        $instituicao_id = $_SESSION['user_id'];
        
        $pagamentos = $this->db->fetchAll("
            SELECT p.*, pl.nome as plano_nome, pl.preco_mensal
            FROM pagamentos p
            JOIN assinaturas a ON p.assinatura_id = a.id
            JOIN planos pl ON a.plano_id = pl.id
            WHERE a.instituicao_id = ?
            ORDER BY p.created_at DESC
        ", [$instituicao_id]);
        
        $title = 'Histórico de Pagamentos';
        
        ob_start();
        require_once APP_PATH . '/views/financeiro/historico.php';
        $content = ob_get_clean();
        
        require_once APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Página de métodos de pagamento
     */
    public function metodos_pagamento() {
        $instituicao_id = $_SESSION['user_id'];
        $plano_id = $this->getGet('plano_id');
        
        if (!$plano_id) {
            $_SESSION['flash_message'] = 'Plano não especificado.';
            $_SESSION['flash_type'] = 'error';
            $this->redirect('/financeiro');
            return;
        }
        
        $plano = $this->db->fetch("SELECT * FROM planos WHERE id = ? AND ativo = 1", [$plano_id]);
        
        if (!$plano) {
            $_SESSION['flash_message'] = 'Plano não encontrado.';
            $_SESSION['flash_type'] = 'error';
            $this->redirect('/financeiro');
            return;
        }
        
        $title = 'Métodos de Pagamento';
        
        ob_start();
        require_once APP_PATH . '/views/financeiro/metodos-pagamento.php';
        $content = ob_get_clean();
        
        require_once APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Processar pagamento via Pix
     */
    public function processar_pagamento() {
        if (!$this->isPost()) {
            $this->json(['success' => false, 'error' => 'Método não permitido']);
            return;
        }
        
        $instituicao_id = $_SESSION['user_id'];
        $plano_id = $this->getPost('plano_id');
        $metodo = $this->getPost('metodo', 'pix');
        
        if (!$plano_id) {
            $this->json(['success' => false, 'error' => 'Plano não especificado']);
            return;
        }
        
        $plano = $this->db->fetch("SELECT * FROM planos WHERE id = ? AND ativo = 1", [$plano_id]);
        
        if (!$plano) {
            $this->json(['success' => false, 'error' => 'Plano não encontrado']);
            return;
        }
        
        try {
            // Buscar ou criar assinatura
            $assinatura = $this->getAssinaturaAtual($instituicao_id);
            
            if (!$assinatura) {
                // Criar nova assinatura
                $data_inicio = date('Y-m-d');
                $data_expiracao = date('Y-m-d', strtotime('+1 month'));
                
                $this->db->query("
                    INSERT INTO assinaturas (instituicao_id, plano_id, status, data_inicio, data_expiracao)
                    VALUES (?, ?, 'ativa', ?, ?)
                ", [$instituicao_id, $plano_id, $data_inicio, $data_expiracao]);
                
                $assinatura_id = $this->db->lastInsertId();
                
                // Atualizar instituição
                $this->db->query("
                    UPDATE instituicoes 
                    SET plano_atual_id = ?, assinatura_atual_id = ?
                    WHERE id = ?
                ", [$plano_id, $assinatura_id, $instituicao_id]);
            } else {
                $assinatura_id = $assinatura['id'];
                
                // Renovar assinatura
                $nova_data_expiracao = date('Y-m-d', strtotime($assinatura['data_expiracao'] . ' +1 month'));
                
                $this->db->query("
                    UPDATE assinaturas 
                    SET plano_id = ?, data_expiracao = ?, status = 'ativa'
                    WHERE id = ?
                ", [$plano_id, $nova_data_expiracao, $assinatura_id]);
                
                $this->db->query("
                    UPDATE instituicoes 
                    SET plano_atual_id = ?
                    WHERE id = ?
                ", [$plano_id, $instituicao_id]);
            }
            
            // Criar registro de pagamento
            $this->db->query("
                INSERT INTO pagamentos (assinatura_id, valor, metodo_pagamento, status)
                VALUES (?, ?, ?, 'pendente')
            ", [$assinatura_id, $plano['preco_mensal'], $metodo]);
            
            $pagamento_id = $this->db->lastInsertId();
            
            // Aqui você integraria com o Mercado Pago
            // Por enquanto, vamos simular o processo
            $mercado_pago_data = $this->criarPagamentoMercadoPago($plano, $pagamento_id);
            
            if ($mercado_pago_data && isset($mercado_pago_data['id'])) {
                // Atualizar pagamento com dados do Mercado Pago
                $this->db->query("
                    UPDATE pagamentos 
                    SET mercado_pago_id = ?, mercado_pago_status = ?
                    WHERE id = ?
                ", [$mercado_pago_data['id'], $mercado_pago_data['status'], $pagamento_id]);
                
                $this->json([
                    'success' => true,
                    'pagamento_id' => $pagamento_id,
                    'mercado_pago' => $mercado_pago_data,
                    'message' => 'Pagamento iniciado com sucesso'
                ]);
            } else {
                $this->json([
                    'success' => true,
                    'pagamento_id' => $pagamento_id,
                    'message' => 'Pagamento registrado. Aguardando processamento.'
                ]);
            }
            
        } catch (Exception $e) {
            $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    /**
     * Webhook do Mercado Pago
     */
    public function webhook_mercado_pago() {
        require_once APP_PATH . '/classes/MercadoPagoHelper.php';
        
        // Receber notificação do Mercado Pago
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        // Log da notificação
        error_log("Mercado Pago Webhook: " . $input);
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid data']);
            return;
        }
        
        $mp = new MercadoPagoHelper();
        
        // Processar notificação
        $payment_info = $mp->processarNotificacao($data);
        
        if (!$payment_info || !isset($payment_info['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid payment info']);
            return;
        }
        
        $payment_id = (string)$payment_info['id'];
        
        // Buscar pagamento pelo external_reference ou mercado_pago_id
        $pagamento = $this->db->fetch("
            SELECT * FROM pagamentos 
            WHERE mercado_pago_id = ? OR id = ?
            ORDER BY id DESC
            LIMIT 1
        ", [$payment_id, $payment_info['external_reference'] ?? 0]);
        
        if (!$pagamento) {
            http_response_code(404);
            echo json_encode(['error' => 'Payment not found']);
            return;
        }
        
        // Mapear status
        $status = $this->mapearStatusMercadoPago($payment_info['status']);
        
        // Atualizar pagamento
        $this->db->query("
            UPDATE pagamentos 
            SET status = ?, 
                mercado_pago_status = ?, 
                mercado_pago_id = ?,
                data_pagamento = CASE WHEN ? = 'aprovado' THEN NOW() ELSE data_pagamento END
            WHERE id = ?
        ", [
            $status, 
            $payment_info['status'], 
            $payment_id,
            $status,
            $pagamento['id']
        ]);
        
        // Se aprovado, atualizar assinatura
        if ($status === 'aprovado') {
            $this->db->query("
                UPDATE assinaturas 
                SET status = 'ativa'
                WHERE id = ?
            ", [$pagamento['assinatura_id']]);
        }
        
        http_response_code(200);
        echo json_encode(['success' => true, 'payment_id' => $payment_id]);
    }
    
    /**
     * Buscar assinatura atual da instituição
     */
    private function getAssinaturaAtual($instituicao_id) {
        return $this->db->fetch("
            SELECT a.*, p.nome as plano_nome, p.preco_mensal, p.pacientes_incluidos
            FROM assinaturas a
            JOIN planos p ON a.plano_id = p.id
            WHERE a.instituicao_id = ? 
            AND a.status = 'ativa'
            ORDER BY a.created_at DESC
            LIMIT 1
        ", [$instituicao_id]);
    }
    
    /**
     * Criar pagamento no Mercado Pago
     */
    private function criarPagamentoMercadoPago($plano, $pagamento_id) {
        require_once APP_PATH . '/classes/MercadoPagoHelper.php';
        
        try {
            $mp = new MercadoPagoHelper();
            
            $dados = [
                'titulo' => 'Assinatura ' . $plano['nome'] . ' - NutriCheck',
                'descricao' => $plano['descricao'] ?? 'Assinatura mensal do plano ' . $plano['nome'],
                'valor' => $plano['preco_mensal'],
                'external_reference' => (string)$pagamento_id,
                'success_url' => BASE_URL . '/financeiro/pagamento-sucesso',
                'failure_url' => BASE_URL . '/financeiro/pagamento-falha',
                'pending_url' => BASE_URL . '/financeiro/pagamento-pendente',
                'notification_url' => BASE_URL . '/financeiro/webhook-mercado-pago'
            ];
            
            $resultado = $mp->criarPreferenciaPix($dados);
            
            if ($resultado) {
                return $resultado;
            }
            
            // Se falhar, retornar estrutura simulada para desenvolvimento
            return [
                'id' => 'MP-' . time() . '-' . $pagamento_id,
                'status' => 'pending',
                'init_point' => '#',
                'qr_code' => '00020126360014BR.GOV.BCB.PIX0114+55119999999990204000053039865802BR5909MERCADO PAGO6009SAO PAULO62070503***6304' . strtoupper(substr(md5($pagamento_id), 0, 4))
            ];
        } catch (Exception $e) {
            error_log("Erro ao criar pagamento Mercado Pago: " . $e->getMessage());
            // Retornar estrutura simulada em caso de erro
            return [
                'id' => 'MP-ERROR-' . $pagamento_id,
                'status' => 'pending',
                'init_point' => '#',
                'qr_code' => null
            ];
        }
    }
    
    /**
     * Buscar informações do pagamento no Mercado Pago
     */
    private function buscarPagamentoMercadoPago($payment_id) {
        require_once APP_PATH . '/classes/MercadoPagoHelper.php';
        
        try {
            $mp = new MercadoPagoHelper();
            return $mp->buscarPagamento($payment_id);
        } catch (Exception $e) {
            error_log("Erro ao buscar pagamento Mercado Pago: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Mapear status do Mercado Pago para status interno
     */
    private function mapearStatusMercadoPago($status_mp) {
        require_once APP_PATH . '/classes/MercadoPagoHelper.php';
        return MercadoPagoHelper::mapearStatus($status_mp);
    }
}

