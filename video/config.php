<?php
/**
 * Arquivo de configuração para a plataforma de vídeo interativo
 * Versão Demo
 */

// Configurações do banco de dados (SQLite para demo)
$host = 'localhost';
$dbname = 'demo_database.sqlite';
$username = '';
$password = '';

// Configurações de upload
define('UPLOAD_PATH', __DIR__ . '/uploads/');
define('MAX_UPLOAD_SIZE', 50 * 1024 * 1024); // 50MB

// Configurações de email (demo)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'demo@exemplo.com');
define('SMTP_PASS', 'senha_demo');
define('FROM_EMAIL', 'demo@exemplo.com');
define('FROM_NAME', 'Sistema Demo');

// Configurações gerais
define('SITE_URL', 'http://localhost:8080');
define('DEBUG_MODE', true);

// Função para conectar ao banco SQLite
function getDatabaseConnection() {
    try {
        $pdo = new PDO('sqlite:' . __DIR__ . '/demo_database.sqlite');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        throw new Exception("Erro de conexão: " . $e->getMessage());
    }
}
?>

