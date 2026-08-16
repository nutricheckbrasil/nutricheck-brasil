<?php
/**
 * PÁGINA DE VÍDEO INTERATIVO PARA PACIENTES
 * Integração com sistema de vídeo embed.php existente
 * 
 * URL: https://dev.anestesiocheck.com.br/paciente/video/{token}
 */

// Configurações básicas
define('BASE_PATH', dirname(dirname(dirname(__FILE__))));
define('APP_PATH', BASE_PATH . '/app');

// Incluir configurações
require_once APP_PATH . '/config/constants.php';
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/database.php';

// Pegar token da URL
$token = null;

// Tentar pegar do query string primeiro
if (isset($_GET['token'])) {
    $token = $_GET['token'];
}

// Se não tem no query string, tentar pegar da URL
if (!$token && isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
    $uri = strtok($uri, '?');
    $parts = explode('/', trim($uri, '/'));
    $video_index = array_search('video', $parts);
    if ($video_index !== false && isset($parts[$video_index + 1])) {
        $token = $parts[$video_index + 1];
    }
}

if (!$token) {
    die('
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acesso Negado</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container py-5">
            <div class="alert alert-danger">
                <h4><i class="bi bi-exclamation-triangle"></i> Token de acesso não informado</h4>
                <p>Por favor, use o link enviado por email.</p>
            </div>
        </div>
    </body>
    </html>
    ');
}

try {
    $db = Database::getInstance();
    
    // Buscar paciente pelo token
    $sql = "SELECT p.*, i.nome as instituicao_nome
            FROM pacientes p
            JOIN instituicoes i ON p.instituicao_id = i.id
            WHERE p.token_acesso = ? AND i.status = 'ativo'";
    
    $paciente = $db->fetch($sql, [$token]);
    
    if (!$paciente) {
        die('
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Token Inválido</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="bg-light">
            <div class="container py-5">
                <div class="alert alert-warning">
                    <h4><i class="bi bi-exclamation-triangle"></i> Token inválido ou expirado</h4>
                    <p>Por favor, entre em contato com a instituição.</p>
                </div>
            </div>
        </body>
        </html>
        ');
    }
    
    // ID do vídeo padrão (você pode configurar isso)
    $video_id = 5; // ID do vídeo no sistema de embed
    
    // Construir URL do embed
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    
    // URL do embed com parâmetros
    $embed_url = $protocol . "://" . $host . "/video/embed.php";
    $embed_params = http_build_query([
        'video_id' => $video_id,
        'theme' => 'default',
        'width' => '100%',
        'height' => '500px',
        'paciente_id' => $paciente['id'],
        'paciente_token' => $token,
        'paciente_nome' => $paciente['nome'],
        'paciente_email' => $paciente['email']
    ]);
    
    $embed_full_url = $embed_url . '?' . $embed_params;
    
    // Link para consentimento
    $link_consentimento = $protocol . "://" . $host . "/paciente_acesso.php?token=" . $token;
    
} catch (Exception $e) {
    die('Erro ao processar solicitação: ' . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vídeo Educativo - NutriCheck</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 20px 10px;
        }
        
        .container {
            max-width: 1200px;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 20px;
            border-radius: 20px 20px 0 0 !important;
        }
        
        .video-wrapper {
            position: relative;
            width: 100%;
            background: #000;
            border-radius: 10px;
            overflow: hidden;
            min-height: 500px;
        }
        
        .video-wrapper iframe {
            width: 100%;
            height: 600px;
            border: none;
            display: block;
        }
        
        .info-box {
            background: rgba(102, 126, 234, 0.1);
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .btn-next {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            padding: 15px 30px;
            font-size: 1.1rem;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .btn-next:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            color: white;
        }
        
        .instructions {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
        }
        
        .completion-message {
            display: none;
            text-align: center;
            padding: 30px;
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 15px;
            margin-top: 20px;
        }
        
        .completion-message.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cabeçalho -->
        <div class="card">
            <div class="card-header">
                <h3><i class="bi bi-play-circle"></i> Vídeo Educativo sobre Nutrição Pré-Operatória</h3>
                <p class="mb-0">Olá, <strong><?= htmlspecialchars($paciente['nome']) ?></strong>! Assista ao vídeo e responda às perguntas.</p>
            </div>
        </div>
        
        <!-- Instruções -->
        <div class="card">
            <div class="card-body">
                <div class="instructions">
                    <h5><i class="bi bi-info-circle"></i> Instruções Importantes</h5>
                    <ul class="mb-0">
                        <li><strong>Assista ao vídeo completo</strong> - Contém informações importantes sobre seu procedimento</li>
                        <li><strong>Responda às perguntas</strong> - Elas aparecerão durante o vídeo automaticamente</li>
                        <li><strong>Não feche esta página</strong> - Suas respostas serão salvas automaticamente</li>
                        <li><strong>Após concluir</strong> - Você poderá prosseguir para o termo de consentimento</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Player de Vídeo (Embed) -->
        <div class="card">
            <div class="card-body p-0">
                <div class="video-wrapper">
                    <iframe 
                        id="videoEmbed"
                        src="<?= htmlspecialchars($embed_full_url) ?>" 
                        allowfullscreen
                        allow="autoplay; fullscreen">
                    </iframe>
                </div>
            </div>
        </div>
        
        <!-- Informações -->
        <div class="card">
            <div class="card-body">
                <div class="info-box">
                    <h5><i class="bi bi-clipboard-check"></i> Sobre este Vídeo</h5>
                    <p class="mb-2">Este vídeo contém orientações importantes sobre:</p>
                    <ul class="mb-0">
                        <li>Preparação para o procedimento anestésico</li>
                        <li>Cuidados pré-operatórios</li>
                        <li>Jejum e medicações</li>
                        <li>O que esperar durante e após o procedimento (nutrição pré-operatória)</li>
                    </ul>
                </div>
                
                <div class="info-box">
                    <h5><i class="bi bi-shield-check"></i> Privacidade e Segurança</h5>
                    <p class="mb-0">
                        <i class="bi bi-lock"></i> Suas respostas são armazenadas de forma segura e confidencial.<br>
                        <i class="bi bi-eye-slash"></i> Apenas a equipe médica autorizada terá acesso às suas informações.<br>
                        <i class="bi bi-check-circle"></i> Estamos em conformidade com a LGPD (Lei Geral de Proteção de Dados).
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Mensagem de Conclusão (oculta inicialmente) -->
        <div id="completionMessage" class="completion-message">
            <div class="mb-3">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
            </div>
            <h3 class="text-success">Parabéns!</h3>
            <p class="lead">Você concluiu o vídeo educativo com sucesso!</p>
            <p>Agora você pode prosseguir para o termo de consentimento.</p>
            <a href="<?= htmlspecialchars($link_consentimento) ?>" class="btn btn-next btn-lg mt-3">
                <i class="bi bi-arrow-right-circle"></i> Ir para Termo de Consentimento
            </a>
        </div>
        
        <!-- Botão para Prosseguir (sempre visível) -->
        <div class="card">
            <div class="card-body text-center">
                <p class="mb-3">Após assistir ao vídeo e responder às perguntas:</p>
                <a href="<?= htmlspecialchars($link_consentimento) ?>" class="btn btn-next btn-lg">
                    <i class="bi bi-arrow-right-circle"></i> Continuar para Termo de Consentimento
                </a>
                <p class="text-muted mt-3 small">
                    <i class="bi bi-info-circle"></i> Você pode prosseguir a qualquer momento, mas recomendamos assistir ao vídeo completo.
                </p>
            </div>
        </div>
    </div>
    
    <script>
        // Escutar mensagens do iframe (quando vídeo for concluído)
        window.addEventListener('message', function(event) {
            // Verificar origem (segurança)
            if (event.origin !== window.location.origin) {
                return;
            }
            
            // Se receber mensagem de conclusão
            if (event.data && event.data.type === 'video_completed') {
                console.log('Vídeo concluído!', event.data);
                
                // Mostrar mensagem de conclusão
                document.getElementById('completionMessage').classList.add('show');
                
                // Scroll suave até a mensagem
                document.getElementById('completionMessage').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
            }
            
            // Se receber mensagem de resposta
            if (event.data && event.data.type === 'question_answered') {
                console.log('Pergunta respondida:', event.data);
                // Aqui você pode adicionar feedback visual se quiser
            }
        });
        
        // Log para debug
        console.log('Paciente ID:', <?= $paciente['id'] ?>);
        console.log('Token:', '<?= $token ?>');
        console.log('Embed URL:', '<?= $embed_full_url ?>');
    </script>
</body>
</html>

