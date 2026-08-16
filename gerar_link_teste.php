<?php
/**
 * GERADOR DE LINK DE CONSENTIMENTO DE TESTE
 * 
 * Este script cria um paciente de teste e gera os links de acesso
 * (vídeo e consentimento) automaticamente.
 * 
 * USO: Acesse via navegador e preencha o formulário
 */

// Configurações
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/database.php';

// Processar formulário
$mensagem = '';
$tipo_mensagem = '';
$links = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = Database::getInstance();
        
        // Dados do formulário
        $instituicao_id = $_POST['instituicao_id'] ?? null;
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $cpf = $_POST['cpf'] ?? '';
        $data_nascimento = $_POST['data_nascimento'] ?? '';
        
        // Validações básicas
        if (!$instituicao_id || !$nome || !$email) {
            throw new Exception('Preencha todos os campos obrigatórios');
        }
        
        // Verificar se instituição existe
        $instituicao = $db->fetch("SELECT * FROM instituicoes WHERE id = ?", [$instituicao_id]);
        if (!$instituicao) {
            throw new Exception('Instituição não encontrada');
        }
        
        // Gerar token único
        $token_acesso = bin2hex(random_bytes(32));
        
        // Inserir paciente
        $sql = "INSERT INTO pacientes 
                (instituicao_id, nome, email, telefone, cpf, data_nascimento, token_acesso, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'cadastrado', NOW())";
        
        $db->query($sql, [
            $instituicao_id,
            $nome,
            $email,
            $telefone,
            $cpf,
            $data_nascimento,
            $token_acesso
        ]);
        
        $paciente_id = $db->lastInsertId();
        
        // Construir URLs
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        
        $link_video = $protocol . "://" . $host . "/paciente/video/" . $token_acesso;
        $link_consentimento = $protocol . "://" . $host . "/paciente/acesso/" . $token_acesso;
        
        // Atualizar link_acesso no banco
        $db->query("UPDATE pacientes SET link_acesso = ? WHERE id = ?", [$link_consentimento, $paciente_id]);
        
        // Sucesso
        $tipo_mensagem = 'success';
        $mensagem = 'Paciente criado com sucesso!';
        $links = [
            'video' => $link_video,
            'consentimento' => $link_consentimento,
            'token' => $token_acesso,
            'paciente_id' => $paciente_id
        ];
        
    } catch (Exception $e) {
        $tipo_mensagem = 'danger';
        $mensagem = 'Erro: ' . $e->getMessage();
    }
}

// Buscar instituições disponíveis
try {
    $db = Database::getInstance();
    $instituicoes = $db->query("SELECT id, nome FROM instituicoes WHERE status = 'ativo' ORDER BY nome");
} catch (Exception $e) {
    $instituicoes = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de Link de Consentimento - NutriCheck</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0 !important;
            padding: 20px;
        }
        .link-box {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
            word-break: break-all;
        }
        .link-box strong {
            color: #667eea;
        }
        .btn-copy {
            margin-top: 10px;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Cabeçalho -->
                <div class="card mb-4">
                    <div class="card-header text-center">
                        <h3><i class="bi bi-link-45deg"></i> Gerador de Link de Consentimento</h3>
                        <p class="mb-0">Crie um paciente de teste e gere os links automaticamente</p>
                    </div>
                </div>
                
                <?php if ($mensagem): ?>
                <div class="alert alert-<?= $tipo_mensagem ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($mensagem) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if ($links): ?>
                <!-- Links Gerados -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="bi bi-check-circle"></i> Links Gerados com Sucesso!</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong>ID do Paciente:</strong> <?= $links['paciente_id'] ?><br>
                            <strong>Token:</strong> <code><?= htmlspecialchars($links['token']) ?></code>
                        </div>
                        
                        <h6 class="mt-4"><i class="bi bi-play-circle"></i> Link do Vídeo Interativo:</h6>
                        <div class="link-box">
                            <strong>URL:</strong><br>
                            <a href="<?= htmlspecialchars($links['video']) ?>" target="_blank" id="linkVideo">
                                <?= htmlspecialchars($links['video']) ?>
                            </a>
                            <button class="btn btn-sm btn-primary btn-copy" onclick="copiarLink('linkVideo')">
                                <i class="bi bi-clipboard"></i> Copiar
                            </button>
                        </div>
                        
                        <h6 class="mt-4"><i class="bi bi-file-text"></i> Link do Termo de Consentimento:</h6>
                        <div class="link-box">
                            <strong>URL:</strong><br>
                            <a href="<?= htmlspecialchars($links['consentimento']) ?>" target="_blank" id="linkConsentimento">
                                <?= htmlspecialchars($links['consentimento']) ?>
                            </a>
                            <button class="btn btn-sm btn-primary btn-copy" onclick="copiarLink('linkConsentimento')">
                                <i class="bi bi-clipboard"></i> Copiar
                            </button>
                        </div>
                        
                        <div class="alert alert-warning mt-4">
                            <strong><i class="bi bi-info-circle"></i> Importante:</strong>
                            <ul class="mb-0">
                                <li>Estes links são únicos e válidos apenas para este paciente</li>
                                <li>O vídeo deve estar configurado em <code>/uploads/videos/</code></li>
                                <li>As tabelas de vídeo devem estar criadas no banco</li>
                            </ul>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="<?= htmlspecialchars($links['video']) ?>" class="btn btn-lg btn-primary" target="_blank">
                                <i class="bi bi-play-fill"></i> Testar Vídeo Agora
                            </a>
                            <a href="<?= htmlspecialchars($links['consentimento']) ?>" class="btn btn-lg btn-success" target="_blank">
                                <i class="bi bi-file-text"></i> Testar Consentimento Agora
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Formulário -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-person-plus"></i> Criar Paciente de Teste</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="instituicao_id" class="form-label">Instituição *</label>
                                <select class="form-select" id="instituicao_id" name="instituicao_id" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($instituicoes as $inst): ?>
                                    <option value="<?= $inst['id'] ?>"><?= htmlspecialchars($inst['nome']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control" id="nome" name="nome" 
                                       placeholder="Ex: João da Silva" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="joao@email.com" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="tel" class="form-control" id="telefone" name="telefone" 
                                           placeholder="(11) 98765-4321">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input type="text" class="form-control" id="cpf" name="cpf" 
                                           placeholder="000.000.000-00">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                                    <input type="date" class="form-control" id="data_nascimento" name="data_nascimento">
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-link-45deg"></i> Gerar Links
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Instruções -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5><i class="bi bi-info-circle"></i> Como Usar</h5>
                    </div>
                    <div class="card-body">
                        <ol>
                            <li>Selecione uma instituição ativa</li>
                            <li>Preencha os dados do paciente de teste</li>
                            <li>Clique em "Gerar Links"</li>
                            <li>Copie os links gerados ou teste diretamente</li>
                            <li>O paciente será criado no banco de dados</li>
                        </ol>
                        
                        <div class="alert alert-warning mt-3">
                            <strong>⚠️ Atenção:</strong> Este é um script de teste. Em produção, os pacientes 
                            devem ser cadastrados através do formulário público com QR Code.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copiarLink(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent || element.innerText;
            
            navigator.clipboard.writeText(text).then(() => {
                alert('Link copiado para a área de transferência!');
            }).catch(err => {
                console.error('Erro ao copiar:', err);
                // Fallback para navegadores antigos
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                alert('Link copiado!');
            });
        }
    </script>
</body>
</html>

