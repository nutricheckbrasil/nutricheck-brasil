<?php
/**
 * Classe para envio de emails com anexos - Anestesia Check
 * 
 * Esta classe gerencia o envio de emails com anexos PDF,
 * incluindo templates personalizados e configurações robustas.
 * 
 * Adaptada do sistema ConsetiCheck para o Anestesia Check
 */

// Tentar carregar PHPMailer via autoload se existir
if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require_once BASE_PATH . '/vendor/autoload.php';
}

class EmailSender {
    private $smtp_host;
    private $smtp_port;
    private $smtp_username;
    private $smtp_password;
    private $from_email;
    private $from_name;
    
    public function __construct($config = null) {
        // Configurações padrão (podem ser sobrescritas)
        $this->smtp_host = $config['smtp_host'] ?? (defined('SMTP_HOST') ? SMTP_HOST : 'smtp.hostinger.com');
        $this->smtp_port = $config['smtp_port'] ?? (defined('SMTP_PORT') ? SMTP_PORT : 587);
        $this->smtp_username = $config['smtp_username'] ?? (defined('SMTP_USERNAME') ? SMTP_USERNAME : 'noreply@anestesiacheck.com.br');
        $this->smtp_password = $config['smtp_password'] ?? (defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '');
        $this->from_email = $config['from_email'] ?? $this->smtp_username;
        $this->from_name = $config['from_name'] ?? 'Anestesia Check';
    }
    
    /**
     * Enviar email com anexo PDF
     * 
     * @param string $to_email Email do destinatário
     * @param string $subject Assunto do email
     * @param string $body_html Corpo do email em HTML
     * @param string $pdf_path Caminho para o arquivo PDF
     * @param string $pdf_name Nome do arquivo PDF para anexo
     * @return bool Sucesso do envio
     */
    public function sendEmailWithPDF($to_email, $subject, $body_html, $pdf_path, $pdf_name = null) {
        if (!$pdf_name) {
            $pdf_name = basename($pdf_path);
        }
        
        // Verificar se o arquivo PDF existe
        if (!file_exists($pdf_path)) {
            throw new Exception("Arquivo PDF não encontrado: " . $pdf_path);
        }
        
        // Tentar envio com PHPMailer se disponível
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            return $this->sendWithPHPMailer($to_email, $subject, $body_html, $pdf_path, $pdf_name);
        }
        
        // Fallback: usar função mail() nativa do PHP
        return $this->sendWithNativeMail($to_email, $subject, $body_html, $pdf_path, $pdf_name);
    }
    
    /**
     * Enviar email simples sem anexo
     * 
     * @param string $to_email Email do destinatário
     * @param string $subject Assunto do email
     * @param string $body_html Corpo do email em HTML
     * @return bool Sucesso do envio
     */
    public function sendEmail($to_email, $subject, $body_html) {
        // Tentar envio com PHPMailer se disponível
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            return $this->sendSimpleWithPHPMailer($to_email, $subject, $body_html);
        }
        
        // Fallback: usar função mail() nativa do PHP
        return $this->sendSimpleWithNativeMail($to_email, $subject, $body_html);
    }
    
    /**
     * Enviar usando PHPMailer (método preferido)
     */
    private function sendWithPHPMailer($to_email, $subject, $body_html, $pdf_path, $pdf_name) {
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            // Configurações do servidor
            $mail->isSMTP();
            $mail->Host = $this->smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtp_username;
            $mail->Password = $this->smtp_password;
            
            // Configurar criptografia baseada na porta
            if ($this->smtp_port == 465) {
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS; // SSL
            } else {
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; // TLS
            }
            
            $mail->Port = $this->smtp_port;
            $mail->CharSet = 'UTF-8';
            $mail->SMTPDebug = 0; // Desabilitar debug em produção
            
            // Remetente e destinatário
            $mail->setFrom($this->from_email, $this->from_name);
            $mail->addAddress($to_email);
            
            // Conteúdo
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body_html;
            
            // Anexar PDF
            $mail->addAttachment($pdf_path, $pdf_name);
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Erro PHPMailer: " . $e->getMessage());
            if (function_exists('logError')) {
                logError("Erro PHPMailer ao enviar email", [
                    'to' => $to_email,
                    'subject' => $subject,
                    'error' => $e->getMessage()
                ]);
            }
            return false;
        }
    }
    
    /**
     * Enviar email simples usando PHPMailer
     */
    private function sendSimpleWithPHPMailer($to_email, $subject, $body_html) {
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            // Configurações do servidor
            $mail->isSMTP();
            $mail->Host = $this->smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtp_username;
            $mail->Password = $this->smtp_password;
            
            // Configurar criptografia baseada na porta
            if ($this->smtp_port == 465) {
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS; // SSL
            } else {
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; // TLS
            }
            
            $mail->Port = $this->smtp_port;
            $mail->CharSet = 'UTF-8';
            $mail->SMTPDebug = 0; // Desabilitar debug em produção
            
            // Remetente e destinatário
            $mail->setFrom($this->from_email, $this->from_name);
            $mail->addAddress($to_email);
            
            // Conteúdo
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body_html;
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Erro PHPMailer: " . $e->getMessage());
            if (function_exists('logError')) {
                logError("Erro PHPMailer ao enviar email", [
                    'to' => $to_email,
                    'subject' => $subject,
                    'error' => $e->getMessage()
                ]);
            }
            return false;
        }
    }
    
    /**
     * Enviar usando função mail() nativa (fallback)
     */
    private function sendWithNativeMail($to_email, $subject, $body_html, $pdf_path, $pdf_name) {
        try {
            // Ler o arquivo PDF
            $pdf_content = file_get_contents($pdf_path);
            $pdf_encoded = chunk_split(base64_encode($pdf_content));
            
            // Gerar boundary único
            $boundary = md5(time());
            
            // Cabeçalhos
            $headers = "From: {$this->from_name} <{$this->from_email}>\r\n";
            $headers .= "Reply-To: {$this->from_email}\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";
            
            // Corpo do email
            $message = "--{$boundary}\r\n";
            $message .= "Content-Type: text/html; charset=UTF-8\r\n";
            $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $message .= $body_html . "\r\n\r\n";
            
            // Anexo PDF
            $message .= "--{$boundary}\r\n";
            $message .= "Content-Type: application/pdf; name=\"{$pdf_name}\"\r\n";
            $message .= "Content-Disposition: attachment; filename=\"{$pdf_name}\"\r\n";
            $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $message .= $pdf_encoded . "\r\n";
            $message .= "--{$boundary}--\r\n";
            
            // Enviar email
            $result = mail($to_email, $subject, $message, $headers);
            
            if (!$result && function_exists('logError')) {
                logError("Erro ao enviar email via mail()", [
                    'to' => $to_email,
                    'subject' => $subject
                ]);
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Erro mail(): " . $e->getMessage());
            if (function_exists('logError')) {
                logError("Erro mail() ao enviar email", [
                    'to' => $to_email,
                    'subject' => $subject,
                    'error' => $e->getMessage()
                ]);
            }
            return false;
        }
    }
    
    /**
     * Enviar email simples usando função mail() nativa
     */
    private function sendSimpleWithNativeMail($to_email, $subject, $body_html) {
        try {
            // Cabeçalhos
            $headers = "From: {$this->from_name} <{$this->from_email}>\r\n";
            $headers .= "Reply-To: {$this->from_email}\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            
            // Enviar email
            $result = mail($to_email, $subject, $body_html, $headers);
            
            if (!$result && function_exists('logError')) {
                logError("Erro ao enviar email via mail()", [
                    'to' => $to_email,
                    'subject' => $subject
                ]);
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Erro mail(): " . $e->getMessage());
            if (function_exists('logError')) {
                logError("Erro mail() ao enviar email", [
                    'to' => $to_email,
                    'subject' => $subject,
                    'error' => $e->getMessage()
                ]);
            }
            return false;
        }
    }
    
    /**
     * Gerar template de email para relatório de avaliação pré-anestésica
     * 
     * @param array $patient_data Dados do paciente
     * @param array $institution_data Dados da instituição
     * @param array $evaluation_data Dados da avaliação
     * @param string $pdf_filename Nome do arquivo PDF
     * @return string HTML do email
     */
    public function generateEvaluationEmailTemplate($patient_data, $institution_data, $evaluation_data, $pdf_filename) {
        $html = '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Avaliação Pré-Anestésica - Anestesiocheck</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8fafc;
            padding: 20px;
            border: 1px solid #e2e8f0;
        }
        .footer {
            background-color: #64748b;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 12px;
        }
        .section {
            margin-bottom: 20px;
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #0d6efd;
        }
        .section h3 {
            margin-top: 0;
            color: #0d6efd;
        }
        .field {
            margin-bottom: 8px;
        }
        .field strong {
            display: inline-block;
            width: 150px;
            color: #4a5568;
        }
        .alert {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 12px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Relatório de Avaliação Pré-Anestésica</h1>
        <p>Anestesiocheck - Sistema de Gestão Anestésica</p>
    </div>
    
    <div class="content">
        <div class="alert">
            <strong>📎 Anexo:</strong> Este email contém o relatório completo em PDF (<strong>' . htmlspecialchars($pdf_filename) . '</strong>) com todos os detalhes da avaliação pré-anestésica.
        </div>
        
        <div class="section">
            <h3>👤 Dados do Paciente</h3>
            <div class="field"><strong>Nome:</strong> ' . htmlspecialchars($patient_data['nome'] ?? $patient_data['name'] ?? 'N/A') . '</div>
            <div class="field"><strong>Email:</strong> ' . htmlspecialchars($patient_data['email'] ?? 'N/A') . '</div>
            <div class="field"><strong>Telefone:</strong> ' . htmlspecialchars($patient_data['telefone'] ?? $patient_data['phone'] ?? 'N/A') . '</div>
            <div class="field"><strong>CPF:</strong> ' . htmlspecialchars($patient_data['cpf'] ?? 'N/A') . '</div>
            <div class="field"><strong>Data de Nascimento:</strong> ' . (isset($patient_data['data_nascimento']) ? date('d/m/Y', strtotime($patient_data['data_nascimento'])) : 'N/A') . '</div>
        </div>
        
        <div class="section">
            <h3>🏥 Dados da Instituição</h3>
            <div class="field"><strong>Nome:</strong> ' . htmlspecialchars($institution_data['nome'] ?? $institution_data['name'] ?? 'N/A') . '</div>
            <div class="field"><strong>Email:</strong> ' . htmlspecialchars($institution_data['email'] ?? 'N/A') . '</div>
            <div class="field"><strong>Telefone:</strong> ' . htmlspecialchars($institution_data['telefone'] ?? $institution_data['phone'] ?? 'N/A') . '</div>
            <div class="field"><strong>CNPJ:</strong> ' . htmlspecialchars($institution_data['cnpj'] ?? 'N/A') . '</div>
        </div>
        
        <div class="section">
            <h3>📊 Dados da Avaliação</h3>
            <div class="field"><strong>Data da Avaliação:</strong> ' . (isset($evaluation_data['data_avaliacao']) ? date('d/m/Y H:i', strtotime($evaluation_data['data_avaliacao'])) : date('d/m/Y H:i')) . '</div>
            <div class="field"><strong>Status:</strong> ' . htmlspecialchars($evaluation_data['status'] ?? 'Concluída') . '</div>
            <div class="field"><strong>Classificação ASA:</strong> ' . htmlspecialchars($evaluation_data['classificacao_asa'] ?? 'N/A') . '</div>
        </div>
        
        <div class="section">
            <h3>ℹ️ Informações Importantes</h3>
            <p><strong>Data de Geração:</strong> ' . date('d/m/Y H:i:s') . '</p>
            <p><strong>Validade:</strong> Este relatório é válido para fins de auditoria e registro médico.</p>
            <p><strong>Confidencialidade:</strong> Este documento contém informações sensíveis e deve ser tratado com confidencialidade conforme LGPD.</p>
        </div>
    </div>
    
    <div class="footer">
        <p>Anestesiocheck - Sistema de Gestão de Avaliação Pré-Anestésica</p>
        <p>Este email foi gerado automaticamente pelo sistema. Não responda a este email.</p>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * Gerar template de email de notificação de cadastro
     * 
     * @param array $user_data Dados do usuário
     * @param string $user_type Tipo de usuário (paciente, anestesista, instituição)
     * @return string HTML do email
     */
    public function generateWelcomeEmailTemplate($user_data, $user_type = 'paciente') {
        $titles = [
            'paciente' => 'Bem-vindo ao Anestesiocheck',
            'anestesista' => 'Cadastro de Anestesista Confirmado',
            'instituicao' => 'Cadastro de Instituição Confirmado'
        ];
        
        $title = $titles[$user_type] ?? 'Cadastro Confirmado';
        
        // Verificar se há link de acesso para incluir no email
        $link_acesso = $user_data['link_acesso'] ?? null;
        $token_acesso = $user_data['token_acesso'] ?? null;
        $link_section = '';
        
        if ($link_acesso && $user_type === 'paciente') {
            // Pegar link do vídeo dos dados do usuário (já vem completo do cadastro)
            $link_video = $user_data['link_video'] ?? null;
            
            // Se não foi passado, construir usando token
            if (!$link_video && $token_acesso) {
                $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $link_video = $protocol . "://" . $host . "/paciente_video.php?token=" . $token_acesso;
            }
            
            $link_section = '
        <div class="section" style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h3 style="color: white; margin-top: 0;">🎬 Assista ao Vídeo Educativo</h3>
            <p style="color: white;">Antes de prosseguir, assista ao vídeo com orientações importantes sobre o procedimento anestésico:</p>
            <a href="' . htmlspecialchars($link_video) . '" class="button" style="background-color: white; color: #0d6efd; font-weight: bold; font-size: 16px; padding: 15px 30px; margin: 10px 0; display: inline-block; text-decoration: none; border-radius: 8px;">
                ▶️ Assistir Vídeo Interativo
            </a>
            <p style="color: white; font-size: 14px; margin-top: 10px;">
                <strong>Duração:</strong> Aproximadamente 5 minutos<br>
                <strong>Conteúdo:</strong> Orientações pré-anestésicas e perguntas interativas
            </p>
        </div>
        
        <div class="section" style="text-align: center; background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); color: white;">
            <h3 style="color: white; margin-top: 0;">📝 Acesse o Termo de Consentimento</h3>
            <p style="color: white;">Após assistir ao vídeo, acesse o termo de consentimento para autorizar o procedimento:</p>
            <a href="' . htmlspecialchars($link_acesso) . '" class="button" style="background-color: white; color: #764ba2; font-weight: bold; font-size: 16px; padding: 15px 30px; margin: 10px 0; display: inline-block; text-decoration: none; border-radius: 8px;">
                ➡️ Acessar Termo de Consentimento
            </a>
            <p style="color: white; font-size: 12px; margin-top: 15px;">
                <strong>Importante:</strong> Estes links são únicos e pessoais. Não compartilhe com outras pessoas.
            </p>
        </div>
        
        <div class="section">
            <h3>📋 Sua Jornada de Preparação</h3>
            <ol style="line-height: 2.5;">
                <li><strong>🎬 Vídeo Educativo:</strong> Assista ao vídeo com orientações pré-anestésicas (5 min)</li>
                <li><strong>❓ Perguntas Interativas:</strong> Responda às perguntas durante o vídeo</li>
                <li><strong>📄 Termo LGPD:</strong> Leia e aceite o termo de privacidade de dados</li>
                <li><strong>📝 Termo de Consentimento:</strong> Leia e autorize o procedimento anestésico</li>
                <li><strong>✅ Confirmação:</strong> Receba confirmação do seu consentimento</li>
            </ol>
            <p><strong>⏱️ Tempo total estimado:</strong> 10-15 minutos</p>
            <p style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 10px; border-radius: 5px; color: #856404;">
                <strong>⚠️ Atenção:</strong> É importante completar todas as etapas para que seu procedimento possa ser realizado com segurança.
            </p>
        </div>';
        }
        
        $html = '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . '</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background-color: #ffffff;
            padding: 0;
            border: 1px solid #e2e8f0;
        }
        .footer {
            background-color: #64748b;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 12px;
        }
        .section {
            margin: 0;
            background-color: white;
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        .section:last-child {
            border-bottom: none;
        }
        .section h3 {
            margin-top: 0;
            color: #0d6efd;
        }
        .button {
            display: inline-block;
            background-color: #0d6efd;
            color: white !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            margin: 15px 0;
            font-weight: bold;
            font-size: 16px;
        }
        .alert {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✅ ' . $title . '</h1>
        <p style="margin: 5px 0 0 0;">Sistema de Gestão de Avaliação Pré-Anestésica</p>
    </div>
    
    <div class="content">
        <div class="section">
            <p>Olá, <strong>' . htmlspecialchars($user_data['nome'] ?? $user_data['name'] ?? 'Usuário') . '</strong>!</p>
            <p>Seu cadastro foi realizado com sucesso no sistema <strong>Anestesia Check</strong>.</p>
            <p><strong>📧 Email cadastrado:</strong> ' . htmlspecialchars($user_data['email'] ?? 'N/A') . '</p>
            ' . (!empty($user_data['telefone']) ? '<p><strong>📱 Telefone:</strong> ' . htmlspecialchars($user_data['telefone']) . '</p>' : '') . '
        </div>
        
        ' . $link_section . '
        
        <div class="section">
            <h3>ℹ️ Informações Importantes</h3>
            <ul style="line-height: 2;">
                <li>Mantenha seus dados de contato atualizados</li>
                <li>Responda todas as perguntas do questionário com atenção</li>
                <li>Em caso de dúvidas, entre em contato com a equipe médica</li>
                <li>Guarde este email para referência futura</li>
            </ul>
        </div>
        
        <div class="section">
            <div class="alert">
                <strong>🔒 Segurança e Privacidade</strong><br>
                Seus dados são protegidos conforme a Lei Geral de Proteção de Dados (LGPD). 
                Todas as informações fornecidas são confidenciais e utilizadas exclusivamente 
                para fins médicos e de gestão do seu procedimento anestésico.
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p><strong>Anestesia Check</strong></p>
        <p>Sistema de Gestão de Avaliação Pré-Anestésica</p>
        <p style="margin-top: 10px; font-size: 11px;">
            Este email foi gerado automaticamente pelo sistema. Por favor, não responda a este email.<br>
            Em caso de dúvidas, entre em contato com a instituição responsável pelo seu atendimento.
        </p>
    </div>
</body>
</html>';
        
        return $html;
    }
}

