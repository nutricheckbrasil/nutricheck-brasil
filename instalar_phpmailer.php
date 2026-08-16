<?php
/**
 * Script para instalar PHPMailer manualmente
 * Acesse: https://dev.anestesiocheck.com.br/instalar_phpmailer.php
 */

set_time_limit(300); // 5 minutos

$resultado = '';
$detalhes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['instalar'])) {
    try {
        // Criar diretório vendor se não existir
        $vendor_dir = __DIR__ . '/vendor';
        $phpmailer_dir = $vendor_dir . '/phpmailer/phpmailer';
        
        if (!is_dir($vendor_dir)) {
            mkdir($vendor_dir, 0755, true);
        }
        
        if (!is_dir($phpmailer_dir)) {
            mkdir($phpmailer_dir, 0755, true);
        }
        
        // URL do PHPMailer no GitHub
        $zip_url = 'https://github.com/PHPMailer/PHPMailer/archive/refs/heads/master.zip';
        $zip_file = $vendor_dir . '/phpmailer.zip';
        
        // Baixar o arquivo
        $detalhes .= "Baixando PHPMailer...<br>";
        $zip_content = file_get_contents($zip_url);
        
        if ($zip_content === false) {
            throw new Exception("Falha ao baixar PHPMailer do GitHub");
        }
        
        file_put_contents($zip_file, $zip_content);
        $detalhes .= "Download concluído!<br>";
        
        // Extrair o arquivo
        $detalhes .= "Extraindo arquivos...<br>";
        $zip = new ZipArchive;
        
        if ($zip->open($zip_file) === TRUE) {
            $zip->extractTo($vendor_dir);
            $zip->close();
            
            // Mover arquivos para o local correto
            $extracted_dir = $vendor_dir . '/PHPMailer-master';
            
            if (is_dir($extracted_dir)) {
                // Copiar arquivos necessários
                $files_to_copy = [
                    'src/PHPMailer.php',
                    'src/SMTP.php',
                    'src/Exception.php'
                ];
                
                foreach ($files_to_copy as $file) {
                    $source = $extracted_dir . '/' . $file;
                    $dest = $phpmailer_dir . '/' . basename($file);
                    
                    if (file_exists($source)) {
                        copy($source, $dest);
                    }
                }
                
                // Criar autoload simples
                $autoload_content = '<?php
namespace PHPMailer\PHPMailer;

require_once __DIR__ . \'/phpmailer/phpmailer/Exception.php\';
require_once __DIR__ . \'/phpmailer/phpmailer/SMTP.php\';
require_once __DIR__ . \'/phpmailer/phpmailer/PHPMailer.php\';
';
                file_put_contents($vendor_dir . '/autoload.php', $autoload_content);
                
                // Limpar arquivos temporários
                unlink($zip_file);
                
                // Remover diretório extraído
                function deleteDirectory($dir) {
                    if (!is_dir($dir)) return;
                    $files = array_diff(scandir($dir), ['.', '..']);
                    foreach ($files as $file) {
                        $path = $dir . '/' . $file;
                        is_dir($path) ? deleteDirectory($path) : unlink($path);
                    }
                    rmdir($dir);
                }
                deleteDirectory($extracted_dir);
                
                $resultado = 'sucesso';
                $detalhes .= "<strong>✅ PHPMailer instalado com sucesso!</strong><br>";
                $detalhes .= "Arquivos instalados em: " . $phpmailer_dir;
            } else {
                throw new Exception("Diretório extraído não encontrado");
            }
        } else {
            throw new Exception("Falha ao extrair arquivo ZIP");
        }
        
    } catch (Exception $e) {
        $resultado = 'erro';
        $detalhes .= "<strong>❌ Erro:</strong> " . $e->getMessage();
    }
}

// Verificar se PHPMailer já está instalado
$phpmailer_instalado = false;
$vendor_path = __DIR__ . '/vendor/phpmailer/phpmailer';

if (is_dir($vendor_path) && file_exists($vendor_path . '/PHPMailer.php')) {
    $phpmailer_instalado = true;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalar PHPMailer - NutriCheck</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .card { border: none; border-radius: 15px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); }
        .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0 !important; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">📦 Instalação do PHPMailer</h3>
                        <small>Biblioteca para envio de emails via SMTP</small>
                    </div>
                    <div class="card-body">
                        <?php if ($resultado === 'sucesso'): ?>
                            <div class="alert alert-success">
                                <h5>✅ Instalação Concluída!</h5>
                                <p><?php echo $detalhes; ?></p>
                            </div>
                            <div class="alert alert-info">
                                <strong>Próximo passo:</strong><br>
                                Atualize a classe EmailSender para carregar o PHPMailer do vendor.<br>
                                Adicione no início do arquivo <code>EmailSender.php</code>:<br>
                                <code>require_once BASE_PATH . '/vendor/autoload.php';</code>
                            </div>
                            <a href="test_email_simples.php" class="btn btn-primary">Testar Envio de Email</a>
                        <?php elseif ($resultado === 'erro'): ?>
                            <div class="alert alert-danger">
                                <h5>❌ Erro na Instalação</h5>
                                <p><?php echo $detalhes; ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($phpmailer_instalado): ?>
                            <div class="alert alert-success">
                                <h5>✅ PHPMailer já está instalado!</h5>
                                <p>Localização: <code><?php echo $vendor_path; ?></code></p>
                            </div>
                            <a href="test_email_simples.php" class="btn btn-primary">Testar Envio de Email</a>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <h5>⚠️ PHPMailer não encontrado</h5>
                                <p>O PHPMailer não está instalado. Clique no botão abaixo para instalar automaticamente.</p>
                            </div>
                            
                            <form method="POST">
                                <button type="submit" name="instalar" class="btn btn-primary btn-lg">
                                    📥 Instalar PHPMailer Agora
                                </button>
                            </form>
                            
                            <div class="alert alert-info mt-3">
                                <strong>ℹ️ O que este script faz:</strong>
                                <ol>
                                    <li>Baixa o PHPMailer do GitHub</li>
                                    <li>Extrai os arquivos necessários</li>
                                    <li>Cria estrutura vendor/phpmailer/phpmailer</li>
                                    <li>Configura autoload básico</li>
                                </ol>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <a href="test_email_simples.php" class="btn btn-secondary">Ir para Teste de Email</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

