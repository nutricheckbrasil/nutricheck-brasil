<?php
/**
 * Script de teste de envio de email - NutriCheck
 * Acesse: https://dev.anestesiocheck.com.br/test_email_simples.php
 */

// Configurações básicas
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

// Incluir configurações
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/classes/EmailSender.php';

// Processar formulário
$resultado = '';
$detalhes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_destino = $_POST['email'] ?? '';
    
    if (empty($email_destino) || !filter_var($email_destino, FILTER_VALIDATE_EMAIL)) {
        $resultado = 'erro';
        $detalhes = 'Email inválido';
    } else {
        try {
            $emailSender = new EmailSender();
            
            // Preparar dados de teste
            $user_data = [
                'nome' => 'Paciente Teste',
                'email' => $email_destino,
                'telefone' => '(11) 99999-9999',
                'link_acesso' => 'https://dev.anestesiocheck.com.br/paciente/acesso/abc123def456'
            ];
            
            // Gerar corpo do email
            $email_body = $emailSender->generateWelcomeEmailTemplate($user_data, 'paciente');
            
            // Assunto do email
            $subject = "Teste - Bem-vindo ao NutriCheck";
            
            // Enviar email
            $sucesso = $emailSender->sendEmail($email_destino, $subject, $email_body);
            
            if ($sucesso) {
                $resultado = 'sucesso';
                $detalhes = 'Email enviado com sucesso para ' . htmlspecialchars($email_destino);
            } else {
                $resultado = 'erro';
                $detalhes = 'Falha ao enviar email. Verifique os logs do servidor.';
            }
            
        } catch (Exception $e) {
            $resultado = 'erro';
            $detalhes = 'Erro: ' . htmlspecialchars($e->getMessage());
        }
    }
}

// Verificar configurações
$config_info = [
    'SMTP_HOST' => defined('SMTP_HOST') ? SMTP_HOST : 'NÃO DEFINIDO',
    'SMTP_PORT' => defined('SMTP_PORT') ? SMTP_PORT : 'NÃO DEFINIDO',
    'SMTP_USERNAME' => defined('SMTP_USERNAME') ? SMTP_USERNAME : 'NÃO DEFINIDO',
    'SMTP_PASSWORD' => defined('SMTP_PASSWORD') ? (SMTP_PASSWORD ? '***DEFINIDA***' : 'VAZIA') : 'NÃO DEFINIDO',
    'SMTP_ENCRYPTION' => defined('SMTP_ENCRYPTION') ? SMTP_ENCRYPTION : 'NÃO DEFINIDO',
    'PHPMailer' => class_exists('PHPMailer\PHPMailer\PHPMailer') ? '✅ Instalado' : '❌ Não instalado (usando mail() nativo)'
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Email - NutriCheck</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .card { border: none; border-radius: 15px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); }
        .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0 !important; }
        .config-table { font-size: 14px; }
        .config-table td:first-child { font-weight: bold; width: 200px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">📧 Teste de Envio de Email</h3>
                        <small>NutriCheck - Sistema de Gestão de Nutrição Pré-Operatória</small>
                    </div>
                    <div class="card-body">
                        <?php if ($resultado === 'sucesso'): ?>
                            <div class="alert alert-success">
                                <h5>✅ Sucesso!</h5>
                                <p class="mb-0"><?php echo $detalhes; ?></p>
                            </div>
                        <?php elseif ($resultado === 'erro'): ?>
                            <div class="alert alert-danger">
                                <h5>❌ Erro!</h5>
                                <p class="mb-0"><?php echo $detalhes; ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Email de Destino:</label>
                                <input type="email" class="form-control" name="email" required placeholder="seu-email@exemplo.com">
                                <small class="form-text text-muted">Digite o email onde deseja receber o teste</small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Enviar Email de Teste
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">⚙️ Configurações SMTP Atuais</h4>
                    </div>
                    <div class="card-body">
                        <table class="table config-table">
                            <?php foreach ($config_info as $key => $value): ?>
                                <tr>
                                    <td><?php echo $key; ?></td>
                                    <td><?php echo htmlspecialchars($value); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        
                        <div class="alert alert-info mt-3">
                            <strong>ℹ️ Informação:</strong><br>
                            <?php if (class_exists('PHPMailer\PHPMailer\PHPMailer')): ?>
                                O sistema está usando <strong>PHPMailer</strong> para envio de emails via SMTP.
                            <?php else: ?>
                                O sistema está usando a função <strong>mail()</strong> nativa do PHP.<br>
                                <small>Para melhor confiabilidade, recomenda-se instalar o PHPMailer.</small>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!class_exists('PHPMailer\PHPMailer\PHPMailer')): ?>
                            <div class="alert alert-warning">
                                <strong>⚠️ Atenção:</strong><br>
                                PHPMailer não está instalado. O sistema tentará usar a função mail() nativa do PHP,
                                mas isso pode não funcionar em todos os servidores. Para instalar o PHPMailer, execute:<br>
                                <code>composer require phpmailer/phpmailer</code>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <a href="cadastro_paciente.php?token=0d1f995146a440973912bbd87b2d65411ecf7991f0382cc88cef203fa8966e55" class="btn btn-secondary">
                        ← Voltar para Cadastro
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

