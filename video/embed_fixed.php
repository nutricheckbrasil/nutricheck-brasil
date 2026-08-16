<?php
/**
 * Sistema de Embed para Vídeos Interativos - VERSÃO CORRIGIDA
 * Integrado com api_video.php do sistema Anestesia Check
 * Compatível com a estrutura de banco de dados existente
 */

// Headers para permitir incorporação em outros sites
header('X-Frame-Options: ALLOWALL');
header('Content-Security-Policy: frame-ancestors *');

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

// Tentar usar a classe Database do sistema principal, se não funcionar, usar config.php local
$video = null;
$perguntas = [];
$session_id = null;
$usar_api = false;

try {
    // Tentar usar o sistema principal
    $base_path = dirname(__DIR__);
    $app_path = $base_path . '/app';
    
    if (file_exists($app_path . '/config/database.php')) {
        require_once $app_path . '/config/constants.php';
        require_once $app_path . '/config/config.php';
        require_once $app_path . '/config/database.php';
        
        $db = Database::getInstance();
        
        // Buscar vídeo - tentar videos_interativos primeiro, depois videos
        $sql = "SELECT * FROM videos_interativos WHERE id = ? AND ativo = 1";
        $video = $db->fetch($sql, [$video_id]);
        
        if (!$video) {
            // Tentar tabela videos (estrutura antiga)
            $sql = "SELECT * FROM videos WHERE id = ?";
            $video = $db->fetch($sql, [$video_id]);
        }
        
        if (!$video) {
            die('Vídeo não encontrado ou inativo');
        }
        
        // Buscar perguntas - tentar video_perguntas primeiro, depois questions
        $sql = "SELECT id, texto_pergunta, tipo_pergunta, opcoes, tempo_exibicao, obrigatoria, pontuacao, ordem
                FROM video_perguntas
                WHERE video_id = ?
                ORDER BY tempo_exibicao ASC, ordem ASC";
        $perguntas = $db->query($sql, [$video_id]);
        
        if (empty($perguntas)) {
            // Tentar tabela questions (estrutura antiga)
            $sql = "SELECT * FROM questions WHERE video_id = ? ORDER BY time_position ASC";
            $perguntas = $db->query($sql, [$video_id]);
        }
        
        // Decodificar JSON das opções
        foreach ($perguntas as &$pergunta) {
            if (isset($pergunta['opcoes']) && $pergunta['opcoes']) {
                $pergunta['opcoes'] = json_decode($pergunta['opcoes'], true);
            }
        }
        
        // Criar sessão de visualização se paciente_id foi fornecido
        if ($paciente_id > 0) {
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
            
            // Tentar criar sessão
            try {
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
                
                $usar_api = true;
            } catch (Exception $e) {
                // Se falhar, continua sem sessão (modo compatibilidade)
                $session_id = null;
                $usar_api = false;
            }
        }
        
    } else {
        throw new Exception('Sistema principal não encontrado');
    }
    
} catch (Exception $e) {
    // Fallback para config.php local (modo demo/antigo)
    try {
        require_once 'config.php';
        $pdo = getDatabaseConnection();
        
        // Buscar vídeo
        $stmt = $pdo->prepare("SELECT * FROM videos WHERE id = ?");
        $stmt->execute([$video_id]);
        $video = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$video) {
            die('Vídeo não encontrado');
        }
        
        // Buscar perguntas
        $stmt = $pdo->prepare("SELECT * FROM questions WHERE video_id = ? ORDER BY time_position ASC");
        $stmt->execute([$video_id]);
        $perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $usar_api = false;
        
    } catch (Exception $e2) {
        die('Erro na conexão: ' . htmlspecialchars($e2->getMessage()));
    }
}

// URL da API (se disponível)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$api_url = $protocol . "://" . $host . "/api_video.php";

// Preparar dados do vídeo para JavaScript
$video_url = isset($video['url_video']) ? $video['url_video'] : (isset($video['video_url']) ? $video['video_url'] : '');
$video_titulo = isset($video['titulo']) ? $video['titulo'] : (isset($video['title']) ? $video['title'] : 'Vídeo');
$video_descricao = isset($video['descricao']) ? $video['descricao'] : (isset($video['description']) ? $video['description'] : '');

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($video_titulo) ?> - Vídeo Interativo</title>
    
    <!-- Bootstrap CSS -->
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
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .video-description {
            margin: 5px 0 0 0;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .video-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            background: #000;
        }
        
        video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        .question-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }
        
        .question-overlay.active {
            display: flex;
        }
        
        .question-card {
            background: <?= $theme === 'dark' ? '#2d2d2d' : '#ffffff' ?>;
            border-radius: 12px;
            padding: 30px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .question-text {
            font-size: 1.2rem;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .answer-option {
            margin-bottom: 10px;
        }
        
        .answer-option button {
            width: 100%;
            text-align: left;
            padding: 15px;
            border: 2px solid <?= $theme === 'dark' ? '#444' : '#dee2e6' ?>;
            background: <?= $theme === 'dark' ? '#1a1a1a' : '#f8f9fa' ?>;
            color: <?= $theme === 'dark' ? '#ffffff' : '#333333' ?>;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 1rem;
        }
        
        .answer-option button:hover {
            border-color: #0d6efd;
            background: <?= $theme === 'dark' ? '#2d2d2d' : '#e7f1ff' ?>;
        }
        
        .answer-option button.correct {
            border-color: #198754;
            background: #d1e7dd;
            color: #0f5132;
        }
        
        .answer-option button.incorrect {
            border-color: #dc3545;
            background: #f8d7da;
            color: #842029;
        }
        
        .feedback-message {
            margin-top: 15px;
            padding: 15px;
            border-radius: 8px;
            display: none;
            animation: fadeIn 0.3s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .feedback-message.success {
            background: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }
        
        .feedback-message.error {
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }
        
        .feedback-message i {
            margin-right: 8px;
        }
        
        .continue-button {
            margin-top: 15px;
            display: none;
        }
        
        .text-answer {
            width: 100%;
            padding: 12px;
            border: 2px solid <?= $theme === 'dark' ? '#444' : '#dee2e6' ?>;
            border-radius: 8px;
            font-size: 1rem;
            background: <?= $theme === 'dark' ? '#1a1a1a' : '#ffffff' ?>;
            color: <?= $theme === 'dark' ? '#ffffff' : '#333333' ?>;
            margin-bottom: 10px;
        }
        
        .text-answer:focus {
            outline: none;
            border-color: #0d6efd;
        }
        
        .loading-spinner {
            display: none;
            text-align: center;
            margin: 10px 0;
        }
        
        .loading-spinner.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="embed-container" style="max-width: <?= htmlspecialchars($width) ?>;">
        <div class="video-header">
            <h1 class="video-title"><?= htmlspecialchars($video_titulo) ?></h1>
            <?php if ($video_descricao): ?>
                <p class="video-description"><?= htmlspecialchars($video_descricao) ?></p>
            <?php endif; ?>
        </div>
        
        <div class="video-wrapper" style="padding-bottom: <?= $height === '100%' ? '56.25%' : 'calc(' . $height . ')' ?>;">
            <video id="videoPlayer" controls>
                <source src="<?= htmlspecialchars($video_url) ?>" type="video/mp4">
                Seu navegador não suporta a reprodução de vídeo.
            </video>
            
            <div id="questionOverlay" class="question-overlay">
                <div class="question-card">
                    <div class="question-text" id="questionText"></div>
                    <div id="answerOptions"></div>
                    <div class="loading-spinner" id="loadingSpinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Enviando...</span>
                        </div>
                    </div>
                    <div class="feedback-message" id="feedbackMessage"></div>
                    <button class="btn btn-primary w-100 continue-button" id="continueButton">
                        Continuar Vídeo <i class="bi bi-play-fill"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configurações
        const VIDEO_ID = <?= $video_id ?>;
        const PACIENTE_ID = <?= $paciente_id ?>;
        const SESSION_ID = <?= $session_id ?? 'null' ?>;
        const USAR_API = <?= $usar_api ? 'true' : 'false' ?>;
        const API_URL = '<?= $api_url ?>';
        
        // Perguntas do vídeo
        const perguntas = <?= json_encode($perguntas, JSON_UNESCAPED_UNICODE) ?>;
        
        // Elementos DOM
        const video = document.getElementById('videoPlayer');
        const overlay = document.getElementById('questionOverlay');
        const questionText = document.getElementById('questionText');
        const answerOptions = document.getElementById('answerOptions');
        const feedbackMessage = document.getElementById('feedbackMessage');
        const continueButton = document.getElementById('continueButton');
        const loadingSpinner = document.getElementById('loadingSpinner');
        
        // Estado
        let currentQuestion = null;
        let questionStartTime = null;
        let progressUpdateInterval = null;
        
        // Inicializar
        video.addEventListener('timeupdate', checkQuestions);
        continueButton.addEventListener('click', continueVideo);
        
        // Atualizar progresso a cada 10 segundos (se API disponível)
        if (USAR_API && SESSION_ID) {
            progressUpdateInterval = setInterval(updateProgress, 10000);
        }
        
        // Verificar se deve mostrar pergunta
        function checkQuestions() {
            const currentTime = video.currentTime;
            
            for (const pergunta of perguntas) {
                const tempo = parseFloat(pergunta.tempo_exibicao || pergunta.time_position || 0);
                
                if (Math.abs(currentTime - tempo) < 0.5 && !pergunta.mostrada) {
                    pergunta.mostrada = true;
                    showQuestion(pergunta);
                    break;
                }
            }
        }
        
        // Mostrar pergunta
        function showQuestion(pergunta) {
            currentQuestion = pergunta;
            questionStartTime = Date.now();
            
            video.pause();
            overlay.classList.add('active');
            
            questionText.textContent = pergunta.texto_pergunta || pergunta.question_text || '';
            answerOptions.innerHTML = '';
            feedbackMessage.style.display = 'none';
            continueButton.style.display = 'none';
            
            const tipo = pergunta.tipo_pergunta || pergunta.question_type || 'multipla_escolha';
            
            if (tipo === 'texto_livre' || tipo === 'text') {
                // Pergunta de texto livre
                const textarea = document.createElement('textarea');
                textarea.className = 'text-answer';
                textarea.placeholder = 'Digite sua resposta...';
                textarea.rows = 4;
                answerOptions.appendChild(textarea);
                
                const submitBtn = document.createElement('button');
                submitBtn.className = 'btn btn-primary w-100';
                submitBtn.textContent = 'Enviar Resposta';
                submitBtn.onclick = () => submitAnswer(textarea.value);
                answerOptions.appendChild(submitBtn);
                
            } else {
                // Múltipla escolha ou Verdadeiro/Falso
                let opcoes = [];
                
                if (tipo === 'verdadeiro_falso' || tipo === 'boolean') {
                    opcoes = [
                        { texto: 'Verdadeiro', valor: 'Verdadeiro' },
                        { texto: 'Falso', valor: 'Falso' }
                    ];
                } else {
                    // Múltipla escolha
                    const opcoesData = pergunta.opcoes || pergunta.options || [];
                    if (Array.isArray(opcoesData)) {
                        opcoes = opcoesData.map(op => ({
                            texto: typeof op === 'string' ? op : (op.texto || op.text || ''),
                            valor: typeof op === 'string' ? op : (op.valor || op.value || op.texto || op.text || '')
                        }));
                    }
                }
                
                opcoes.forEach(opcao => {
                    const div = document.createElement('div');
                    div.className = 'answer-option';
                    
                    const button = document.createElement('button');
                    button.textContent = opcao.texto;
                    button.onclick = () => submitAnswer(opcao.valor, button);
                    
                    div.appendChild(button);
                    answerOptions.appendChild(div);
                });
            }
        }
        
        // Enviar resposta
        async function submitAnswer(resposta, button = null) {
            if (!resposta || resposta.trim() === '') {
                alert('Por favor, forneça uma resposta.');
                return;
            }
            
            const tempoResposta = Math.floor((Date.now() - questionStartTime) / 1000);
            
            // Desabilitar botões
            const buttons = answerOptions.querySelectorAll('button');
            buttons.forEach(btn => btn.disabled = true);
            
            // Se API disponível, enviar para servidor
            if (USAR_API && SESSION_ID) {
                loadingSpinner.classList.add('active');
                
                try {
                    const response = await fetch(API_URL + '?action=submit_answer', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            pergunta_id: currentQuestion.id,
                            sessao_id: SESSION_ID,
                            resposta: resposta,
                            tempo_resposta: tempoResposta
                        })
                    });
                    
                    const data = await response.json();
                    loadingSpinner.classList.remove('active');
                    
                    if (data.success) {
                        showFeedback(data.correta, data.resposta_correta, data.explicacao);
                        if (button) {
                            button.classList.add(data.correta ? 'correct' : 'incorrect');
                        }
                    } else {
                        throw new Error(data.error || 'Erro ao enviar resposta');
                    }
                    
                } catch (error) {
                    console.error('Erro ao enviar resposta:', error);
                    loadingSpinner.classList.remove('active');
                    showFeedback(false, null, 'Erro ao enviar resposta. Por favor, tente novamente.');
                }
            } else {
                // Modo offline/demo - apenas mostra feedback
                showFeedback(true, null, 'Resposta registrada!');
            }
        }
        
        // Mostrar feedback
        function showFeedback(correta, respostaCorreta, explicacao) {
            feedbackMessage.style.display = 'block';
            feedbackMessage.className = 'feedback-message ' + (correta ? 'success' : 'error');
            
            let html = '<i class="bi bi-' + (correta ? 'check-circle-fill' : 'x-circle-fill') + '"></i>';
            html += '<strong>' + (correta ? 'Resposta correta!' : 'Resposta incorreta') + '</strong>';
            
            if (!correta && respostaCorreta) {
                html += '<br><small>Resposta correta: ' + respostaCorreta + '</small>';
            }
            
            if (explicacao) {
                html += '<br><small>' + explicacao + '</small>';
            }
            
            feedbackMessage.innerHTML = html;
            continueButton.style.display = 'block';
        }
        
        // Continuar vídeo
        function continueVideo() {
            overlay.classList.remove('active');
            video.play();
        }
        
        // Atualizar progresso
        async function updateProgress() {
            if (!USAR_API || !SESSION_ID) return;
            
            try {
                await fetch(API_URL + '?action=update_progress', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        session_id: SESSION_ID,
                        current_position: video.currentTime,
                        duration: video.duration
                    })
                });
            } catch (error) {
                console.error('Erro ao atualizar progresso:', error);
            }
        }
        
        // Quando vídeo terminar
        video.addEventListener('ended', async function() {
            if (USAR_API && SESSION_ID) {
                try {
                    await fetch(API_URL + '?action=complete_session', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            session_id: SESSION_ID
                        })
                    });
                } catch (error) {
                    console.error('Erro ao concluir sessão:', error);
                }
            }
        });
        
        // Limpar interval ao sair
        window.addEventListener('beforeunload', function() {
            if (progressUpdateInterval) {
                clearInterval(progressUpdateInterval);
            }
        });
    </script>
</body>
</html>

