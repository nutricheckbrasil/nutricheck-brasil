<?php
/**
 * Página de Teste do Sistema de Email
 * Use esta página para testar se o sistema de email está configurado corretamente
 */

// Verificar se é uma requisição POST para enviar email de teste
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        require_once 'email_system.php';
        
        $emailManager = new ConsentEmailManager();
        
        // Dados de teste
        $sessionData = [
            'id' => 999,
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Test Browser',
            'started_at' => date('Y-m-d H:i:s'),
            'completed_at' => date('Y-m-d H:i:s')
        ];
        
        $videoData = [
            'id' => 1,
            'title' => 'Vídeo de Teste - Sistema de Email',
            'author' => 'Sistema de Teste',
            'duration' => 180
        ];
        
        $consentData = [
            'user_name' => $_POST['test_name'] ?? 'Usuário de Teste',
            'user_email' => $_POST['test_email'] ?? 'teste@exemplo.com',
            'completion_percentage' => 100,
            'total_time_watched' => 180,
            'device_type' => 'desktop'
        ];
        
        $result = $emailManager->sendConsentNotification($sessionData, $videoData, $consentData);
        
        echo json_encode($result);
        exit;
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erro no teste: ' . $e->getMessage(),
            'error' => $e->getMessage()
        ]);
        exit;
    }
}

// Verificar configuração
$configStatus = [];
try {
    require_once 'email_system.php';
    $emailManager = new ConsentEmailManager();
    $configTest = $emailManager->testEmailConfiguration();
    $configStatus = $configTest;
} catch (Exception $e) {
    $configStatus = [
        'success' => false,
        'message' => 'Erro na configuração: ' . $e->getMessage()
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste do Sistema de Email - Vídeo Interativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            padding-top: 50px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .status-good {
            color: #28a745;
        }
        .status-bad {
            color: #dc3545;
        }
        .config-item {
            padding: 10px;
            margin: 5px 0;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        .test-result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            display: none;
        }
        .test-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .test-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="bi bi-envelope-check"></i>
                            Teste do Sistema de Email
                        </h3>
                    </div>
                    <div class="card-body">
                        
                        <!-- Status da Configuração -->
                        <div class="mb-4">
                            <h5><i class="bi bi-gear"></i> Status da Configuração</h5>
                            
                            <?php if ($configStatus['success']): ?>
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle"></i>
                                    Sistema de email configurado corretamente!
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="config-item">
                                            <strong>Servidor SMTP:</strong><br>
                                            <?= htmlspecialchars($configStatus['config']['smtp_host'] ?? 'N/A') ?>:<?= htmlspecialchars($configStatus['config']['smtp_port'] ?? 'N/A') ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="config-item">
                                            <strong>Email de Origem:</strong><br>
                                            <?= htmlspecialchars($configStatus['config']['from_email'] ?? 'N/A') ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="config-item">
                                            <strong>Email de Consentimento:</strong><br>
                                            <?= htmlspecialchars($configStatus['config']['consent_email'] ?? 'N/A') ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="config-item">
                                            <strong>PHPMailer:</strong><br>
                                            <span class="<?= $configStatus['config']['mailer_configured'] ? 'status-good' : 'status-bad' ?>">
                                                <?= $configStatus['config']['mailer_configured'] ? 'Configurado' : 'Não configurado' ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                            <?php else: ?>
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    Erro na configuração: <?= htmlspecialchars($configStatus['message']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Formulário de Teste -->
                        <div class="mb-4">
                            <h5><i class="bi bi-send"></i> Enviar Email de Teste</h5>
                            <p class="text-muted">
                                Use este formulário para enviar um email de teste e verificar se o sistema está funcionando corretamente.
                            </p>
                            
                            <form id="testForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="test_name" class="form-label">Nome de Teste</label>
                                            <input type="text" class="form-control" id="test_name" name="test_name" value="João da Silva" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="test_email" class="form-label">Email de Teste</label>
                                            <input type="email" class="form-control" id="test_email" name="test_email" value="joao@exemplo.com" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary" id="sendTestBtn">
                                    <i class="bi bi-send"></i>
                                    Enviar Email de Teste
                                </button>
                                
                                <button type="button" class="btn btn-outline-secondary" onclick="window.location.reload()">
                                    <i class="bi bi-arrow-clockwise"></i>
                                    Recarregar Página
                                </button>
                            </form>
                            
                            <div id="testResult" class="test-result"></div>
                        </div>
                        
                        <!-- Instruções -->
                        <div class="mb-4">
                            <h5><i class="bi bi-info-circle"></i> Instruções de Configuração</h5>
                            <div class="alert alert-info">
                                <h6>Para configurar o sistema de email:</h6>
                                <ol>
                                    <li>Copie o arquivo <code>.env.example</code> para <code>.env</code></li>
                                    <li>Configure suas credenciais SMTP no arquivo <code>.env</code></li>
                                    <li>Para Gmail, use uma senha de app específica</li>
                                    <li>Defina o email que receberá as notificações de consentimento</li>
                                    <li>Teste usando o formulário acima</li>
                                </ol>
                                
                                <h6 class="mt-3">Variáveis importantes:</h6>
                                <ul>
                                    <li><code>SMTP_HOST</code>: Servidor SMTP (ex: smtp.gmail.com)</li>
                                    <li><code>SMTP_USER</code>: Seu email</li>
                                    <li><code>SMTP_PASS</code>: Senha de app</li>
                                    <li><code>CONSENT_EMAIL</code>: Email que receberá as notificações</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Links Úteis -->
                        <div class="text-center">
                            <a href="index.html" class="btn btn-outline-primary me-2">
                                <i class="bi bi-house"></i> Voltar à Plataforma
                            </a>
                            <a href="test_embed.html" class="btn btn-outline-success">
                                <i class="bi bi-play-circle"></i> Testar Vídeo Embarcado
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('testForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('sendTestBtn');
            const result = document.getElementById('testResult');
            
            // Mostrar loading
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Enviando...';
            btn.disabled = true;
            result.style.display = 'none';
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch('test_email.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    result.className = 'test-result test-success';
                    result.innerHTML = `
                        <h6><i class="bi bi-check-circle"></i> Email enviado com sucesso!</h6>
                        <p><strong>Destinatário:</strong> ${data.recipient || 'N/A'}</p>
                        <p><strong>Timestamp:</strong> ${data.timestamp || 'N/A'}</p>
                        <p><strong>IP capturado:</strong> ${data.data?.ip_address || 'N/A'}</p>
                        <p><strong>Localização:</strong> ${data.data?.location?.country || 'N/A'}</p>
                        <small class="text-muted">Verifique sua caixa de entrada no email configurado.</small>
                    `;
                } else {
                    result.className = 'test-result test-error';
                    result.innerHTML = `
                        <h6><i class="bi bi-exclamation-triangle"></i> Erro no envio</h6>
                        <p><strong>Mensagem:</strong> ${data.message}</p>
                        ${data.error ? `<p><strong>Detalhes:</strong> ${data.error}</p>` : ''}
                        <small>Verifique suas configurações de email.</small>
                    `;
                }
                
            } catch (error) {
                result.className = 'test-result test-error';
                result.innerHTML = `
                    <h6><i class="bi bi-exclamation-triangle"></i> Erro de conexão</h6>
                    <p>Não foi possível conectar ao servidor: ${error.message}</p>
                `;
            }
            
            // Restaurar botão
            btn.innerHTML = '<i class="bi bi-send"></i> Enviar Email de Teste';
            btn.disabled = false;
            result.style.display = 'block';
        });
    </script>
</body>
</html>

