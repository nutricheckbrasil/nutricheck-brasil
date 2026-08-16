<?php

require_once __DIR__ . '/../config/mercado_pago_config.php';

class MercadoPagoHelper {
    private $accessToken;
    private $publicKey;
    private $baseUrl;
    
    public function __construct() {
        $this->accessToken = MP_ACCESS_TOKEN;
        $this->publicKey = MP_PUBLIC_KEY;
        
        if (MP_ENVIRONMENT === 'sandbox') {
            $this->baseUrl = 'https://api.mercadopago.com';
        } else {
            $this->baseUrl = 'https://api.mercadopago.com';
        }
    }
    
    /**
     * Criar preferência de pagamento (PIX)
     */
    public function criarPreferenciaPix($dados) {
        $url = $this->baseUrl . '/checkout/preferences';
        
        $preference = [
            'items' => [
                [
                    'title' => $dados['titulo'],
                    'description' => $dados['descricao'] ?? '',
                    'quantity' => 1,
                    'unit_price' => (float)$dados['valor'],
                    'currency_id' => 'BRL'
                ]
            ],
            'payment_methods' => [
                'excluded_payment_types' => [
                    ['id' => 'credit_card'],
                    ['id' => 'debit_card'],
                    ['id' => 'ticket']
                ],
                'excluded_payment_methods' => [],
                'installments' => 1
            ],
            'back_urls' => [
                'success' => $dados['success_url'] ?? MP_SUCCESS_URL,
                'failure' => $dados['failure_url'] ?? MP_FAILURE_URL,
                'pending' => $dados['pending_url'] ?? MP_PENDING_URL
            ],
            'auto_return' => 'approved',
            'external_reference' => $dados['external_reference'] ?? '',
            'notification_url' => $dados['notification_url'] ?? MP_NOTIFICATION_URL,
            'statement_descriptor' => $dados['statement_descriptor'] ?? 'NUTRICHECK'
        ];
        
        $response = $this->makeRequest('POST', $url, $preference);
        
        if ($response && isset($response['id'])) {
            // Buscar QR Code do PIX
            $paymentId = $response['id'];
            $qrCode = $this->buscarQrCodePix($paymentId);
            
            return [
                'id' => $response['id'],
                'status' => $response['status'] ?? 'pending',
                'init_point' => $response['init_point'] ?? '',
                'qr_code' => $qrCode,
                'qr_code_base64' => $response['qr_code_base64'] ?? null,
                'preference_id' => $response['id']
            ];
        }
        
        return null;
    }
    
    /**
     * Buscar QR Code do PIX
     */
    public function buscarQrCodePix($preferenceId) {
        $url = $this->baseUrl . '/checkout/preferences/' . $preferenceId;
        $response = $this->makeRequest('GET', $url);
        
        if ($response && isset($response['point_of_interaction'])) {
            $poi = $response['point_of_interaction'];
            if (isset($poi['transaction_data']['qr_code'])) {
                return $poi['transaction_data']['qr_code'];
            }
            if (isset($poi['transaction_data']['qr_code_base64'])) {
                return $poi['transaction_data']['qr_code_base64'];
            }
        }
        
        return null;
    }
    
    /**
     * Buscar informações de um pagamento
     */
    public function buscarPagamento($paymentId) {
        $url = $this->baseUrl . '/v1/payments/' . $paymentId;
        return $this->makeRequest('GET', $url);
    }
    
    /**
     * Processar notificação do webhook
     */
    public function processarNotificacao($data) {
        if (!isset($data['data']['id'])) {
            return null;
        }
        
        $paymentId = $data['data']['id'];
        return $this->buscarPagamento($paymentId);
    }
    
    /**
     * Fazer requisição HTTP
     */
    private function makeRequest($method, $url, $data = null) {
        $ch = curl_init();
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->accessToken
        ];
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYPEER => true
        ]);
        
        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            error_log("Mercado Pago Error: " . $error);
            return null;
        }
        
        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true);
        }
        
        error_log("Mercado Pago HTTP Error: " . $httpCode . " - " . $response);
        return null;
    }
    
    /**
     * Mapear status do Mercado Pago para status interno
     */
    public static function mapearStatus($statusMp) {
        $map = [
            'pending' => 'pendente',
            'in_process' => 'processando',
            'approved' => 'aprovado',
            'rejected' => 'rejeitado',
            'cancelled' => 'cancelado',
            'refunded' => 'reembolsado',
            'charged_back' => 'reembolsado'
        ];
        
        return $map[$statusMp] ?? 'pendente';
    }
    
    /**
     * Validar assinatura do webhook (opcional, para segurança)
     */
    public function validarAssinaturaWebhook($xSignature, $xRequestId, $dataId) {
        // Implementar validação de assinatura se necessário
        // Por enquanto, retorna true
        return true;
    }
}

