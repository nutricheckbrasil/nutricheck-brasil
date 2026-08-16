<?php
/**
 * SCRIPT DE DIAGNÓSTICO - ENVIO DE EMAIL
 * 
 * Este script testa o envio de email e identifica problemas
 * Coloque este arquivo na raiz do projeto nutricheck
 */

// Configurações básicas
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

echo "<h1>Diagnóstico de Envio de Email - NutriCheck</h1>";
echo "<hr>";

// Teste 1: Verificar se os arquivos necessários existem
echo "<h2>Teste 1: Verificação de Arquivos</h2>";

$files_to_check = [
    APP_PATH . '/config/constants.php',
    APP_PATH . '/config/config.php',
    APP_PATH . '/config/database.php',
    APP_PATH . '/classes/EmailSender.php'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "✅ <strong>OK:</strong> $file<br>";
    } else {
        echo "❌ <strong>ERRO:</strong> $file não encontrado<br>";
    }
}

echo "<hr>";

// Teste 2: Incluir arquivos necessários
echo "<h2>Teste 2: Carregamento de Classes</h2>";

try {
    require_once APP_PATH . '/config/constants.php';
    echo "✅ <strong>OK:</strong> constants.php carregado<br>";
} catch (Exception $e) {
    echo "❌ <strong>ERRO:</strong> " . $e->getMessage() . "<br>";
}

try {
    require_once APP_PATH . '/config/config.php';
    echo "✅ <strong>OK:</strong> config.php carregado<br>";
} catch (Exception $e) {
    echo "❌ <strong>ERRO:</strong> " . $e->getMessage() . "<br>";
}

try {
    require_once APP_PATH . '/config/database.php';
    echo "✅ <strong>OK:</strong> database.php carregado<br>";
} catch (Exception $e) {
    echo "❌ <strong>ERRO:</strong> " . $e->getMessage() . "<br>";
}

try {
    require_once APP_PATH . '/classes/EmailSender.php';
    echo "✅ <strong>OK:</strong> EmailSender.php carregado<br>";
} catch (Exception $e) {
    echo "❌ <strong>ERRO:</strong> " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Teste 3: Verificar configurações SMTP
echo "<h2>Teste 3: Configurações SMTP</h2>";

$smtp_configs = [
    'SMTP_HOST' => defined('SMTP_HOST') ? SMTP_HOST : 'NÃO DEFINIDO',
    'SMTP_PORT' => defined('SMTP_PORT') ? SMTP_PORT : 'NÃO DEFINIDO',
    'SMTP_USERNAME' => defined('SMTP_USERNAME') ? SMTP_USERNAME : 'NÃO DEFINIDO',
    'SMTP_PASSWORD' => defined('SMTP_PASSWORD') ? (SMTP_PASSWORD ? '****** (definido)' : 'VAZIO') : 'NÃO DEFINIDO'
];

foreach ($smtp_configs as $key => $value) {
    if ($value === 'NÃO DEFINIDO' || $value === 'VAZIO') {
        echo "❌ <strong>$key:</strong> $value<br>";
    } else {
        echo "✅ <strong>$key:</strong> $value<br>";
    }
}

echo "<hr>";

// Teste 4: Verificar se PHPMailer está disponível
echo "<h2>Teste 4: PHPMailer</h2>";

if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "✅ <strong>OK:</strong> PHPMailer está disponível<br>";
} else {
    echo "⚠️ <strong>AVISO:</strong> PHPMailer não encontrado. Sistema usará função mail() nativa.<br>";
}

echo "<hr>";

// Teste 5: Tentar instanciar EmailSender
echo "<h2>Teste 5: Instanciar EmailSender</h2>";

try {
    $emailSender = new EmailSender();
    echo "✅ <strong>OK:</strong> EmailSender instanciado com sucesso<br>";
} catch (Exception $e) {
    echo "❌ <strong>ERRO:</strong> " . $e->getMessage() . "<br>";
    die();
}

echo "<hr>";

// Teste 6: Gerar template de email
echo "<h2>Teste 6: Gerar Template de Email</h2>";

try {
    $user_data = [
        'nome' => 'Teste Diagnóstico',
        'email' => 'teste@example.com',
        'telefone' => '11999999999',
        'link_acesso' => 'https://anestesiocheck.com.br/paciente/acesso/teste123'
    ];
    
    $email_body = $emailSender->generateWelcomeEmailTemplate($user_data, 'paciente');
    
    if (!empty($email_body)) {
        echo "✅ <strong>OK:</strong> Template gerado com sucesso<br>";
        echo "<details><summary>Ver HTML do email (clique para expandir)</summary>";
        echo "<pre>" . htmlspecialchars(substr($email_body, 0, 500)) . "...</pre>";
        echo "</details>";
    } else {
        echo "❌ <strong>ERRO:</strong> Template vazio<br>";
    }
} catch (Exception $e) {
    echo "❌ <strong>ERRO:</strong> " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Teste 7: Teste de envio de email (OPCIONAL - descomente para testar)
echo "<h2>Teste 7: Envio de Email (TESTE REAL)</h2>";
echo "<p><strong>⚠️ ATENÇÃO:</strong> Para testar o envio real, descomente o código abaixo e substitua o email de destino.</p>";


// DESCOMENTE ESTE BLOCO PARA TESTAR ENVIO REAL
$email_destino = 'joaob042@gmail.com'; // SUBSTITUA PELO SEU EMAIL

try {
    $subject = "Teste de Diagnóstico - NutriCheck";
    $result = $emailSender->sendEmail($email_destino, $subject, $email_body);
    
    if ($result) {
        echo "✅ <strong>SUCESSO:</strong> Email enviado para $email_destino<br>";
        echo "<p>Verifique sua caixa de entrada (e spam).</p>";
    } else {
        echo "❌ <strong>ERRO:</strong> Falha ao enviar email<br>";
    }
} catch (Exception $e) {
    echo "❌ <strong>ERRO:</strong> " . $e->getMessage() . "<br>";
}


echo "<hr>";

// Teste 8: Verificar logs de erro
echo "<h2>Teste 8: Logs de Erro Recentes</h2>";

$log_files = [
    '/var/log/php/error.log',
    '/var/log/apache2/error.log',
    '/var/log/nginx/error.log',
    ini_get('error_log')
];

echo "<p>Verifique os seguintes arquivos de log para mensagens de erro relacionadas a email:</p>";
foreach ($log_files as $log_file) {
    if (!empty($log_file) && file_exists($log_file)) {
        echo "📄 <strong>$log_file</strong> (existe)<br>";
    } else {
        echo "⚠️ <strong>$log_file</strong> (não encontrado ou não acessível)<br>";
    }
}

echo "<hr>";

// Resumo e Recomendações
echo "<h2>Resumo e Recomendações</h2>";

echo "<ol>";
echo "<li>Verifique se todas as configurações SMTP estão corretas</li>";
echo "<li>Se PHPMailer não estiver disponível, considere instalá-lo: <code>composer require phpmailer/phpmailer</code></li>";
echo "<li>Verifique os logs de erro do servidor para mensagens específicas</li>";
echo "<li>Descomente o Teste 7 acima para fazer um teste real de envio</li>";
echo "<li>Verifique se o servidor tem permissão para enviar emails (firewall, porta 587/465)</li>";
echo "</ol>";

echo "<hr>";
echo "<p><em>Diagnóstico concluído em " . date('Y-m-d H:i:s') . "</em></p>";
?>

