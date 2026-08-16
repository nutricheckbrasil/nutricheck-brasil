<?php

// Incluir constantes
require_once __DIR__ . '/constants.php';

// Configurações gerais do sistema
define('APP_NAME', 'NutriCheck');
define('APP_VERSION', '1.0.0');
if (!defined('APP_URL')) {
    define('APP_URL', '/nutricheck/public');
}
define('APP_TIMEZONE', 'America/Sao_Paulo');

// Configurações de sessão
//ini_set('session.cookie_httponly', 1);
//ini_set('session.cookie_secure', 0); // Mude para 1 em produção com HTTPS
//ini_set('session.use_strict_mode', 1);
//ini_set('session.cookie_samesite', 'Lax');

// Configurações de segurança
define('PASSWORD_MIN_LENGTH', 8);

// Incluir configurações de email
require_once __DIR__ . '/email_config.php';

// Configurações de API (para integrações futuras)
define('API_KEY', '');
define('API_SECRET', '');

// Configurações de desenvolvimento
define('DEBUG_MODE', true);
define('LOG_ERRORS', true);
define('LOG_PATH', BASE_PATH . '/logs/');

// Definir UPLOAD_PATH após PUBLIC_PATH estar disponível
if (defined('PUBLIC_PATH')) {
    define('UPLOAD_PATH', PUBLIC_PATH . '/uploads/');
}

// Funções utilitárias
function isProduction() {
    return !DEBUG_MODE;
}

function logError($message, $context = []) {
    if (LOG_ERRORS) {
        $log_file = LOG_PATH . 'error_' . date('Y-m-d') . '.log';
        $log_entry = date('Y-m-d H:i:s') . ' - ' . $message . ' - ' . json_encode($context) . PHP_EOL;
        file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
    }
}

function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateCPF($cpf) {
    // Remove caracteres não numéricos
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
    // Verifica se tem 11 dígitos
    if (strlen($cpf) != 11) {
        return false;
    }
    
    // Verifica se todos os dígitos são iguais
    if (preg_match('/^(\d)\1+$/', $cpf)) {
        return false;
    }
    
    // Calcula os dígitos verificadores
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    
    return true;
}

function validateCNPJ($cnpj) {
    // Remove caracteres não numéricos
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
    
    // Verifica se tem 14 dígitos
    if (strlen($cnpj) != 14) {
        return false;
    }
    
    // Verifica se todos os dígitos são iguais
    if (preg_match('/^(\d)\1+$/', $cnpj)) {
        return false;
    }
    
    // Calcula os dígitos verificadores
    for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
        $soma += $cnpj[$i] * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }
    
    $resto = $soma % 11;
    if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
        return false;
    }
    
    for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
        $soma += $cnpj[$i] * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }
    
    $resto = $soma % 11;
    return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
}

function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

function formatCurrency($value) {
    return 'R$ ' . number_format($value, 2, ',', '.');
}

function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

function formatDateTime($datetime, $format = 'd/m/Y H:i') {
    return date($format, strtotime($datetime));
}

function getStatusLabel($status) {
    $labels = [
        STATUS_CADASTRADO => 'Cadastrado',
        STATUS_TERMO_ACEITO => 'Termo Aceito',
        STATUS_SELFIE_TIRADA => 'Selfie Capturada',
        STATUS_VIDEO_ASSISTIDO => 'Vídeo Assistido',
        STATUS_QUESTIONARIO_RESPONDIDO => 'Questionário Respondido',
        STATUS_AUTORIZADO => 'Autorizado',
        STATUS_FINALIZADO => 'Finalizado'
    ];
    
    return $labels[$status] ?? ucfirst(str_replace('_', ' ', $status));
}

function getStatusColor($status) {
    $colors = [
        STATUS_CADASTRADO => 'primary',
        STATUS_TERMO_ACEITO => 'warning',
        STATUS_SELFIE_TIRADA => 'info',
        STATUS_VIDEO_ASSISTIDO => 'info',
        STATUS_QUESTIONARIO_RESPONDIDO => 'warning',
        STATUS_AUTORIZADO => 'success',
        STATUS_FINALIZADO => 'secondary'
    ];
    
    return $colors[$status] ?? 'secondary';
}

// Configurar timezone
date_default_timezone_set(APP_TIMEZONE);

// Configurar locale
setlocale(LC_ALL, 'pt_BR.utf-8', 'pt_BR', 'Portuguese_Brazil'); 