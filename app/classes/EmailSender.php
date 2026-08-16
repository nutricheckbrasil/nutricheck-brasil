<?php
/**
 * Classe para envio de emails - VERSÃO COM MAIL() PRIORITÁRIO
 * 
 * Esta versão SEMPRE usa a função mail() do PHP como método principal,
 * sem depender de PHPMailer ou outras bibliotecas externas.
 * 
 * NutriCheck
 */

class EmailSender {
    private $from_email;
    private $from_name;
    
    public function __construct($config = null) {
        $this->from_email = $config['from_email'] ?? 'noreply@nutricheck.com.br';
        $this->from_name = $config['from_name'] ?? 'NutriCheck';
    }
    
    /**
     * Enviar email simples sem anexo usando SEMPRE mail()
     * 
     * @param string $to_email Email do destinatário
     * @param string $subject Assunto do email
     * @param string $body_html Corpo do email em HTML
     * @return bool Sucesso do envio
     */
    public function sendEmail($to_email, $subject, $body_html) {
        // Validar email
        if (!filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
            error_log("Email inválido: {$to_email}");
            return false;
        }
        
        // Verificar se mail() está disponível
        if (!function_exists('mail')) {
            error_log("Função mail() não está disponível no PHP");
            return false;
        }
        
        try {
            // Preparar cabeçalhos
            $headers = $this->prepareHeaders();
            
            // Codificar assunto para UTF-8
            $subject = $this->encodeSubject($subject);
            
            // Enviar email
            $result = mail($to_email, $subject, $body_html, $headers);
            
            // Log do resultado
            if ($result) {
                error_log("Email enviado com sucesso para: {$to_email}");
            } else {
                error_log("Falha ao enviar email para: {$to_email}");
                $last_error = error_get_last();
                if ($last_error) {
                    error_log("Erro: " . $last_error['message']);
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Exceção ao enviar email: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Preparar cabeçalhos do email
     */
    private function prepareHeaders() {
        $headers = [];
        
        // From
        $headers[] = "From: {$this->from_name} <{$this->from_email}>";
        
        // Reply-To
        $headers[] = "Reply-To: {$this->from_email}";
        
        // MIME Version
        $headers[] = "MIME-Version: 1.0";
        
        // Content-Type
        $headers[] = "Content-Type: text/html; charset=UTF-8";
        
        // X-Mailer
        $headers[] = "X-Mailer: PHP/" . phpversion();
        
        // Juntar headers com \r\n
        return implode("\r\n", $headers);
    }
    
    /**
     * Codificar assunto para UTF-8
     */
    private function encodeSubject($subject) {
        // Verificar se precisa codificar
        if (mb_detect_encoding($subject, 'UTF-8', true) === 'UTF-8') {
            return '=?UTF-8?B?' . base64_encode($subject) . '?=';
        }
        return $subject;
    }
    
    /**
     * Gerar template de email de notificação de cadastro - VERSÃO SIMPLIFICADA
     * 
     * @param array $user_data Dados do usuário
     * @param string $user_type Tipo de usuário (paciente, anestesista, instituição)
     * @return string HTML do email
     */
    public function generateWelcomeEmailTemplate($user_data, $user_type = 'paciente') {
        $titles = [
            'paciente' => 'Bem-vindo ao NutriCheck',
            'anestesista' => 'Cadastro de Nutricionista Confirmado',
            'instituicao' => 'Cadastro de Instituição Confirmado'
        ];
        
        $title = $titles[$user_type] ?? 'Cadastro Confirmado';
        $nome = htmlspecialchars($user_data['nome'] ?? $user_data['name'] ?? 'Usuário');
        $email = htmlspecialchars($user_data['email'] ?? 'N/A');
        $telefone = htmlspecialchars($user_data['telefone'] ?? '');
        
        // Construir conteúdo do email em texto simples
        $body = '';
        $body .= "Olá, {$nome}!\n\n";
        $body .= "Seu cadastro foi realizado com sucesso no sistema NutriCheck.\n\n";
        $body .= "Email cadastrado: {$email}\n";
        
        if (!empty($telefone)) {
            $body .= "Telefone: {$telefone}\n";
        }
        
        // Adicionar links se for paciente
        if ($user_type === 'paciente') {
            $link_acesso = $user_data['link_acesso'] ?? null;
            $token_acesso = $user_data['token_acesso'] ?? null;
            $link_video = $user_data['link_video'] ?? null;
            
            // Construir link do vídeo se não foi passado
            // URL base do site (defina SITE_URL em config para produção, ex: https://seudominio.com.br)
            $base_url = defined('SITE_URL') ? rtrim(SITE_URL, '/') : (defined('APP_URL') ? 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . APP_URL : '');
            if (!$link_video && $token_acesso && $base_url) {
                $link_video = $base_url . "/paciente_video.php?token=" . $token_acesso;
            }
            
            // Adicionar link do vídeo se existir
            if ($link_video) {
                $body .= "\n" . str_repeat("-", 60) . "\n\n";
                $body .= "ASSISTA AO VÍDEO EDUCATIVO\n\n";
                $body .= "Antes de prosseguir, assista ao vídeo com orientações importantes sobre a nutrição pré-operatória.\n\n";
                $body .= "Link do vídeo: {$link_video}\n\n";
                $body .= "Duração: Aproximadamente 5 minutos\n";
                $body .= "Conteúdo: Orientações de nutrição pré-operatória e perguntas interativas\n";
            }
            
            // Adicionar link do termo se existir
            if ($link_acesso) {
                $body .= "\n" . str_repeat("-", 60) . "\n\n";
                $body .= "ACESSE O TERMO DE CONSENTIMENTO\n\n";
                $body .= "Após assistir ao vídeo, acesse o termo de consentimento para autorizar o procedimento.\n\n";
                $body .= "Link do termo: {$link_acesso}\n\n";
                $body .= "IMPORTANTE: Estes links são únicos e pessoais. Não compartilhe com outras pessoas.\n";
            }
            
            // Adicionar jornada se ambos os links existirem
            if ($link_video && $link_acesso) {
                $body .= "\n" . str_repeat("-", 60) . "\n\n";
                $body .= "SUA JORNADA DE PREPARAÇÃO\n\n";
                $body .= "1. Vídeo Educativo: Assista ao vídeo com orientações de nutrição pré-operatória (5 min)\n";
                $body .= "2. Perguntas Interativas: Responda às perguntas durante o vídeo\n";
                $body .= "3. Termo LGPD: Leia e aceite o termo de privacidade de dados\n";
                $body .= "4. Termo de Consentimento: Leia e autorize o preparo nutricional pré-operatório\n";
                $body .= "5. Confirmação: Receba confirmação do seu consentimento\n\n";
                $body .= "Tempo total estimado: 10-15 minutos\n\n";
                $body .= "ATENÇÃO: É importante completar todas as etapas para que seu procedimento possa ser realizado com segurança.\n";
            }
        }
        
        // Informações importantes
        $body .= "\n" . str_repeat("-", 60) . "\n\n";
        $body .= "INFORMAÇÕES IMPORTANTES\n\n";
        $body .= "- Mantenha seus dados de contato atualizados\n";
        $body .= "- Responda todas as perguntas do questionário com atenção\n";
        $body .= "- Em caso de dúvidas, entre em contato com a equipe médica\n";
        $body .= "- Guarde este email para referência futura\n";
        
        // Segurança e privacidade
        $body .= "\n" . str_repeat("-", 60) . "\n\n";
        $body .= "SEGURANÇA E PRIVACIDADE\n\n";
        $body .= "Seus dados são protegidos conforme a Lei Geral de Proteção de Dados (LGPD). ";
        $body .= "Todas as informações fornecidas são confidenciais e utilizadas exclusivamente ";
        $body .= "para fins de nutrição e gestão do seu preparo pré-operatório.\n";
        
        // Rodapé
        $body .= "\n" . str_repeat("=", 60) . "\n";
        $body .= "NutriCheck\n";
        $body .= "Sistema de Nutrição Pré-Operatória\n\n";
        $body .= "Este email foi gerado automaticamente pelo sistema.\n";
        $body .= "Por favor, não responda a este email.\n";
        $body .= "Em caso de dúvidas, entre em contato com a instituição responsável pelo seu atendimento.\n";
        $body .= str_repeat("=", 60) . "\n";
        
        // Criar versão HTML simples mantendo a formatação de texto
        $html = '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . '</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; padding: 20px; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border: 1px solid #ddd;">
        <pre style="font-family: Arial, sans-serif; white-space: pre-wrap; word-wrap: break-word;">' . htmlspecialchars($body) . '</pre>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * Método auxiliar para enviar email de boas-vindas
     * 
     * @param array $user_data Dados do usuário
     * @param string $user_type Tipo de usuário
     * @return bool Sucesso do envio
     */
    public function sendWelcomeEmail($user_data, $user_type = 'paciente') {
        // Gerar HTML
        $html = $this->generateWelcomeEmailTemplate($user_data, $user_type);
        
        // Definir assunto baseado no tipo
        $subjects = [
            'paciente' => 'Bem-vindo ao NutriCheck',
            'anestesista' => 'Cadastro de Nutricionista Confirmado',
            'instituicao' => 'Cadastro de Instituição Confirmado'
        ];
        $subject = $subjects[$user_type] ?? 'Cadastro Confirmado - NutriCheck';
        
        // Enviar
        return $this->sendEmail($user_data['email'], $subject, $html);
    }
    
    /**
     * Testar configuração do mail()
     * 
     * @return array Informações sobre a configuração
     */
    public function testMailConfiguration() {
        $info = [];
        
        // Verificar se mail() existe
        $info['mail_function_exists'] = function_exists('mail');
        
        // Configurações do php.ini
        $info['sendmail_path'] = ini_get('sendmail_path') ?: 'Não configurado';
        $info['smtp'] = ini_get('SMTP') ?: 'Não configurado';
        $info['smtp_port'] = ini_get('smtp_port') ?: 'Não configurado';
        
        // Versão do PHP
        $info['php_version'] = phpversion();
        
        // Sistema operacional
        $info['os'] = PHP_OS;
        
        return $info;
    }
}

?>
