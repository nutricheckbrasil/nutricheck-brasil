<?php
/**
 * Controller para geração de PDFs
 * NutriCheck - Sistema de Nutrição Pré-Operatória
 */

require_once BASE_PATH . '/app/classes/PDFGenerator.php';
require_once BASE_PATH . '/app/classes/EmailSender.php';

class PDFController extends BaseController {
    
    /**
     * Gerar PDF de avaliação pré-anestésica
     */
    public function generateEvaluation() {
        // Verificar autenticação
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }
        
        // Obter parâmetros
        $patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : null;
        $institution_id = isset($_GET['institution_id']) ? intval($_GET['institution_id']) : null;
        $email = isset($_GET['email']) ? filter_var($_GET['email'], FILTER_VALIDATE_EMAIL) : null;
        
        if (!$patient_id || !$institution_id) {
            $_SESSION['error'] = 'Parâmetros obrigatórios ausentes';
            header('Location: ' . APP_URL . '/pacientes');
            exit;
        }
        
        try {
            $pdfGenerator = new PDFGenerator();
            $result = $pdfGenerator->generateEvaluationPDF($patient_id, $institution_id, $email);
            
            $_SESSION['success'] = 'PDF gerado com sucesso!';
            
            // Redirecionar para download
            header('Location: ' . APP_URL . $result['url']);
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Erro ao gerar PDF: ' . $e->getMessage();
            header('Location: ' . APP_URL . '/pacientes');
            exit;
        }
    }
    
    /**
     * Enviar email com relatório
     */
    public function sendReport() {
        // Verificar autenticação
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Não autenticado'], 401);
            return;
        }
        
        // Obter dados do POST
        $data = json_decode(file_get_contents('php://input'), true);
        
        $patient_id = $data['patient_id'] ?? null;
        $institution_id = $data['institution_id'] ?? null;
        $email = $data['email'] ?? null;
        
        if (!$patient_id || !$institution_id || !$email) {
            $this->jsonResponse(['success' => false, 'message' => 'Parâmetros obrigatórios ausentes'], 400);
            return;
        }
        
        try {
            // Gerar PDF
            $pdfGenerator = new PDFGenerator();
            $result = $pdfGenerator->generateEvaluationPDF($patient_id, $institution_id, $email);
            
            if ($result['email_sent']) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Relatório enviado com sucesso para ' . $email,
                    'filename' => $result['filename']
                ]);
            } else {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'PDF gerado mas falha no envio do email',
                    'filename' => $result['filename'],
                    'url' => $result['url']
                ], 500);
            }
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Enviar email de boas-vindas
     */
    public function sendWelcomeEmail() {
        // Verificar autenticação
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Não autenticado'], 401);
            return;
        }
        
        // Obter dados do POST
        $data = json_decode(file_get_contents('php://input'), true);
        
        $email = $data['email'] ?? null;
        $user_data = $data['user_data'] ?? [];
        $user_type = $data['user_type'] ?? 'paciente';
        
        if (!$email || empty($user_data)) {
            $this->jsonResponse(['success' => false, 'message' => 'Dados obrigatórios ausentes'], 400);
            return;
        }
        
        try {
            $emailSender = new EmailSender();
            
            // Gerar template
            $email_body = $emailSender->generateWelcomeEmailTemplate($user_data, $user_type);
            
            // Assunto
            $subject = "Bem-vindo ao NutriCheck";
            
            // Enviar email
            $result = $emailSender->sendEmail($email, $subject, $email_body);
            
            if ($result) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Email de boas-vindas enviado com sucesso'
                ]);
            } else {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Falha no envio do email'
                ], 500);
            }
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Resposta JSON
     */
    private function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

