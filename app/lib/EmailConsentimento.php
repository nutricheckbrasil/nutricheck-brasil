<?php
/**
 * Classe para Envio de Email de Confirmação de Consentimento
 * NutriCheck
 */

require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailConsentimento
{
    private $mailer;
    private $config;
    
    public function __construct()
    {
        // Carregar configurações de email
        if (file_exists(__DIR__ . '/../config/email_config.php')) {
            require_once __DIR__ . '/../config/email_config.php';
            $this->config = getEmailConfig();
        } else {
            // Configurações padrão
            $this->config = [
                'smtp_host' => 'smtp.hostinger.com',
                'smtp_port' => 465,
                'smtp_username' => 'noreply@consenticheck.com.br',
                'smtp_password' => 'Texbr2007*/',
                'smtp_encryption' => 'ssl',
                'from_email' => 'noreply@consenticheck.com.br',
                'from_name' => 'NutriCheck'
            ];
        }
        
        $this->setupMailer();
    }
    
    private function setupMailer()
    {
        $this->mailer = new PHPMailer(true);
        
        try {
            // Configurações do servidor SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['smtp_host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['smtp_username'];
            $this->mailer->Password = $this->config['smtp_password'];
            
            // SSL ou TLS
            if ($this->config['smtp_encryption'] === 'ssl') {
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            
            $this->mailer->Port = $this->config['smtp_port'];
            
            // Configurações gerais
            $this->mailer->setFrom($this->config['from_email'], $this->config['from_name']);
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = 'UTF-8';
            
            // Debug (desativar em produção)
            // $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            
        } catch (Exception $e) {
            error_log("Erro na configuração do PHPMailer: " . $e->getMessage());
            throw new Exception("Falha na configuração do sistema de email");
        }
    }
    
    /**
     * Enviar email de confirmação de consentimento para o paciente
     *
     * @param array $paciente Dados do paciente
     * @param array $consentimento Dados do consentimento
     * @return bool Sucesso ou falha
     */
    public function enviarConfirmacaoPaciente($paciente, $consentimento)
    {
        try {
            // Limpar destinatários anteriores
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            // Configurar destinatário
            $this->mailer->addAddress($paciente['email'], $paciente['nome']);
            
            // Assunto
            $this->mailer->Subject = 'Confirmação de Consentimento - NutriCheck';
            
            // Corpo do email
            $html = $this->gerarHTMLConfirmacaoPaciente($paciente, $consentimento);
            $this->mailer->Body = $html;
            
            // Versão texto simples
            $this->mailer->AltBody = $this->gerarTextoConfirmacaoPaciente($paciente, $consentimento);
            
            // Enviar
            $resultado = $this->mailer->send();
            
            // Log de sucesso
            error_log("Email de confirmação enviado para: " . $paciente['email']);
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Erro ao enviar email para {$paciente['email']}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Enviar notificação para a equipe médica
     *
     * @param array $paciente Dados do paciente
     * @param array $consentimento Dados do consentimento
     * @param string $emailDestino Email da equipe médica
     * @return bool Sucesso ou falha
     */
    public function enviarNotificacaoEquipe($paciente, $consentimento, $emailDestino)
    {
        try {
            // Limpar destinatários anteriores
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            // Configurar destinatário
            $this->mailer->addAddress($emailDestino);
            
            // Assunto
            $this->mailer->Subject = 'Novo Consentimento Registrado - ' . $paciente['nome'];
            
            // Corpo do email
            $html = $this->gerarHTMLNotificacaoEquipe($paciente, $consentimento);
            $this->mailer->Body = $html;
            
            // Versão texto simples
            $this->mailer->AltBody = $this->gerarTextoNotificacaoEquipe($paciente, $consentimento);
            
            // Enviar
            $resultado = $this->mailer->send();
            
            // Log de sucesso
            error_log("Notificação de consentimento enviada para: " . $emailDestino);
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Erro ao enviar notificação para {$emailDestino}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Gerar HTML do email de confirmação para o paciente
     */
    private function gerarHTMLConfirmacaoPaciente($paciente, $consentimento)
    {
        $nome = htmlspecialchars($paciente['nome']);
        $procedimento = htmlspecialchars($paciente['procedimento_nome'] ?? 'Procedimento');
        $instituicao = htmlspecialchars($paciente['instituicao_nome'] ?? 'Instituição');
        $data_procedimento = isset($paciente['data_procedimento']) ? date('d/m/Y', strtotime($paciente['data_procedimento'])) : 'A definir';
        $data_aceite = date('d/m/Y H:i:s', strtotime($consentimento['data_aceite']));
        $ip = htmlspecialchars($consentimento['ip_address'] ?? 'N/A');
        
        $html = <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Consentimento</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">NutriCheck</h1>
        <p style="color: white; margin: 10px 0 0 0;">Confirmação de Consentimento</p>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h2 style="color: #667eea; margin-top: 0;">Olá, {$nome}!</h2>
            
            <p>Seu consentimento para o procedimento foi registrado com sucesso.</p>
            
            <div style="background: #e8f5e9; border-left: 4px solid #4caf50; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <p style="margin: 0; color: #2e7d32; font-weight: bold;">✓ Consentimento Confirmado</p>
            </div>
            
            <h3 style="color: #667eea; border-bottom: 2px solid #667eea; padding-bottom: 10px;">Detalhes do Procedimento</h3>
            
            <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-weight: bold; width: 40%;">Procedimento:</td>
                    <td style="padding: 10px; background: white; border: 1px solid #ddd;">{$procedimento}</td>
                </tr>
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-weight: bold;">Instituição:</td>
                    <td style="padding: 10px; background: white; border: 1px solid #ddd;">{$instituicao}</td>
                </tr>
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-weight: bold;">Data Prevista:</td>
                    <td style="padding: 10px; background: white; border: 1px solid #ddd;">{$data_procedimento}</td>
                </tr>
            </table>
            
            <h3 style="color: #667eea; border-bottom: 2px solid #667eea; padding-bottom: 10px; margin-top: 30px;">Informações do Consentimento</h3>
            
            <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-weight: bold; width: 40%;">Data/Hora do Aceite:</td>
                    <td style="padding: 10px; background: white; border: 1px solid #ddd;">{$data_aceite}</td>
                </tr>
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-weight: bold;">Endereço IP:</td>
                    <td style="padding: 10px; background: white; border: 1px solid #ddd;">{$ip}</td>
                </tr>
            </table>
            
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 25px 0; border-radius: 4px;">
                <p style="margin: 0; color: #856404;"><strong>Próximos Passos:</strong></p>
                <ul style="margin: 10px 0 0 0; padding-left: 20px; color: #856404;">
                    <li>Aguarde contato da equipe médica</li>
                    <li>Siga as orientações pré-operatórias que serão enviadas</li>
                    <li>Em caso de dúvidas, entre em contato com a instituição</li>
                </ul>
            </div>
            
            <p style="margin-top: 30px; font-size: 14px; color: #666;">
                Este é um email automático. Por favor, não responda.<br>
                Em caso de dúvidas, entre em contato com {$instituicao}.
            </p>
        </div>
        
        <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p style="font-size: 12px; color: #999; margin: 5px 0;">
                © 2025 NutriCheck - Todos os direitos reservados
            </p>
            <p style="font-size: 12px; color: #999; margin: 5px 0;">
                Este documento está protegido pela LGPD (Lei Geral de Proteção de Dados)
            </p>
        </div>
    </div>
</body>
</html>
HTML;
        
        return $html;
    }
    
    /**
     * Gerar versão texto simples do email para o paciente
     */
    private function gerarTextoConfirmacaoPaciente($paciente, $consentimento)
    {
        $nome = $paciente['nome'];
        $procedimento = $paciente['procedimento_nome'] ?? 'Procedimento';
        $instituicao = $paciente['instituicao_nome'] ?? 'Instituição';
        $data_procedimento = isset($paciente['data_procedimento']) ? date('d/m/Y', strtotime($paciente['data_procedimento'])) : 'A definir';
        $data_aceite = date('d/m/Y H:i:s', strtotime($consentimento['data_aceite']));
        
        return <<<TEXT
NutriCheck
Confirmação de Consentimento

Olá, {$nome}!

Seu consentimento para o procedimento foi registrado com sucesso.

DETALHES DO PROCEDIMENTO
------------------------
Procedimento: {$procedimento}
Instituição: {$instituicao}
Data Prevista: {$data_procedimento}

INFORMAÇÕES DO CONSENTIMENTO
----------------------------
Data/Hora do Aceite: {$data_aceite}

PRÓXIMOS PASSOS
--------------
- Aguarde contato da equipe médica
- Siga as orientações pré-operatórias que serão enviadas
- Em caso de dúvidas, entre em contato com a instituição

Este é um email automático. Por favor, não responda.
Em caso de dúvidas, entre em contato com {$instituicao}.

© 2025 NutriCheck - Todos os direitos reservados
Este documento está protegido pela LGPD (Lei Geral de Proteção de Dados)
TEXT;
    }
    
    /**
     * Gerar HTML do email de notificação para a equipe médica
     */
    private function gerarHTMLNotificacaoEquipe($paciente, $consentimento)
    {
        $nome = htmlspecialchars($paciente['nome']);
        $cpf = htmlspecialchars($paciente['cpf'] ?? 'N/A');
        $email = htmlspecialchars($paciente['email']);
        $telefone = htmlspecialchars($paciente['telefone'] ?? 'N/A');
        $procedimento = htmlspecialchars($paciente['procedimento_nome'] ?? 'Procedimento');
        $data_aceite = date('d/m/Y H:i:s', strtotime($consentimento['data_aceite']));
        $ip = htmlspecialchars($consentimento['ip_address'] ?? 'N/A');
        $user_agent = htmlspecialchars($consentimento['user_agent'] ?? 'N/A');
        
        $html = <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Novo Consentimento Registrado</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 700px; margin: 0 auto; padding: 20px;">
    <div style="background: #2c3e50; padding: 25px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 22px;">Novo Consentimento Registrado</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;">
        <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            
            <div style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 25px; border-radius: 4px;">
                <p style="margin: 0; color: #155724; font-weight: bold;">✓ Novo consentimento aceito pelo paciente</p>
            </div>
            
            <h3 style="color: #2c3e50; border-bottom: 2px solid #667eea; padding-bottom: 10px;">Dados do Paciente</h3>
            
            <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-weight: bold; width: 35%;">Nome:</td>
                    <td style="padding: 10px; background: white; border: 1px solid #ddd;">{$nome}</td>
                </tr>
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-weight: bold;">CPF:</td>
                    <td style="padding: 10px; background: white; border: 1px solid #ddd;">{$cpf}</td>
                </tr>
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-weight: bold;">Email:</td>
                    <td style="padding: 10px; background: white; border: 1px solid #ddd;">{$email}</td>
                </tr>
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-weight: bold;">Telefone:</td>
                    <td style="padding: 10px; background: white; border: 1px solid #ddd;">{$telefone}</td>
                </tr>
            </table>
            
            <h3 style="color: #2c3e50; border-bottom: 2px solid #667eea; padding-bottom: 10px; margin-top: 30px;">Informações do Consentimento</h3>
            
            <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-weight: bold; width: 35%;">Procedimento:</td>
                    <td style="padding: 10px; background: white; border: 1px solid #ddd;">{$procedimento}</td>
                </tr>
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-weight: bold;">Data/Hora do Aceite:</td>
                    <td style="padding: 10px; background: white; border: 1px solid #ddd;">{$data_aceite}</td>
                </tr>
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-weight: bold;">Endereço IP:</td>
                    <td style="padding: 10px; background: white; border: 1px solid #ddd;">{$ip}</td>
                </tr>
                <tr>
                    <td style="padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-weight: bold;">Navegador:</td>
                    <td style="padding: 10px; background: white; border: 1px solid #ddd; font-size: 11px;">{$user_agent}</td>
                </tr>
            </table>
            
            <div style="background: #cce5ff; border-left: 4px solid #004085; padding: 15px; margin: 25px 0; border-radius: 4px;">
                <p style="margin: 0; color: #004085;"><strong>Ação Necessária:</strong></p>
                <p style="margin: 10px 0 0 0; color: #004085;">
                    Acesse o sistema NutriCheck para visualizar os detalhes completos do consentimento e dar continuidade ao atendimento.
                </p>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p style="font-size: 12px; color: #999; margin: 5px 0;">
                © 2025 NutriCheck - Sistema de Gestão de Consentimentos
            </p>
        </div>
    </div>
</body>
</html>
HTML;
        
        return $html;
    }
    
    /**
     * Gerar versão texto simples do email para a equipe médica
     */
    private function gerarTextoNotificacaoEquipe($paciente, $consentimento)
    {
        $nome = $paciente['nome'];
        $cpf = $paciente['cpf'] ?? 'N/A';
        $email = $paciente['email'];
        $procedimento = $paciente['procedimento_nome'] ?? 'Procedimento';
        $data_aceite = date('d/m/Y H:i:s', strtotime($consentimento['data_aceite']));
        $ip = $consentimento['ip_address'] ?? 'N/A';
        
        return <<<TEXT
NutriCheck
Novo Consentimento Registrado

DADOS DO PACIENTE
-----------------
Nome: {$nome}
CPF: {$cpf}
Email: {$email}

INFORMAÇÕES DO CONSENTIMENTO
----------------------------
Procedimento: {$procedimento}
Data/Hora do Aceite: {$data_aceite}
Endereço IP: {$ip}

AÇÃO NECESSÁRIA
--------------
Acesse o sistema ANutriCheck para visualizar os detalhes completos 
do consentimento e dar continuidade ao atendimento.

© 2025 NutriCheck - Sistema de Gestão de Consentimentos
TEXT;
    }
}
?>

