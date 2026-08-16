<?php
/**
 * Sistema de Embed para Vídeos Interativos - ATUALIZADO
 * Integrado com api_video.php do sistema Anestesia Check
 */

// Headers para permitir incorporação em outros sites
header('X-Frame-Options: ALLOWALL');
header('Content-Security-Policy: frame-ancestors *');

// Configurações do banco - usar o sistema principal
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

require_once APP_PATH . '/config/constants.php';
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/database.php';

// Obter parâmetros
$video_id = isset($_GET['video_id']) ? (int)$_GET['video_id'] : 0;
$paciente_id = isset($_GET['paciente_id']) ? (int)$_GET['paciente_id'] : 0;
$paciente_token = isset($_GET['paciente_token']) ? $_GET['paciente_token'] : '';
$paciente_nome = isset($_GET['paciente_nome']) ? $_GET['paciente_nome'] : '';
$paciente_email = isset($_GET['paciente_email']) ? $_GET['paciente_email'] : '';
$theme = isset($_GET['theme']) ? $_GET['theme'] : 'default';
$width = isset($_GET['width']) ? $_GET['width'] : '100%';
$height = isset($_GET['height']) ? $_GET['height'] : '500px';

if (!$video_id) {
    die('ID do vídeo não fornecido');
}

if (!$paciente_id) {
    die('ID do paciente não fornecido');
}

// Buscar dados do vídeo e perguntas
try {
    $db = Database::getInstance();
    
    // Buscar vídeo
    $sql = "SELECT * FROM videos_interativos WHERE id = ? AND ativo = 1";
    $video = $db->fetch($sql, [$video_id]);
    
    if (!$video) {
        die('Vídeo não encontrado ou inativo');
    }
    
    // Buscar perguntas
    $sql = "SELECT id, texto_pergunta, tipo_pergunta, opcoes, tempo_exibicao, obrigatoria, pontuacao, ordem
            FROM video_perguntas
            WHERE video_id = ?
            ORDER BY tempo_exibicao ASC, ordem ASC";
    $perguntas = $db->query($sql, [$video_id]);
    
    // Decodificar JSON das opções
    foreach ($perguntas as &$pergunta) {
        if ($pergunta['opcoes']) {
            $pergunta['opcoes'] = json_decode($pergunta['opcoes'], true);
        }
    }
    
    // Criar sessão de visualização automaticamente
    $sessionToken = bin2hex(random_bytes(32));
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    $deviceType = 'unknown';
    
    if ($userAgent) {
        if (preg_match('/mobile/i', $userAgent)) {
            $deviceType = 'mobile';
        } elseif (preg_match('/tablet/i', $userAgent)) {
            $deviceType = 'tablet';
        } else {
            $deviceType = 'desktop';
        }
    }
    
    $sql = "INSERT INTO video_sessoes 
            (paciente_id, video_id, session_token, ip_address, user_agent, device_type, status)
            VALUES (?, ?, ?, ?, ?, ?, 'iniciada')";
    $db->query($sql, [$paciente_id, $video_id, $sessionToken, $ipAddress, $userAgent, $deviceType]);
    $session_id = $db->lastInsertId();
    
    // Atualizar estatísticas
    $sql = "INSERT INTO video_estatisticas 
            (paciente_id, video_id, total_visualizacoes, data_primeira_visualizacao, data_ultima_visualizacao)
            VALUES (?, ?, 1, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
            total_visualizacoes = total_visualizacoes + 1,
            data_ultima_visualizacao = NOW()";
    $db->query($sql, [$paciente_id, $video_id]);
    
} catch (Exception $e) {
    die('Erro na conexão: ' . htmlspecialchars($e->getMessage()));
}

// URL da API
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$api_url = $protocol . "://" . $host . "/api_video.php";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($video['titulo']) ?> - Vídeo Interativo</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            margin: 0;
            padding: 10px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: <?= $theme === 'dark' ? '#1a1a1a' : '#f8f9fa' ?>;
            color: <?= $theme === 'dark' ? '#ffffff' : '#333333' ?>;
        }
        
        .embed-container {
            max-width: 100%;
            margin: 0 auto;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            background: <?= $theme === 'dark' ? '#2d2d2d' : '#ffffff' ?>;
        }
        
        .video-header {
            padding: 15px;
            border-bottom: 1px solid <?= $theme === 'dark' ? '#444' : '#dee2e6' ?>;
        }
        
        .video-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
            color: <?= $theme === 'dark' ? '#ffffff' : '#333333' ?>;
        }
        
        .video-author {
            font-size: 0.9rem;
            color: <?= $theme === 'dark' ? '#aaaaaa' : '#666666' ?>;
            margin: 5px 0 0 0;
        }
        
        .video-container {
            position: relative;
            width: 100%;
            height: <?= $height ?>;
            background: #000;
        }
        
        video {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .question-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.95);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }
        
        .question-card {
            background: <?= $theme === 'dark' ? '#2d2d2d' : '#ffffff' ?>;
            border-radius: 12px;
            padding: 30px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .question-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid <?= $theme === 'dark' ? '#444' : '#dee2e6' ?>;
        }
        
        .question-icon {
            font-size: 2rem;
            color: #007bff;
            margin-right: 15px;
        }
        
        .question-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0;
            color: <?= $theme === 'dark' ? '#ffffff' : '#333333' ?>;
        }
        
        .question-text {
            font-size: 1.1rem;
            margin-bottom: 25px;
            line-height: 1.6;
            color: <?= $theme === 'dark' ? '#e0e0e0' : '#444444' ?>;
        }
        
        .question-option {
            margin: 12px 0;
            padding: 15px 20px;
            border: 2px solid <?= $theme === 'dark' ? '#444' : '#dee2e6' ?>;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: <?= $theme === 'dark' ? '#1a1a1a' : '#f8f9fa' ?>;
            display: flex;
            align-items: center;
        }
        
        .question-option:hover {
            border-color: #007bff;
            background: <?= $theme === 'dark' ? '#333' : '#e3f2fd' ?>;
            transform: translateX(5px);
        }
        
        .question-option.selected {
            border-color: #007bff;
            background: <?= $theme === 'dark' ? '#1a3a5a' : '#cfe2ff' ?>;
        }
        
        .option-radio {
            width: 20px;
            height: 20px;
            border: 2px solid #007bff;
            border-radius: 50%;
            margin-right: 12px;
            flex-shrink: 0;
        }
        
        .question-option.selected .option-radio {
            background: #007bff;
            box-shadow: inset 0 0 0 4px <?= $theme === 'dark' ? '#1a1a1a' : '#ffffff' ?>;
        }
        
        .text-answer {
            width: 100%;
            padding: 12px;
            border: 2px solid <?= $theme === 'dark' ? '#444' : '#dee2e6' ?>;
            border-radius: 8px;
            font-size: 1rem;
            background: <?= $theme === 'dark' ? '#1a1a1a' : '#ffffff' ?>;
            color: <?= $theme === 'dark' ? '#ffffff' : '#333333' ?>;
            resize: vertical;
            min-height: 100px;
        }
        
        .text-answer:focus {
            outline: none;
            border-color: #007bff;
        }
        
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,123,255,0.4);
        }
        
        .btn-submit:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }
        
        .feedback-message {
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            display: none;
        }
        
        .feedback-message.success {
            background: #d4edda;
            border: 1px solid #28a745;
            color: #155724;
        }
        
        .feedback-message.error {
            background: #f8d7da;
            border: 1px solid #dc3545;
            color: #721c24;
        }
        
        .progress-bar-container {
            padding: 15px;
            background: <?= $theme === 'dark' ? '#1a1a1a' : '#f8f9fa' ?>;
            border-top: 1px solid <?= $theme === 'dark' ? '#444' : '#dee2e6' ?>;
        }
        
        .progress {
            height: 8px;
            border-radius: 4px;
            background: <?= $theme === 'dark' ? '#333' : '#e9ecef' ?>;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
            transition: width 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .question-card {
                padding: 20px;
            }
            
            .question-title {
                font-size: 1.1rem;
            }
            
            .question-text {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="embed-container">
        <!-- Cabeçalho do Vídeo -->
        <div class="video-header">
            <h1 class="video-title"><?= htmlspecialchars($video['titulo']) ?></h1>
            <?php if (!empty($video['autor'])): ?>
                <p class="video-author"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($video['autor']) ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Container do Vídeo -->
        <div class="video-container">
            <video id="mainVideo" controls>
                <source src="<?= htmlspecialchars($video['file_path']) ?>" type="video/mp4">
                Seu navegador não suporta vídeo HTML5.
            </video>
            
            <!-- Overlay de Pergunta -->
            <div id="questionOverlay" class="question-overlay">
                <div class="question-card">
                    <div class="question-header">
                        <div class="question-icon">
                            <i class="bi bi-question-circle-fill"></i>
                        </div>
                        <div>
                            <h2 class="question-title">Pergunta</h2>
                            <small class="text-muted">Responda para continuar</small>
                        </div>
                    </div>
                    
                    <div id="questionContent"></div>
                    
                    <div id="feedbackMessage" class="feedback-message"></div>
                </div>
            </div>
        </div>
        
        <!-- Barra de Progresso -->
        <div class="progress-bar-container">
            <div class="d-flex justify-content-between mb-2">
                <small class="text-muted">Progresso do vídeo</small>
                <small class="text-muted"><span id="progressText">0%</span></small>
            </div>
            <div class="progress">
                <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
        </div>
    </div>
    
    <script>
        // Dados do vídeo e perguntas
        const videoData = <?= json_encode($video, JSON_UNESCAPED_UNICODE) ?>;
        const questionsData = <?= json_encode($perguntas, JSON_UNESCAPED_UNICODE) ?>;
        const sessionId = <?= $session_id ?>;
        const pacienteId = <?= $paciente_id ?>;
        const apiUrl = '<?= $api_url ?>';
        
        // Elementos DOM
        const video = document.getElementById('mainVideo');
        const questionOverlay = document.getElementById('questionOverlay');
        const questionContent = document.getElementById('questionContent');
        const feedbackMessage = document.getElementById('feedbackMessage');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        
        // Estado
        let currentQuestionIndex = 0;
        let answeredQuestions = new Set();
        let currentQuestion = null;
        let questionStartTime = null;
        
        console.log('🎬 Vídeo carregado:', videoData);
        console.log('❓ Perguntas:', questionsData);
        console.log('🔑 Session ID:', sessionId);
        
        // Monitorar progresso do vídeo
        video.addEventListener('timeupdate', function() {
            const currentTime = Math.floor(video.currentTime);
            const duration = video.duration;
            const percentual = (currentTime / duration) * 100;
            
            // Atualizar barra de progresso
            progressBar.style.width = percentual + '%';
            progressText.textContent = Math.round(percentual) + '%';
            
            // Atualizar progresso no servidor a cada 10 segundos
            if (currentTime % 10 === 0) {
                updateProgress(currentTime, percentual);
            }
            
            // Verificar se deve mostrar alguma pergunta
            questionsData.forEach((question, index) => {
                if (currentTime >= question.tempo_exibicao && 
                    !answeredQuestions.has(question.id) &&
                    !questionOverlay.style.display.includes('flex')) {
                    showQuestion(question);
                }
            });
        });
        
        // Quando vídeo termina
        video.addEventListener('ended', function() {
            completeSession();
        });
        
        // Mostrar pergunta
        function showQuestion(question) {
            currentQuestion = question;
            questionStartTime = Date.now();
            
            video.pause();
            questionOverlay.style.display = 'flex';
            feedbackMessage.style.display = 'none';
            
            let html = `<p class="question-text">${question.texto_pergunta}</p>`;
            
            if (question.tipo_pergunta === 'multipla_escolha' || question.tipo_pergunta === 'verdadeiro_falso') {
                html += '<div class="options-container">';
                question.opcoes.forEach((opcao, index) => {
                    html += `
                        <div class="question-option" onclick="selectOption(this, '${opcao}')">
                            <div class="option-radio"></div>
                            <span>${opcao}</span>
                        </div>
                    `;
                });
                html += '</div>';
                html += `<button class="btn-submit" onclick="submitAnswer()" disabled>
                    <i class="bi bi-check-circle"></i> Confirmar Resposta
                </button>`;
            } else if (question.tipo_pergunta === 'texto_livre') {
                html += `
                    <textarea id="textAnswer" class="text-answer" placeholder="Digite sua resposta aqui..."></textarea>
                    <button class="btn-submit" onclick="submitAnswer()">
                        <i class="bi bi-check-circle"></i> Enviar Resposta
                    </button>
                `;
            }
            
            questionContent.innerHTML = html;
        }
        
        // Selecionar opção
        let selectedAnswer = null;
        function selectOption(element, answer) {
            // Remover seleção anterior
            document.querySelectorAll('.question-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Selecionar nova opção
            element.classList.add('selected');
            selectedAnswer = answer;
            
            // Habilitar botão
            document.querySelector('.btn-submit').disabled = false;
        }
        
        // Enviar resposta
        function submitAnswer() {
            let answer = selectedAnswer;
            
            if (currentQuestion.tipo_pergunta === 'texto_livre') {
                answer = document.getElementById('textAnswer').value.trim();
                if (answer.length < 3) {
                    showFeedback('Por favor, escreva uma resposta mais completa.', 'error');
                    return;
                }
            }
            
            if (!answer) {
                showFeedback('Por favor, selecione uma resposta.', 'error');
                return;
            }
            
            const tempoResposta = Math.floor((Date.now() - questionStartTime) / 1000);
            
            // Desabilitar botão enquanto envia
            const btn = document.querySelector('.btn-submit');
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Enviando...';
            
            // Enviar para API
            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'submit_answer',
                    pergunta_id: currentQuestion.id,
                    sessao_id: sessionId,
                    resposta: answer,
                    tempo_resposta: tempoResposta
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('✅ Resposta salva:', data);
                
                if (data.success) {
                    answeredQuestions.add(currentQuestion.id);
                    
                    // Mostrar feedback
                    if (data.correta) {
                        showFeedback('✓ Resposta correta! Muito bem!', 'success');
                    } else {
                        let msg = '✗ Resposta incorreta.';
                        if (data.resposta_correta) {
                            msg += ` A resposta correta é: ${data.resposta_correta}`;
                        }
                        if (data.explicacao) {
                            msg += `<br><small>${data.explicacao}</small>`;
                        }
                        showFeedback(msg, 'error');
                    }
                    
                    // Notificar página pai
                    window.parent.postMessage({
                        type: 'question_answered',
                        question_id: currentQuestion.id,
                        answer: answer,
                        correct: data.correta
                    }, '*');
                    
                    // Continuar vídeo após 2 segundos
                    setTimeout(() => {
                        questionOverlay.style.display = 'none';
                        selectedAnswer = null;
                        video.play();
                    }, 2000);
                } else {
                    showFeedback('Erro ao salvar resposta. Tente novamente.', 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-check-circle"></i> Confirmar Resposta';
                }
            })
            .catch(error => {
                console.error('❌ Erro ao salvar resposta:', error);
                showFeedback('Erro de conexão. Tente novamente.', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle"></i> Confirmar Resposta';
            });
        }
        
        // Mostrar feedback
        function showFeedback(message, type) {
            feedbackMessage.innerHTML = message;
            feedbackMessage.className = 'feedback-message ' + type;
            feedbackMessage.style.display = 'block';
        }
        
        // Atualizar progresso
        function updateProgress(posicao, percentual) {
            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'update_progress',
                    session_id: sessionId,
                    posicao: posicao,
                    tempo_assistido: posicao,
                    percentual: percentual
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('📊 Progresso atualizado:', data);
            })
            .catch(error => {
                console.error('❌ Erro ao atualizar progresso:', error);
            });
        }
        
        // Completar sessão
        function completeSession() {
            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'complete_session',
                    session_id: sessionId
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('🎉 Sessão concluída:', data);
                
                // Notificar página pai
                window.parent.postMessage({
                    type: 'video_completed',
                    session_id: sessionId,
                    statistics: data.estatisticas
                }, '*');
            })
            .catch(error => {
                console.error('❌ Erro ao completar sessão:', error);
            });
        }
    </script>
</body>
</html>

