<?php
/**
 * Configurações de Email - NutriCheck
 * 
 * Este arquivo contém as configurações de SMTP para envio de emails
 */

// Configurações de SMTP
define('SMTP_HOST', 'smtp.hostinger.com');
define('SMTP_PORT', 465); // Porta 465 para SSL/TLS
define('SMTP_USERNAME', 'noreply@consenticheck.com.br');
define('SMTP_PASSWORD', 'Texbr2007*/'); // Senha do sistema ConsetiCheck
define('SMTP_ENCRYPTION', 'ssl'); // SSL para porta 465
define('SMTP_FROM_EMAIL', 'noreply@consenticheck.com.br');
define('SMTP_FROM_NAME', 'NutriCheck');

// Configurações de email de notificação
define('ADMIN_EMAIL', 'admin@anestesiacheck.com.br');
define('SUPPORT_EMAIL', 'suporte@anestesiacheck.com.br');

// Configurações de templates
define('EMAIL_TEMPLATES_PATH', BASE_PATH . '/app/views/email-templates/');

/**
 * Obter configuração de email como array
 * 
 * @return array Configurações de email
 */
function getEmailConfig() {
    return [
        'smtp_host' => SMTP_HOST,
        'smtp_port' => SMTP_PORT,
        'smtp_username' => SMTP_USERNAME,
        'smtp_password' => SMTP_PASSWORD,
        'smtp_encryption' => SMTP_ENCRYPTION,
        'from_email' => SMTP_FROM_EMAIL,
        'from_name' => SMTP_FROM_NAME
    ];
}

