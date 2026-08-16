<?php
/**
 * Sistema de Envio de Email para Consentimento
 * Plataforma de Vídeo Interativo
 */

require_once 'vendor/autoload.php';
require_once 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ConsentEmailManager {
    private $mailer;
    private $config;
    
    public function __construct() {
        $this->config = $this->loadEmailConfig();
        $this->setupMailer();
    }
    
    private function loadEmailConfig() {
        // Configurações de email - podem ser definidas em config.php ou variáveis de ambiente
        return [
            'smtp_host' => $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com',
            'smtp_port' => $_ENV['SMTP_PORT'] ?? 587,
            'smtp_user' => $_ENV['SMTP_USER'] ?? 'seu_email@gmail.com',
            'smtp_pass' => $_ENV['SMTP_PASS'] ?? 'sua_senha_app',
            'from_email' => $_ENV['FROM_EMAIL'] ?? 'noreply@videointerativo.com',
            'from_name' => $_ENV['FROM_NAME'] ?? 'Sistema de Vídeo Interativo',
            'consent_email' => $_ENV['CONSENT_EMAIL'] ?? 'consentimento@empresa.com',
            'admin_email' => $_ENV['ADMIN_EMAIL'] ?? 'admin@empresa.com'
        ];
    }
    
    private function setupMailer() {
        $this->mailer = new PHPMailer(true);
        
        try {
            // Configurações do servidor SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['smtp_host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['smtp_user'];
            $this->mailer->Password = $this->config['smtp_pass'];
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = $this->config['smtp_port'];
            
            // Configurações gerais
            $this->mailer->setFrom($this->config['from_email'], $this->config['from_name']);
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = 'UTF-8';
            
        } catch (Exception $e) {
            error_log("Erro na configuração do PHPMailer: " . $e->getMessage());
            throw new Exception("Falha na configuração do sistema de email");
        }
    }
    
    public function sendConsentNotification($sessionData, $videoData, $consentData = []) {
        try {
            // Limpar destinatários anteriores
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            // Configurar destinatário
            $this->mailer->addAddress($this->config['consent_email']);
            
            // Coletar informações detalhadas
            $ipAddress = $this->getRealIpAddress();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Não disponível';
            $timestamp = date('d/m/Y H:i:s');
            $timestampISO = date('c');
            
            // Dados do usuário (se fornecidos)
            $userName = $consentData['user_name'] ?? 'Não informado';
            $userEmail = $consentData['user_email'] ?? 'Não informado';
            $completionPercentage = $consentData['completion_percentage'] ?? 100;
            
            // Informações de geolocalização (básica baseada no IP)
            $locationInfo = $this->getLocationFromIP($ipAddress);
            
            // Assunto do email
            $this->mailer->Subject = "Novo Consentimento Registrado - {$videoData['title']}";
            
            // Corpo do email em HTML
            $htmlBody = $this->generateConsentEmailHTML([
                'video' => $videoData,
                'session' => $sessionData,
                'consent' => $consentData,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'timestamp' => $timestamp,
                'timestamp_iso' => $timestampISO,
                'user_name' => $userName,
                'user_email' => $userEmail,
                'completion_percentage' => $completionPercentage,
                'location' => $locationInfo
            ]);
            
            $this->mailer->Body = $htmlBody;
            
            // Versão em texto simples
            $this->mailer->AltBody = $this->generateConsentEmailText([
                'video' => $videoData,
                'ip_address' => $ipAddress,
                'timestamp' => $timestamp,
                'user_name' => $userName,
                'user_email' => $userEmail,
                'completion_percentage' => $completionPercentage
            ]);
            
            // Enviar email
            $result = $this->mailer->send();
            
            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Email de consentimento enviado com sucesso',
                    'timestamp' => $timestampISO,
                    'recipient' => $this->config['consent_email'],
                    'data' => [
                        'ip_address' => $ipAddress,
                        'user_agent' => $userAgent,
                        'location' => $locationInfo,
                        'video_title' => $videoData['title'],
                        'user_name' => $userName,
                        'user_email' => $userEmail
                    ]
                ];
            } else {
                throw new Exception('Falha no envio do email');
            }
            
        } catch (Exception $e) {
            error_log("Erro no envio de email de consentimento: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro no envio do email: ' . $e->getMessage(),
                'timestamp' => date('c'),
                'error_details' => $e->getMessage()
            ];
        }
    }
    
    private function getRealIpAddress() {
        // Verificar vários cabeçalhos para obter o IP real
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                $ip = trim($ips[0]);
                
                // Validar se é um IP válido e não privado
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        // Fallback para REMOTE_ADDR
        return $_SERVER['REMOTE_ADDR'] ?? 'IP não disponível';
    }
    
    private function getLocationFromIP($ip) {
        // Informações básicas de localização (pode ser expandido com APIs de geolocalização)
        $location = [
            'ip' => $ip,
            'country' => 'Não disponível',
            'region' => 'Não disponível',
            'city' => 'Não disponível',
            'isp' => 'Não disponível'
        ];
        
        // Para IPs locais/privados
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            $location['country'] = 'Rede Local/Privada';
            $location['region'] = 'Rede Interna';
            $location['city'] = 'Localhost/LAN';
        }
        
        return $location;
    }
    
    private function generateConsentEmailHTML($data) {
        $html = '
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Consentimento Registrado</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 8px 8px; }
        .info-box { background: white; padding: 15px; margin: 15px 0; border-radius: 6px; border-left: 4px solid #007bff; }
        .data-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .data-table th, .data-table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .data-table th { background: #e9ecef; font-weight: bold; }
        .highlight { background: #fff3cd; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛡️ Novo Consentimento Registrado</h1>
            <p>Sistema de Vídeo Interativo</p>
        </div>
        
        <div class="content">
            <div class="highlight">
                <strong>⏰ Data/Hora:</strong> ' . $data['timestamp'] . '<br>
                <strong>🌐 IP do Usuário:</strong> ' . $data['ip_address'] . '<br>
                <strong>📹 Vídeo:</strong> ' . htmlspecialchars($data['video']['title']) . '
            </div>
            
            <div class="info-box">
                <h3>📊 Informações da Sessão</h3>
                <table class="data-table">
                    <tr><th>ID da Sessão</th><td>' . ($data['session']['id'] ?? 'N/A') . '</td></tr>
                    <tr><th>Vídeo Assistido</th><td>' . htmlspecialchars($data['video']['title']) . '</td></tr>
                    <tr><th>Autor do Vídeo</th><td>' . htmlspecialchars($data['video']['author'] ?? 'N/A') . '</td></tr>
                    <tr><th>Duração do Vídeo</th><td>' . ($data['video']['duration'] ?? 0) . ' segundos</td></tr>
                    <tr><th>% de Conclusão</th><td>' . $data['completion_percentage'] . '%</td></tr>
                </table>
            </div>
            
            <div class="info-box">
                <h3>👤 Dados do Usuário</h3>
                <table class="data-table">
                    <tr><th>Nome</th><td>' . htmlspecialchars($data['user_name']) . '</td></tr>
                    <tr><th>Email</th><td>' . htmlspecialchars($data['user_email']) . '</td></tr>
                    <tr><th>Endereço IP</th><td>' . $data['ip_address'] . '</td></tr>
                    <tr><th>Localização</th><td>' . $data['location']['country'] . ', ' . $data['location']['region'] . '</td></tr>
                    <tr><th>Navegador</th><td>' . htmlspecialchars(substr($data['user_agent'], 0, 100)) . '...</td></tr>
                </table>
            </div>
            
            <div class="info-box">
                <h3>⚡ Detalhes Técnicos</h3>
                <table class="data-table">
                    <tr><th>Timestamp ISO</th><td>' . $data['timestamp_iso'] . '</td></tr>
                    <tr><th>User Agent</th><td style="word-break: break-all; font-size: 11px;">' . htmlspecialchars($data['user_agent']) . '</td></tr>
                    <tr><th>Provedor/ISP</th><td>' . $data['location']['isp'] . '</td></tr>
                </table>
            </div>
            
            <div class="highlight">
                <strong>✅ Status:</strong> Consentimento registrado com sucesso<br>
                <strong>📧 Notificação:</strong> Email enviado automaticamente<br>
                <strong>🔒 Conformidade:</strong> Dados armazenados conforme LGPD
            </div>
        </div>
        
        <div class="footer">
            <p>Este email foi gerado automaticamente pelo Sistema de Vídeo Interativo</p>
            <p>Data de envio: ' . date('d/m/Y H:i:s') . '</p>
        </div>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    private function generateConsentEmailText($data) {
        return "
NOVO CONSENTIMENTO REGISTRADO
=============================

Data/Hora: {$data['timestamp']}
IP do Usuário: {$data['ip_address']}

INFORMAÇÕES DO VÍDEO:
- Título: {$data['video']['title']}
- Autor: {$data['video']['author']}
- Duração: {$data['video']['duration']} segundos

DADOS DO USUÁRIO:
- Nome: {$data['user_name']}
- Email: {$data['user_email']}
- % de Conclusão: {$data['completion_percentage']}%

Este email foi gerado automaticamente pelo Sistema de Vídeo Interativo.
        ";
    }
    
    public function logConsentToDatabase($pdo, $sessionId, $emailResult, $consentData = []) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO consent_logs (
                    session_id, consent_given, email_sent, email_result, 
                    ip_address, user_agent, consent_data, logged_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, datetime('now'))
            ");
            
            $result = $stmt->execute([
                $sessionId,
                1, // consentimento dado
                $emailResult['success'] ? 1 : 0,
                json_encode($emailResult),
                $this->getRealIpAddress(),
                $_SERVER['HTTP_USER_AGENT'] ?? 'N/A',
                json_encode($consentData)
            ]);
            
            if ($result) {
                // Também registrar na tabela de notificações
                $this->logEmailNotification($pdo, $sessionId, $emailResult);
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Erro ao registrar log de consentimento: " . $e->getMessage());
            return false;
        }
    }
    
    private function logEmailNotification($pdo, $sessionId, $emailResult) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO email_notifications (
                    session_id, email_type, recipient_email, subject, 
                    status, error_message, email_data, sent_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, datetime('now'))
            ");
            
            $stmt->execute([
                $sessionId,
                'consent',
                $this->config['consent_email'],
                'Novo Consentimento Registrado',
                $emailResult['success'] ? 'sent' : 'failed',
                $emailResult['success'] ? null : $emailResult['message'],
                json_encode($emailResult)
            ]);
            
        } catch (Exception $e) {
            error_log("Erro ao registrar notificação de email: " . $e->getMessage());
        }
    }
    
    public function testEmailConfiguration() {
        try {
            // Teste básico de configuração
            $testResult = [
                'smtp_host' => $this->config['smtp_host'],
                'smtp_port' => $this->config['smtp_port'],
                'from_email' => $this->config['from_email'],
                'consent_email' => $this->config['consent_email'],
                'mailer_configured' => isset($this->mailer),
                'timestamp' => date('c')
            ];
            
            return [
                'success' => true,
                'message' => 'Configuração de email verificada',
                'config' => $testResult
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro na configuração: ' . $e->getMessage()
            ];
        }
    }
}
?>

