<?php
/**
 * PÁGINA DE VÍDEO INTERATIVO PARA PACIENTES (TRIAGEM)
 * URL: /triage-video-medico.php?token={token}
 * 
 * Esta página carrega o player de vídeo interativo embutido,
 * validando o acesso pelo token único enviado por email.
 */

// --- LÓGICA DE VALIDAÇÃO DE TOKEN E BUSCA DE DADOS DO PACIENTE ---

// Configurações básicas
// Assumindo que este arquivo está na raiz do projeto e 'app' é um subdiretório
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

// Incluir configurações (Substitua pelos seus includes reais)
// require_once APP_PATH . '/config/constants.php';
// require_once APP_PATH . '/config/config.php';
// require_once APP_PATH . '/config/database.php';

// --- SIMULAÇÃO DA CLASSE DATABASE ---
// Remova esta simulação e inclua seu arquivo de database real
if (!class_exists('Database')) {
    class Database {
        public static function getInstance() {
            return new class {
                public function fetch($sql, $params) {
                    // Simulação: Apenas para que o código PHP não quebre.
                    // Token de teste: ABC123XYZ789
                    if ($params[0] === 'ABC123XYZ789') { 
                        return [
                            'id' => 123,
                            'nome' => 'Paciente Teste',
                            'email' => 'teste@exemplo.com',
                            'instituicao_nome' => 'Hospital Simulado',
                            'token_acesso' => 'ABC123XYZ789'
                        ];
                    }
                    return false;
                }
            };
        }
    }
}
// --- FIM DA SIMULAÇÃO ---


// Pegar token da URL
$token = $_GET['token'] ?? null;

if (!$token) {
    // HTML para Token não informado
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
    // Acessar o banco de dados
    $db = Database::getInstance();
    
    // Buscar paciente pelo token
    $sql = "SELECT p.*, i.nome as instituicao_nome
            FROM pacientes p
            JOIN instituicoes i ON p.instituicao_id = i.id
            WHERE p.token_acesso = ? AND i.status = 'ativo'";
    
    $paciente = $db->fetch($sql, [$token]);
    
    if (!$paciente) {
        // HTML para Token inválido
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
    
    // Dados do paciente para o JavaScript
    $paciente_id = $paciente['id'];
    $paciente_token = $paciente['token_acesso'];
    $paciente_nome = $paciente['nome'];
    $instituicao_nome = $paciente['instituicao_nome'];
    
} catch (Exception $e) {
    die('Erro ao processar solicitação: ' . htmlspecialchars($e->getMessage()));
}

// --- ESTRUTURA DE VÍDEOS E PERGUNTAS (DO PASTED_CONTENT_4.TXT) ---

$videos = [
    [
        'id' => 'video_1',
        'title' => 'Vídeo 1 - Introdução',
        'src' => 'assets/fallback/video_1.mp4',
        'questions' => [
            [
                'id' => 'v1_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 1',
                'text' => 'Você entendeu como o sistema vai funcionar e está pronto para continuarmos?',
                'instruction' => '',
                'yesLabel' => 'Sim, podemos continuar',
                'noLabel' => 'Não, Gostaria de mais explicações'
            ],
        ],
    ],
    [
        'id' => 'video_2',
        'title' => 'Vídeo 2 - Histórico Clínico',
        'src' => 'assets/fallback/video_2.mp4',
        'questions' => [
            [
                'id' => 'v2_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 2',
                'text' => 'Você realizou algum procedimento anterior?',
                'instruction' => ''
            ],
            [
                'id' => 'v2_q2',
                'type' => 'text',
                'title' => 'Pergunta 2 de 2',
                'text' => 'Descreva qualquer problema anterior em alguma cirurgia já vivenciado.',
                'instruction' => '',
                'showIf' => [
                    'questionIndex' => 0,
                    'equals' => 'Sim'
                ]
            ],
        ],
    ],
    [
        'id' => 'video_3',
        'title' => 'Vídeo 3 - Avaliação Cardiovascular',
        'src' => 'assets/fallback/video_3.mp4',
        'questions' => [
            [
                'id' => 'v3_q1',
                'type' => 'boolean',
                'title' => 'Pergunta 1 de 4',
                'text' => 'Você tem pressão alta?',
                'instruction' => ''
            ],
            [
                'id' => 'v3_q2',
                'type' => 'boolean',
                'title' => 'Pergunta 2 de 4',
                'text' => 'Você sente dor no peito?',
                'instruction' => ''
            ],
            [
                'id' => 'v3_q3',
                'type' => 'boolean',
                'title' => 'Pergunta 3 de 4',
                'text' => 'Você já teve infarto?',
                'instruction' => ''
            ],
            [
                'id' => 'v3_q4',
                'type' => 'boolean',
                'title' => 'Pergunta 4 de 4',
                'text' => 'Você já precisou colocar molinhas no coração?',
                'instruction' => ''
            ],
        ],
    ],
];

$videosJson = json_encode($videos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Entrevista Pré-anestésica - Triagem</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* --- CSS COMPLETO DO PASTED_CONTENT_4.TXT --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #2c3e50;
        }
        
        
        .main-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .triage-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .card-header h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .card-header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .progress-section {
            padding: 30px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        
        .progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .progress-label {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .progress-percentage {
            font-weight: 700;
            color: #667eea;
        }
        
        .progress-bar {
            width: 100%;
            height: 12px;
            background: #e9ecef;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 6px;
            transition: width 0.5s ease;
        }
        
        .video-section {
            padding: 40px;
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }
        
        .video-container {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            max-height: 50vh;
        }
        
        .doctor-video {
            width: 100%;
            max-width: 100%;
            height: 300px;
            border-radius: 20px;
            display: block;
            object-fit: cover;
            -webkit-playsinline: true;
            playsinline: true;
            -webkit-media-controls: none;
            -webkit-media-controls-panel: none;
            -webkit-media-controls-play-button: none;
            -webkit-media-controls-timeline: none;
            -webkit-media-controls-current-time-display: none;
            -webkit-media-controls-time-remaining-display: none;
            -webkit-media-controls-mute-button: none;
            -webkit-media-controls-volume-slider: none;
            -webkit-media-controls-fullscreen-button: none;
        }
        
        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .video-overlay.active {
            opacity: 1;
        }
        
        .play-button {
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 30px;
            color: #667eea;
            transition: all 0.3s ease;
        }
        
        .play-button:hover {
            background: white;
            transform: scale(1.1);
        }
        
        .play-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        .play-button:disabled:hover {
            transform: none;
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
        }
        
        .video-controls {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: rgba(0,0,0,0.7);
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }
        
        .time-display {
            font-size: 14px;
            font-weight: 500;
        }
        
        .progress-display {
            flex: 1;
            height: 4px;
            background: rgba(255,255,255,0.3);
            border-radius: 2px;
            overflow: hidden;
        }
        
        .progress-fill-video {
            height: 100%;
            background: #667eea;
            border-radius: 2px;
            transition: width 0.1s ease;
        }
        
        .start-section {
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
        }
        
        .start-button {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 20px 40px;
            border-radius: 20px;
            font-size: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 0 auto;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
            min-width: 280px;
            justify-content: center;
            min-height: 60px;
        }
        
        .start-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(40, 167, 69, 0.4);
        }
        
        .start-button:active {
            transform: translateY(-2px);
        }
        
        .start-button:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .response-section {
            padding: 20px;
            background: #f8f9fa;
            display: none;
        }
        
        .response-section.active {
            display: block;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        
        .response-header {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .response-header h3 {
            font-size: 20px;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .response-header p {
            color: #2c3e50;
            font-size: 20px;
            font-weight: 600;
            line-height: 1.4;
        }
        
        .response-header p strong {
            font-size: 22px;
            font-weight: 700;
            display: block;
            color: #1a2a4a;
        }
        
        .response-buttons {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 15px 0;
        }
        
        .response-input {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            margin: 15px 0;
        }
        
        .response-input textarea {
            width: 100%;
            max-width: 520px;
            min-height: 120px;
            padding: 15px;
            border-radius: 12px;
            border: 1px solid #ced4da;
            font-size: 16px;
            font-family: inherit;
            resize: vertical;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .response-input textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }
        
        .response-btn {
            padding: 15px 30px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 160px;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            min-height: 50px;
        }
        
        .btn-yes {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
        }
        
        .btn-yes:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(46, 204, 113, 0.4);
        }
        
        .btn-no {
            background: linear-gradient(135deg, #e67e22, #d35400);
            color: white;
        }
        
        .btn-no:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(230, 126, 34, 0.4);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }
        
        .btn-submit:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(41, 128, 185, 0.4);
        }
        
        .response-btn:active {
            transform: translateY(-2px);
        }
        
        .status-message {
            padding: 20px 25px;
            border-radius: 15px;
            margin: 25px 0;
            font-weight: 500;
            text-align: center;
            font-size: 16px;
        }
        
        .status-info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 5px solid #17a2b8;
        }
        
        .status-success {
            background: #d4edda;
            color: #155724;
            border-left: 5px solid #28a745;
        }
        
        .status-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 5px solid #dc3545;
        }
        
        .status-warning {
            background: #fff3cd;
            color: #856404;
            border-left: 5px solid #ffc107;
        }
        
        .footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 8px 0;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
        
        .hidden {
            display: none;
        }
        
        @media (max-width: 768px) {
            body {
                font-size: 16px;
                line-height: 1.4;
            }
            
            
            .main-container {
                padding: 10px;
                min-height: 100vh;
            }
            
            .triage-card {
                border-radius: 15px;
                margin-bottom: 10px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
            
            .card-header {
                padding: 20px 15px;
            }
            
            .card-header h2 {
                font-size: 20px;
                margin-bottom: 10px;
            }
            
            .card-header p {
                font-size: 14px;
            }
            
            .video-section {
                padding: 15px;
            }
            
            .video-container {
                margin: 10px 0;
                border-radius: 15px;
            }
            
            .doctor-video {
                height: 200px;
                border-radius: 15px;
            }
            
            .response-section {
                padding: 15px;
            }
            
            
            .response-header h3 {
                font-size: 16px;
                margin-bottom: 6px;
            }
            
            .response-header p {
                font-size: 18px;
            }
            
            .response-header p strong {
                font-size: 20px;
            }
            
            .response-buttons {
                flex-direction: column;
                gap: 15px;
                margin: 10px 0;
            }
            
            .response-input {
                width: 100%;
            }
            
            .response-input textarea {
                max-width: 100%;
                min-height: 100px;
            }
            
            .response-btn {
                width: 100%;
                padding: 15px 20px;
                font-size: 18px;
                min-height: 50px;
                border-radius: 12px;
                gap: 8px;
            }
            
            .start-button {
                width: 90%;
                max-width: 300px;
                font-size: 18px;
                padding: 18px 30px;
                min-height: 55px;
                border-radius: 15px;
            }
            
            .progress-bar {
                height: 8px;
                margin: 15px 0;
            }
            
            .status-message {
                font-size: 14px;
                padding: 10px 15px;
                border-radius: 10px;
            }
        }
        
        @media (max-width: 480px) {
            
            .main-container {
                padding: 5px;
            }
            
            .triage-card {
                border-radius: 10px;
            }
            
            .card-header {
                padding: 15px 10px;
            }
            
            .card-header h2 {
                font-size: 18px;
            }
            
            .video-section {
                padding: 10px;
            }
            
            .doctor-video {
                height: 180px;
            }
            
            .response-section {
                padding: 10px;
            }
            
            .response-btn {
                padding: 12px 15px;
                font-size: 16px;
                min-height: 45px;
            }
            
            .start-button {
                padding: 18px 12px;
                font-size: 18px;
                min-height: 55px;
            }
        }
    </style>
</head>
<body>
    <main class="main-container">
        <div class="triage-card">
            <div class="card-header">
                <h2><i class="fas fa-stethoscope"></i> Entrevista Pré-anestésica com IA</h2>
                <p>Olá, <?= htmlspecialchars($paciente_nome) ?>! Dr(a). Liege conduzirá uma entrevista pré-anestésica</p>
            </div>
            
            <div class="progress-section">
                <div class="progress-info">
                    <span class="progress-label" id="progressLabel">Progresso da Entrevista</span>
                    <span class="progress-percentage" id="progressPercentage">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill" style="width: 0%"></div>
                </div>
            </div>
            
            <div class="video-section">
                <div class="video-container">
                    <video class="doctor-video" id="doctorVideo" preload="metadata" 
                           playsinline webkit-playsinline 
                           disablepictureinpicture 
                           controlslist="nodownload nofullscreen noremoteplayback" 
                           x-webkit-airplay="deny">
                        <source src="assets/fallback/video_1.mp4" type="video/mp4">
                        Seu navegador não suporta vídeos HTML5.
                    </video>
                    
                    <div class="video-overlay" id="videoOverlay">
                        <button class="play-button" id="playButton">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                    
                    <div class="video-controls" id="videoControls">
                        <div class="time-display" id="timeDisplay">00:00 / 00:00</div>
                        <div class="progress-display">
                            <div class="progress-fill-video" id="progressFillVideo"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="start-section" id="startSection">
                <button class="start-button" id="startButton">
                    <i class="fas fa-play"></i>
                    Vamos Iniciar a Entrevista
                </button>
            </div>
            
            <div class="response-section" id="responseSection">
                <div class="response-header">
                    <h3 id="responseTitle"><i class="fas fa-question-circle"></i> Sua resposta</h3>
                    <p id="responseInstruction">Clique em "Sim" ou "Não" para responder</p>
                </div>
                
                <div class="response-buttons" id="responseButtons">
                    <button class="response-btn btn-yes" id="yesBtn">
                        <i class="fas fa-check"></i>
                        Sim
                    </button>
                    <button class="response-btn btn-no" id="noBtn">
                        <i class="fas fa-times"></i>
                        Não
                    </button>
                </div>

                <div class="response-input hidden" id="responseInput">
                    <textarea id="textAnswer" placeholder="Digite sua resposta aqui..."></textarea>
                    <button class="response-btn btn-submit" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Enviar resposta
                    </button>
                </div>

                <div class="start-section hidden" id="continueSection">
                    <button class="start-button" id="continueButton">
                        <i class="fas fa-arrow-right"></i>
                        Continuar
                    </button>
                </div>
            </div>
        </div>
        
        <div class="status-message status-info" id="statusMessage">
            <i class="fas fa-info-circle"></i>
            Clique no botão para iniciar a entrevista pré-anestésica
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2024 Sistema de Entrevista Pré-anestésica com IA - <?= htmlspecialchars($instituicao_nome) ?></p>
    </footer>

    <script>
        // --- DADOS DO PACIENTE INJETADOS PELO PHP ---
        const PACIENTE_ID = <?= $paciente_id ?>;
        const PACIENTE_TOKEN = '<?= $paciente_token ?>';
        const VIDEO_CALLBACK_URL = 'video_callback.php'; // Ajustado para o nome do arquivo
        const CONSENTIMENTO_URL = 'paciente_acesso.php?token=' + PACIENTE_TOKEN; // Ajustado para o nome do arquivo
        
        // --- DADOS DO VÍDEO E PERGUNTAS ---
        const videosData = <?php echo $videosJson; ?>;
        
        // --- VARIÁVEIS DE ESTADO ---
        let isStarted = false;
        let currentVideoIndex = 0;
        let currentQuestionIndex = 0;
        let completedVideos = 0;
        let currentPhase = 'waiting';
        let responses = [];
        
        const totalVideos = videosData.length;
        
        // --- ELEMENTOS DOM ---
        const video = document.getElementById('doctorVideo');
        const videoOverlay = document.getElementById('videoOverlay');
        const playButton = document.getElementById('playButton');
        const videoControls = document.getElementById('videoControls');
        const timeDisplay = document.getElementById('timeDisplay');
        const progressFillVideo = document.getElementById('progressFillVideo');
        const startSection = document.getElementById('startSection');
        const responseSection = document.getElementById('responseSection');
        const responseTitle = document.getElementById('responseTitle');
        const responseInstruction = document.getElementById('responseInstruction');
        const responseButtons = document.getElementById('responseButtons');
        const yesBtn = document.getElementById('yesBtn');
        const noBtn = document.getElementById('noBtn');
        const responseInput = document.getElementById('responseInput');
        const textAnswer = document.getElementById('textAnswer');
        const submitButton = document.getElementById('submitBtn');
        const continueSection = document.getElementById('continueSection');
        const continueButton = document.getElementById('continueButton');
        const statusMessage = document.getElementById('statusMessage');
        const progressLabel = document.getElementById('progressLabel');
        let currentChoiceAnswers = { yes: 'Sim', no: 'Não' };
        
        // --- LISTENERS ---
        document.getElementById('startButton').addEventListener('click', startInterview);
        document.getElementById('playButton').addEventListener('click', playVideo);
        yesBtn.addEventListener('click', () => respondQuestion(currentChoiceAnswers.yes));
        noBtn.addEventListener('click', () => respondQuestion(currentChoiceAnswers.no));
        document.getElementById('continueButton').addEventListener('click', continueToNextVideo);
        submitButton.addEventListener('click', submitTextResponse);
        textAnswer.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && (event.ctrlKey || event.metaKey)) {
                event.preventDefault();
                submitTextResponse();
            }
        });
        
        // --- FUNÇÕES DE COMUNICAÇÃO AJAX ---
        
        function saveResponse(data) {
            fetch(VIDEO_CALLBACK_URL + '?action=save_response', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log('Resposta salva com sucesso no servidor.');
                } else {
                    console.error('Erro ao salvar resposta:', result.message);
                    updateStatus('❌ Erro ao salvar resposta no servidor. Tente novamente.', 'error');
                }
            })
            .catch(error => {
                console.error('Erro de rede ao salvar resposta:', error);
                updateStatus('❌ Erro de rede. Verifique sua conexão.', 'error');
            });
        }
        
        function sendCompletionCallback() {
            fetch(VIDEO_CALLBACK_URL + '?action=complete_video', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    paciente_id: PACIENTE_ID,
                    paciente_token: PACIENTE_TOKEN,
                    status: 'completed'
                }),
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log('Callback de conclusão enviado com sucesso.');
                } else {
                    console.error('Erro ao enviar callback de conclusão:', result.message);
                }
            })
            .catch(error => {
                console.error('Erro de rede ao enviar callback de conclusão:', error);
            });
        }
        
        // --- FUNÇÕES DE CONTROLE (AJUSTADAS) ---
        
        function startInterview() {
            if (isStarted) return;
            isStarted = true;
            currentPhase = 'video';
            startSection.style.display = 'none';
            updateProgress();
            prepareVideo(currentVideoIndex);
            setTimeout(() => {
                playVideo();
            }, 800);
        }
        
        function prepareVideo(index) {
            const videoData = videosData[index];
            if (!videoData) {
                finishTriagem();
                return;
            }
            
            currentVideoIndex = index;
            currentQuestionIndex = 0;
            currentPhase = 'video';
            
            videoOverlay.classList.add('active');
            playButton.disabled = false;
            playButton.style.opacity = '1';
            playButton.style.cursor = 'pointer';
            
            video.pause();
            video.currentTime = 0;
            progressFillVideo.style.width = '0%';
            timeDisplay.textContent = '00:00 / 00:00';
            
            const currentSource = video.querySelector('source');
            currentSource.src = videoData.src;
            video.load();
            
            progressLabel.textContent = `Progresso da Entrevista (${completedVideos} de ${totalVideos} vídeos concluídos)`;
            updateStatus(`🎬 ${videoData.title} pronto. Clique para assistir.`, 'info');
            
            hideQuestions();
        }
        
        function playVideo() {
            if (!videosData[currentVideoIndex]) return;
            videoOverlay.classList.remove('active');
            video.play().then(() => {
                currentPhase = 'video';
                updateStatus('🎬 Dr(a). Liege está falando...', 'info');
            }).catch(() => {
                updateStatus('❌ Não foi possível reproduzir o vídeo automaticamente. Clique em play.', 'error');
                videoOverlay.classList.add('active');
            });
        }
        
        function handleVideoEnd() {
            currentPhase = 'questions';
            playButton.disabled = true;
            playButton.style.opacity = '0.5';
            playButton.style.cursor = 'not-allowed';
            showQuestions();
        }
        
        function showQuestions() {
            const videoData = videosData[currentVideoIndex];
            const questions = videoData?.questions || [];
            
            if (questions.length === 0) {
                // Se não houver perguntas, registra uma resposta nula e conclui o vídeo
                responses.push({
                    videoId: videoData.id,
                    videoTitle: videoData.title,
                    questionIndex: null,
                    questionText: null,
                    answer: null,
                    type: 'none',
                    questionId: null,
                    timestamp: new Date().toISOString()
                });
                concludeVideo();
                return;
            }
            
            prepareQuestionInterface();
            updateStatus('📝 Responda às perguntas do vídeo para continuar.', 'warning');

            const firstQuestionIndex = getNextQuestionIndex(videoData, -1);
            if (firstQuestionIndex === null) {
                concludeVideo();
                return;
            }

            currentQuestionIndex = firstQuestionIndex;
            responseSection.classList.add('active');
            renderQuestion();
        }
        
        function renderQuestion() {
            const videoData = videosData[currentVideoIndex];
            const questions = videoData.questions || [];
            const question = questions[currentQuestionIndex];
            
            if (!question) {
                concludeVideo();
                return;
            }
            
            responseTitle.innerHTML = `<i class="fas fa-question-circle"></i> ${question.title || 'Pergunta'}`;
            if (question.instruction && question.instruction.trim() !== '') {
                responseInstruction.innerHTML = `<strong>${question.text || ''}</strong><br>${question.instruction}`;
            } else {
                responseInstruction.innerHTML = `<strong>${question.text || ''}</strong>`;
            }
            
            const questionType = question.type || 'boolean';
            
            if (questionType === 'boolean') {
                const yesLabel = question.yesLabel || 'Sim';
                const noLabel = question.noLabel || 'Não';
                const yesValue = question.yesValue || yesLabel;
                const noValue = question.noValue || noLabel;
                
                yesBtn.innerHTML = `<i class="fas fa-check"></i> ${yesLabel}`;
                noBtn.innerHTML = `<i class="fas fa-times"></i> ${noLabel}`;
                
                currentChoiceAnswers = {
                    yes: yesValue,
                    no: noValue
                };
            } else {
                currentChoiceAnswers = { yes: 'Sim', no: 'Não' };
            }
            
            if (questionType === 'text') {
                responseButtons.classList.add('hidden');
                responseInput.classList.remove('hidden');
                continueSection.classList.add('hidden');
                textAnswer.value = '';
                setTimeout(() => textAnswer.focus(), 100);
            } else {
                responseButtons.classList.remove('hidden');
                responseInput.classList.add('hidden');
                continueSection.classList.add('hidden');
            }
        }

        function respondQuestion(answer) {
            if (currentPhase !== 'questions') return;
            
            const videoData = videosData[currentVideoIndex];
            const questions = videoData.questions || [];
            const question = questions[currentQuestionIndex];
            
            if (!question) {
                concludeVideo();
                return;
            }
            
            const questionType = question.type || 'boolean';
            const processedAnswer = questionType === 'text' ? (answer || '').trim() : answer;
            
            if (questionType === 'text' && processedAnswer.length === 0) {
                updateStatus('✏️ Por favor, preencha sua resposta antes de continuar.', 'warning');
                textAnswer.focus();
                return;
            }
            
            const responseData = {
                paciente_id: PACIENTE_ID,
                paciente_token: PACIENTE_TOKEN,
                video_id: videoData.id,
                video_title: videoData.title,
                question_index: currentQuestionIndex + 1,
                question_text: question.text,
                answer: processedAnswer,
                type: questionType,
                question_id: question.id || null,
                timestamp: new Date().toISOString()
            };
            
            responses.push(responseData);
            
            // --- CHAMADA AJAX PARA video_callback.php ---
            saveResponse(responseData);
            
            const statusMessageText = questionType === 'text'
                ? '✅ Resposta registrada.'
                : `✅ Resposta registrada: ${processedAnswer}`;
            updateStatus(statusMessageText, 'success');
            
            const nextQuestionIndex = getNextQuestionIndex(videoData, currentQuestionIndex);
            if (nextQuestionIndex !== null) {
                currentQuestionIndex = nextQuestionIndex;
                renderQuestion();
            } else {
                concludeVideo();
            }
        }
        
        function submitTextResponse() {
            respondQuestion(textAnswer.value);
        }

        function getVideoResponses(videoId) {
            return responses.filter(response => response.videoId === videoId);
        }

        function shouldShowQuestion(videoData, questionIndex) {
            const questions = videoData.questions || [];
            const question = questions[questionIndex];
            
            if (!question) return false;
            if (!question.showIf) return true;

            const condition = question.showIf;
            const videoResponses = getVideoResponses(videoData.id);
            let referenceResponse = null;

            // Busca a resposta da pergunta referenciada
            if (condition.questionId) {
                referenceResponse = videoResponses.find(response => response.questionId === condition.questionId);
            } else if (typeof condition.questionIndex === 'number') {
                // questionIndex é 0-based
                const refIndex = condition.questionIndex;
                referenceResponse = videoResponses.find(response => response.question_index === refIndex + 1);
            }

            if (!referenceResponse) return false;

            if (condition.equals !== undefined) {
                return referenceResponse.answer === condition.equals;
            }

            if (condition.notEquals !== undefined) {
                return referenceResponse.answer !== condition.notEquals;
            }

            if (Array.isArray(condition.in)) {
                return condition.in.includes(referenceResponse.answer);
            }

            if (Array.isArray(condition.notIn)) {
                return !condition.notIn.includes(referenceResponse.answer);
            }

            return true;
        }
        
        function getNextQuestionIndex(videoData, fromIndex) {
            const questions = videoData.questions || [];
            let nextIndex = fromIndex + 1;

            while (nextIndex < questions.length) {
                if (shouldShowQuestion(videoData, nextIndex)) {
                    return nextIndex;
                }
                nextIndex++;
            }

            return null;
        }
        
        function concludeVideo() {
            completedVideos = Math.max(completedVideos, currentVideoIndex + 1);
            updateProgress();
            currentPhase = 'awaitingNext';
            
            responseButtons.classList.add('hidden');
            responseInput.classList.add('hidden');
            continueSection.classList.remove('hidden');
            continueButton.focus();
            
            const hasNextVideo = completedVideos < totalVideos;
            continueButton.innerHTML = hasNextVideo
                ? '<i class="fas fa-arrow-right"></i> Continuar para o próximo vídeo'
                : '<i class="fas fa-flag-checkered"></i> Finalizar entrevista';
            
            responseSection.classList.add('active');
            responseTitle.innerHTML = '<i class="fas fa-check-circle"></i> Etapa concluída';
            responseInstruction.innerHTML = hasNextVideo
                ? 'Clique em continuar para assistir ao próximo vídeo.'
                : 'Clique em finalizar para encerrar a entrevista e prosseguir para o Termo de Consentimento.';
            
            updateStatus(hasNextVideo
                ? '✅ Vídeo concluído. Avance para o próximo quando estiver pronto.'
                : '🏁 Todos os vídeos foram concluídos. Finalize para prosseguir.', 'success');
            
            if (!hasNextVideo) {
                continueButton.dataset.action = 'finish';
                // Envia o callback de conclusão para o servidor
                sendCompletionCallback();
            } else {
                continueButton.dataset.action = 'next';
            }
        }
        
        function prepareQuestionInterface() {
            responseButtons.classList.remove('hidden');
            responseInput.classList.add('hidden');
            continueSection.classList.add('hidden');
            textAnswer.value = '';
            currentChoiceAnswers = { yes: 'Sim', no: 'Não' };
        }
        
        function continueToNextVideo() {
            if (continueButton.dataset.action === 'finish') {
                finishTriagem();
                return;
            }
            
            if (completedVideos >= totalVideos) {
                finishTriagem();
                return;
            }
            
            continueSection.classList.add('hidden');
            responseSection.classList.remove('active');
            prepareVideo(completedVideos);
            
            setTimeout(() => {
                playVideo();
            }, 600);
        }
        
        function updateProgress() {
            const progress = Math.round((completedVideos / totalVideos) * 100);
            document.getElementById('progressFill').style.width = progress + '%';
            document.getElementById('progressPercentage').textContent = progress + '%';
            progressLabel.textContent = `Progresso da Entrevista (${completedVideos} de ${totalVideos} vídeos concluídos)`;
        }
        
        function hideQuestions() {
            responseSection.classList.remove('active');
            responseButtons.classList.remove('hidden');
            responseInput.classList.add('hidden');
            continueSection.classList.add('hidden');
            textAnswer.value = '';
            currentChoiceAnswers = { yes: 'Sim', no: 'Não' };
        }
        
        function updateStatus(message, type = 'info') {
            const icon = type === 'success' ? 'fas fa-check-circle' : 
                        type === 'error' ? 'fas fa-exclamation-circle' :
                        type === 'warning' ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
            
            statusMessage.style.display = 'block';
            statusMessage.innerHTML = `<i class="${icon}"></i> ${message}`;
            statusMessage.className = `status-message status-${type}`;
        }
        
        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        
        function updateVideoProgress() {
            if (video.duration) {
                const progress = (video.currentTime / video.duration) * 100;
                progressFillVideo.style.width = progress + '%';
                timeDisplay.textContent = `${formatTime(video.currentTime)} / ${formatTime(video.duration)}`;
            }
        }
        
        function finishTriagem() {
            currentPhase = 'finished';
            updateProgress();
            updateStatus('🎉 Entrevista concluída com sucesso!', 'success');
            
            playButton.disabled = true;
            playButton.style.opacity = '0.5';
            playButton.style.cursor = 'not-allowed';
            
            // Redireciona para a página de consentimento
            window.location.href = CONSENTIMENTO_URL;
        }
        
        video.addEventListener('timeupdate', updateVideoProgress);
        video.addEventListener('ended', handleVideoEnd);
        
        video.addEventListener('play', function() {
            videoControls.style.display = 'flex';
        });
        
        video.addEventListener('pause', function() {
            if (currentPhase === 'questions' || currentPhase === 'awaitingNext') {
                videoControls.style.display = 'flex';
            }
        });
        
        video.addEventListener('loadedmetadata', function() {
            updateVideoProgress();
            if (!isStarted) {
                updateStatus('📹 Vídeo carregado - Pronto para iniciar entrevista', 'success');
            }
        });
        
        video.addEventListener('error', function() {
            updateStatus('❌ Erro ao carregar o vídeo', 'error');
        });
        
        updateProgress();
    </script>
</body>
</html>
