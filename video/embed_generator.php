<?php
/**
 * Gerador de Código Embed
 * Gera códigos HTML para incorporar vídeos em outros sites
 */

// Configurações
$base_url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$base_url .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);

// Obter parâmetros
$video_id = isset($_GET['video_id']) ? (int)$_GET['video_id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : 'form';

if ($action === 'generate' && $video_id > 0) {
    // Parâmetros de customização
    $width = isset($_GET['width']) ? $_GET['width'] : '800';
    $height = isset($_GET['height']) ? $_GET['height'] : '450';
    $theme = isset($_GET['theme']) ? $_GET['theme'] : 'default';
    $autoplay = isset($_GET['autoplay']) ? 'true' : 'false';
    $controls = isset($_GET['controls']) ? 'true' : 'false';
    
    // Gerar URL do embed
    $embed_url = $base_url . "/embed.php?video_id={$video_id}&theme={$theme}&width={$width}px&height={$height}px";
    
    // Código iframe
    $iframe_code = "<iframe src=\"{$embed_url}\" width=\"{$width}\" height=\"{$height}\" frameborder=\"0\" allowfullscreen></iframe>";
    
    // Código JavaScript avançado
    $js_code = "
<div id=\"video-embed-{$video_id}\"></div>
<script>
(function() {
    var iframe = document.createElement('iframe');
    iframe.src = '{$embed_url}';
    iframe.width = '{$width}';
    iframe.height = '{$height}';
    iframe.frameBorder = '0';
    iframe.allowFullscreen = true;
    iframe.style.border = 'none';
    iframe.style.borderRadius = '8px';
    iframe.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    
    document.getElementById('video-embed-{$video_id}').appendChild(iframe);
})();
</script>";
    
    // Código WordPress shortcode
    $wordpress_code = "[video_interativo id=\"{$video_id}\" width=\"{$width}\" height=\"{$height}\" theme=\"{$theme}\"]";
    
    // Código React/Vue component
    $react_code = "
<VideoInterativo 
    videoId={$video_id}
    width=\"{$width}\"
    height=\"{$height}\"
    theme=\"{$theme}\"
    src=\"{$embed_url}\"
/>";
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'embed_url' => $embed_url,
        'codes' => [
            'iframe' => $iframe_code,
            'javascript' => $js_code,
            'wordpress' => $wordpress_code,
            'react' => $react_code
        ],
        'preview_url' => $embed_url,
        'settings' => [
            'width' => $width,
            'height' => $height,
            'theme' => $theme,
            'autoplay' => $autoplay,
            'controls' => $controls
        ]
    ]);
    exit;
}

// Buscar vídeos disponíveis
try {
    require_once 'config.php';
    $pdo = getDatabaseConnection();
    
    $stmt = $pdo->query("SELECT id, title, author FROM videos ORDER BY created_at DESC");
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $videos = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de Código Embed - Sistema de Vídeo Interativo</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Prism.js para syntax highlighting -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container {
            padding: 40px 15px;
        }
        
        .embed-generator {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 600;
        }
        
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px;
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .preview-container {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            background: #f8f9fa;
            margin: 20px 0;
        }
        
        .preview-placeholder {
            color: #6c757d;
            font-size: 1.1rem;
        }
        
        .code-container {
            background: #2d3748;
            border-radius: 8px;
            margin: 15px 0;
            overflow: hidden;
        }
        
        .code-header {
            background: #4a5568;
            color: white;
            padding: 12px 20px;
            font-weight: 600;
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .code-content {
            position: relative;
        }
        
        .copy-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #007bff;
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
            z-index: 10;
        }
        
        .copy-btn:hover {
            background: #0056b3;
        }
        
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .setting-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        
        .setting-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        
        .btn-generate {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40,167,69,0.3);
        }
        
        .alert-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
            border: none;
            color: white;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="embed-generator">
            <!-- Header -->
            <div class="header">
                <h1><i class="bi bi-code-slash"></i> Gerador de Código Embed</h1>
                <p>Incorpore seus vídeos interativos em qualquer site</p>
            </div>
            
            <!-- Content -->
            <div class="content">
                <!-- Seleção de Vídeo -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="bi bi-play-circle text-primary"></i>
                        Selecionar Vídeo
                    </h2>
                    
                    <select id="videoSelect" class="form-select form-select-lg">
                        <option value="">Escolha um vídeo...</option>
                        <?php foreach ($videos as $video): ?>
                            <option value="<?= $video['id'] ?>">
                                <?= htmlspecialchars($video['title']) ?> - <?= htmlspecialchars($video['author']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Configurações -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="bi bi-gear text-success"></i>
                        Configurações de Embed
                    </h2>
                    
                    <div class="settings-grid">
                        <div class="setting-card">
                            <div class="setting-label">Largura</div>
                            <div class="input-group">
                                <input type="number" id="widthInput" class="form-control" value="800" min="300" max="1920">
                                <span class="input-group-text">px</span>
                            </div>
                        </div>
                        
                        <div class="setting-card">
                            <div class="setting-label">Altura</div>
                            <div class="input-group">
                                <input type="number" id="heightInput" class="form-control" value="450" min="200" max="1080">
                                <span class="input-group-text">px</span>
                            </div>
                        </div>
                        
                        <div class="setting-card">
                            <div class="setting-label">Tema</div>
                            <select id="themeSelect" class="form-select">
                                <option value="default">Claro</option>
                                <option value="dark">Escuro</option>
                            </select>
                        </div>
                        
                        <div class="setting-card">
                            <div class="setting-label">Opções</div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="autoplayCheck">
                                <label class="form-check-label" for="autoplayCheck">
                                    Reprodução automática
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="controlsCheck" checked>
                                <label class="form-check-label" for="controlsCheck">
                                    Mostrar controles
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button id="generateBtn" class="btn-generate" onclick="generateEmbed()">
                            <i class="bi bi-magic"></i> Gerar Código Embed
                        </button>
                    </div>
                </div>
                
                <!-- Preview -->
                <div class="form-section" id="previewSection" style="display: none;">
                    <h2 class="section-title">
                        <i class="bi bi-eye text-info"></i>
                        Preview
                    </h2>
                    
                    <div class="preview-container" id="previewContainer">
                        <div class="preview-placeholder">
                            <i class="bi bi-play-circle" style="font-size: 3rem;"></i>
                            <p>Preview do vídeo aparecerá aqui</p>
                        </div>
                    </div>
                </div>
                
                <!-- Códigos Gerados -->
                <div class="form-section" id="codesSection" style="display: none;">
                    <h2 class="section-title">
                        <i class="bi bi-code text-warning"></i>
                        Códigos para Incorporação
                    </h2>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Como usar:</strong> Copie o código desejado e cole no HTML do seu site onde deseja que o vídeo apareça.
                    </div>
                    
                    <!-- Código iframe -->
                    <div class="code-container">
                        <div class="code-header">
                            <span><i class="bi bi-window"></i> Código iframe (Universal)</span>
                        </div>
                        <div class="code-content">
                            <button class="copy-btn" onclick="copyCode('iframeCode')">Copiar</button>
                            <pre><code id="iframeCode" class="language-html"></code></pre>
                        </div>
                    </div>
                    
                    <!-- Código JavaScript -->
                    <div class="code-container">
                        <div class="code-header">
                            <span><i class="bi bi-filetype-js"></i> Código JavaScript (Avançado)</span>
                        </div>
                        <div class="code-content">
                            <button class="copy-btn" onclick="copyCode('jsCode')">Copiar</button>
                            <pre><code id="jsCode" class="language-html"></code></pre>
                        </div>
                    </div>
                    
                    <!-- Código WordPress -->
                    <div class="code-container">
                        <div class="code-header">
                            <span><i class="bi bi-wordpress"></i> Shortcode WordPress</span>
                        </div>
                        <div class="code-content">
                            <button class="copy-btn" onclick="copyCode('wpCode')">Copiar</button>
                            <pre><code id="wpCode" class="language-html"></code></pre>
                        </div>
                    </div>
                    
                    <!-- Código React -->
                    <div class="code-container">
                        <div class="code-header">
                            <span><i class="bi bi-filetype-jsx"></i> Componente React/Vue</span>
                        </div>
                        <div class="code-content">
                            <button class="copy-btn" onclick="copyCode('reactCode')">Copiar</button>
                            <pre><code id="reactCode" class="language-jsx"></code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
    
    <script>
        // Gerar código embed
        async function generateEmbed() {
            const videoId = document.getElementById('videoSelect').value;
            const width = document.getElementById('widthInput').value;
            const height = document.getElementById('heightInput').value;
            const theme = document.getElementById('themeSelect').value;
            const autoplay = document.getElementById('autoplayCheck').checked;
            const controls = document.getElementById('controlsCheck').checked;
            
            if (!videoId) {
                alert('Por favor, selecione um vídeo.');
                return;
            }
            
            try {
                const params = new URLSearchParams({
                    action: 'generate',
                    video_id: videoId,
                    width: width,
                    height: height,
                    theme: theme,
                    autoplay: autoplay ? '1' : '0',
                    controls: controls ? '1' : '0'
                });
                
                const response = await fetch(`embed_generator.php?${params}`);
                const data = await response.json();
                
                if (data.success) {
                    // Mostrar preview
                    showPreview(data.preview_url, width, height);
                    
                    // Mostrar códigos
                    showCodes(data.codes);
                    
                    // Mostrar seções
                    document.getElementById('previewSection').style.display = 'block';
                    document.getElementById('codesSection').style.display = 'block';
                    
                    // Scroll para preview
                    document.getElementById('previewSection').scrollIntoView({ 
                        behavior: 'smooth' 
                    });
                }
            } catch (error) {
                console.error('Erro ao gerar embed:', error);
                alert('Erro ao gerar código embed. Tente novamente.');
            }
        }
        
        // Mostrar preview
        function showPreview(url, width, height) {
            const container = document.getElementById('previewContainer');
            container.innerHTML = `
                <iframe src="${url}" 
                        width="${Math.min(width, 600)}" 
                        height="${Math.min(height, 400)}" 
                        frameborder="0" 
                        allowfullscreen
                        style="border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                </iframe>
            `;
        }
        
        // Mostrar códigos
        function showCodes(codes) {
            document.getElementById('iframeCode').textContent = codes.iframe;
            document.getElementById('jsCode').textContent = codes.javascript;
            document.getElementById('wpCode').textContent = codes.wordpress;
            document.getElementById('reactCode').textContent = codes.react;
            
            // Aplicar syntax highlighting
            Prism.highlightAll();
        }
        
        // Copiar código
        function copyCode(elementId) {
            const code = document.getElementById(elementId).textContent;
            navigator.clipboard.writeText(code).then(() => {
                // Feedback visual
                const btn = event.target;
                const originalText = btn.textContent;
                btn.textContent = 'Copiado!';
                btn.style.background = '#28a745';
                
                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.style.background = '#007bff';
                }, 2000);
            });
        }
    </script>
</body>
</html>

