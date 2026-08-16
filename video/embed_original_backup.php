<?php
/**
 * Sistema de Embed para Vídeos Interativos
 * Permite incorporar vídeos com perguntas em outros sites
 */

// Headers para permitir incorporação em outros sites
header('X-Frame-Options: ALLOWALL');
header('Content-Security-Policy: frame-ancestors *');

// Configurações do banco
require_once 'config.php';

// Obter ID do vídeo
$video_id = isset($_GET['video_id']) ? (int)$_GET['video_id'] : 0;
$theme = isset($_GET['theme']) ? $_GET['theme'] : 'default';
$width = isset($_GET['width']) ? $_GET['width'] : '100%';
$height = isset($_GET['height']) ? $_GET['height'] : '400px';

if (!$video_id) {
    die('ID do vídeo não fornecido');
}

// Buscar dados do vídeo
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
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    die('Erro na conexão: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($video['title']) ?> - Vídeo Interativo</title>
    
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
            background: rgba(0,0,0,0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        
        .question-card {
            background: <?= $theme === 'dark' ? '#2d2d2d' : '#ffffff' ?>;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        
        .question-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: <?= $theme === 'dark' ? '#ffffff' : '#333333' ?>;
        }
        
        .question-option {
            margin: 10px 0;
            padding: 12px;
            border: 2px solid <?= $theme === 'dark' ? '#444' : '#dee2e6' ?>;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: <?= $theme === 'dark' ? '#1a1a1a' : '#f8f9fa' ?>;
        }
        
        .question-option:hover {
            border-color: #007bff;
            background: <?= $theme === 'dark' ? '#333' : '#e3f2fd' ?>;
        }
        
        .question-option.selected {
            border-color: #007bff;
            background: #007bff;
            color: white;
        }
        
        .question-controls {
            margin-top: 20px;
            text-align: center;
        }
        
        .btn-respond {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-respond:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
        }
        
        .progress-bar {
            height: 4px;
            background: <?= $theme === 'dark' ? '#444' : '#dee2e6' ?>;
            margin: 10px 15px;
            border-radius: 2px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #007bff, #0056b3);
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .embed-footer {
            padding: 10px 15px;
            text-align: center;
            font-size: 0.8rem;
            color: <?= $theme === 'dark' ? '#aaaaaa' : '#666666' ?>;
            border-top: 1px solid <?= $theme === 'dark' ? '#444' : '#dee2e6' ?>;
        }
        
        .powered-by {
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
        }
        
        .powered-by:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="embed-container">
        <!-- Header do Vídeo -->
        <div class="video-header">
            <h1 class="video-title"><?= htmlspecialchars($video['title']) ?></h1>
            <p class="video-author">Por: <?= htmlspecialchars($video['author']) ?></p>
        </div>
        
        <!-- Container do Vídeo -->
        <div class="video-container">
            <video id="embedVideo" controls>
                <source src="uploads/<?= htmlspecialchars($video['filename']) ?>" type="video/mp4">
                Seu navegador não suporta vídeo HTML5.
            </video>
            
            <!-- Overlay de Pergunta -->
            <div id="questionOverlay" class="question-overlay">
                <div class="question-card">
                    <div id="questionContent">
                        <!-- Conteúdo da pergunta será inserido aqui -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Barra de Progresso -->
        <div class="progress-bar">
            <div id="progressFill" class="progress-fill"></div>
        </div>
        
        <!-- Footer -->
        <div class="embed-footer">
            Powered by <a href="#" class="powered-by">Sistema de Vídeo Interativo</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Dados do vídeo e perguntas
        const videoData = <?= json_encode($video) ?>;
        const questionsData = <?= json_encode($questions) ?>;
        
        // Elementos DOM
        const video = document.getElementById('embedVideo');
        const questionOverlay = document.getElementById('questionOverlay');
        const questionContent = document.getElementById('questionContent');
        const progressFill = document.getElementById('progressFill');
        
        // Variáveis de controle
        let currentQuestionIndex = -1;
        let questionsAnswered = [];
        
        // Inicializar embed
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🎬 Embed do vídeo interativo carregado');
            console.log('📋 Perguntas disponíveis:', questionsData.length);
            
            // Configurar eventos do vídeo
            video.addEventListener('timeupdate', handleTimeUpdate);
            video.addEventListener('loadedmetadata', function() {
                console.log('📹 Vídeo carregado - Duração:', video.duration);
            });
        });
        
        // Atualizar progresso e verificar perguntas
        function handleTimeUpdate() {
            const currentTime = video.currentTime;
            const duration = video.duration;
            
            // Atualizar barra de progresso
            if (duration > 0) {
                const progress = (currentTime / duration) * 100;
                progressFill.style.width = progress + '%';
            }
            
            // Verificar se deve mostrar pergunta
            checkForQuestions(currentTime);
        }
        
        // Verificar perguntas no tempo atual
        function checkForQuestions(currentTime) {
            for (let i = 0; i < questionsData.length; i++) {
                const question = questionsData[i];
                
                // Verificar se chegou no tempo da pergunta
                if (currentTime >= question.time_position && 
                    currentTime <= (question.time_position + 1) && 
                    !questionsAnswered.includes(question.id)) {
                    
                    showQuestion(question, i);
                    break;
                }
            }
        }
        
        // Mostrar pergunta
        function showQuestion(question, index) {
            console.log('❓ Mostrando pergunta:', question.question_text);
            
            currentQuestionIndex = index;
            video.pause();
            
            let optionsHtml = '';
            
            if (question.question_type === 'multiple_choice') {
                const options = JSON.parse(question.options);
                options.forEach((option, i) => {
                    optionsHtml += `
                        <div class="question-option" onclick="selectOption(this, '${option}')">
                            ${option}
                        </div>
                    `;
                });
            } else if (question.question_type === 'true_false') {
                optionsHtml = `
                    <div class="question-option" onclick="selectOption(this, 'true')">
                        Verdadeiro
                    </div>
                    <div class="question-option" onclick="selectOption(this, 'false')">
                        Falso
                    </div>
                `;
            } else if (question.question_type === 'text') {
                optionsHtml = `
                    <textarea class="form-control" id="textAnswer" rows="3" 
                              placeholder="Digite sua resposta aqui..."></textarea>
                `;
            }
            
            questionContent.innerHTML = `
                <h3 class="question-title">${question.question_text}</h3>
                ${optionsHtml}
                <div class="question-controls">
                    <button class="btn-respond" onclick="submitAnswer(${question.id})">
                        Responder
                    </button>
                </div>
            `;
            
            questionOverlay.style.display = 'flex';
        }
        
        // Selecionar opção
        function selectOption(element, value) {
            // Remover seleção anterior
            document.querySelectorAll('.question-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Selecionar nova opção
            element.classList.add('selected');
            element.dataset.value = value;
        }
        
        // Enviar resposta
        function submitAnswer(questionId) {
            let answer = '';
            
            // Obter resposta baseada no tipo
            const selectedOption = document.querySelector('.question-option.selected');
            const textAnswer = document.getElementById('textAnswer');
            
            if (selectedOption) {
                answer = selectedOption.dataset.value;
            } else if (textAnswer) {
                answer = textAnswer.value.trim();
            }
            
            if (!answer) {
                alert('Por favor, selecione ou digite uma resposta.');
                return;
            }
            
            console.log('✅ Resposta enviada:', answer);
            
            // Marcar pergunta como respondida
            questionsAnswered.push(questionId);
            
            // Fechar overlay e continuar vídeo
            questionOverlay.style.display = 'none';
            video.play();
            
            // Enviar resposta para o servidor (opcional)
            sendAnswerToServer(questionId, answer);
        }
        
        // Enviar resposta para servidor
        function sendAnswerToServer(questionId, answer) {
            fetch('api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'submit_answer',
                    question_id: questionId,
                    answer: answer,
                    video_id: videoData.id,
                    embed: true
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('📊 Resposta salva:', data);
            })
            .catch(error => {
                console.error('❌ Erro ao salvar resposta:', error);
            });
        }
    </script>
</body>
</html>

