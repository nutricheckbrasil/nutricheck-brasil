<?php
/**
 * API DEMO - Sistema de Vídeo Interativo
 * Versão simplificada com SQLite para demonstração
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Configurações
define('DB_FILE', __DIR__ . '/demo_database.sqlite');
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_FILE_SIZE', 50 * 1024 * 1024); // 50MB

// Classe de banco de dados SQLite
class DemoDatabase {
    private $pdo;
    
    public function __construct() {
        try {
            $this->pdo = new PDO('sqlite:' . DB_FILE);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
            $this->insertSampleData();
        } catch (PDOException $e) {
            throw new Exception("Erro de conexão: " . $e->getMessage());
        }
    }
    
    public function getPDO() {
        return $this->pdo;
    }
    
    private function createTables() {
        $tables = [
            "CREATE TABLE IF NOT EXISTS videos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT,
                author TEXT,
                filename TEXT NOT NULL,
                file_size INTEGER,
                duration INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )",
            
            "CREATE TABLE IF NOT EXISTS questions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                video_id INTEGER NOT NULL,
                question_text TEXT NOT NULL,
                question_type TEXT NOT NULL CHECK(question_type IN ('multiple_choice', 'true_false', 'text')),
                options TEXT,
                correct_answer TEXT,
                time_position INTEGER NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (video_id) REFERENCES videos(id) ON DELETE CASCADE
            )",
            
            "CREATE TABLE IF NOT EXISTS viewing_sessions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                video_id INTEGER NOT NULL,
                session_token TEXT,
                user_ip TEXT,
                user_agent TEXT,
                user_name TEXT,
                user_email TEXT,
                started_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                completed_at DATETIME NULL,
                last_position INTEGER DEFAULT 0,
                total_time_watched INTEGER DEFAULT 0,
                completion_percentage REAL DEFAULT 0.0,
                device_type TEXT DEFAULT 'unknown',
                FOREIGN KEY (video_id) REFERENCES videos(id) ON DELETE CASCADE
            )",
            
            "CREATE TABLE IF NOT EXISTS responses (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                question_id INTEGER NOT NULL,
                session_id INTEGER NOT NULL,
                answer TEXT NOT NULL,
                is_correct INTEGER DEFAULT 0,
                answered_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
                FOREIGN KEY (session_id) REFERENCES viewing_sessions(id) ON DELETE CASCADE
            )",
            
            "CREATE TABLE IF NOT EXISTS consent_logs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                session_id INTEGER NOT NULL,
                consent_given INTEGER DEFAULT 1,
                email_sent INTEGER DEFAULT 0,
                email_result TEXT,
                ip_address TEXT,
                user_agent TEXT,
                consent_data TEXT,
                logged_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (session_id) REFERENCES viewing_sessions(id) ON DELETE CASCADE
            )"
        ];
        
        foreach ($tables as $sql) {
            $this->pdo->exec($sql);
        }
    }
    
    private function insertSampleData() {
        // Verificar se já existem dados
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM videos");
        if ($stmt->fetchColumn() > 0) {
            return;
        }
        
        // Inserir vídeo de exemplo
        $stmt = $this->pdo->prepare("
            INSERT INTO videos (title, description, author, filename, file_size, duration) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            'Vídeo de Demonstração - Matemática Básica',
            'Um vídeo educativo sobre conceitos básicos de matemática com perguntas interativas.',
            'Prof. João Silva',
            'demo_video.mp4',
            15728640, // 15MB
            180 // 3 minutos
        ]);
        
        $videoId = $this->pdo->lastInsertId();
        
        // Inserir perguntas de exemplo
        $questions = [
            [
                'video_id' => $videoId,
                'question_text' => 'Quanto é 2 + 2?',
                'question_type' => 'multiple_choice',
                'options' => '["3", "4", "5", "6"]',
                'correct_answer' => '4',
                'time_position' => 30
            ],
            [
                'video_id' => $videoId,
                'question_text' => 'A matemática é importante para a vida cotidiana?',
                'question_type' => 'true_false',
                'options' => '["Verdadeiro", "Falso"]',
                'correct_answer' => 'Verdadeiro',
                'time_position' => 90
            ],
            [
                'video_id' => $videoId,
                'question_text' => 'Explique com suas palavras o que você aprendeu até agora.',
                'question_type' => 'text',
                'options' => null,
                'correct_answer' => 'matemática',
                'time_position' => 150
            ]
        ];
        
        $stmt = $this->pdo->prepare("
            INSERT INTO questions (video_id, question_text, question_type, options, correct_answer, time_position) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        foreach ($questions as $question) {
            $stmt->execute([
                $question['video_id'],
                $question['question_text'],
                $question['question_type'],
                $question['options'],
                $question['correct_answer'],
                $question['time_position']
            ]);
        }
    }
}

// Funções auxiliares
function jsonResponse($data) {
    echo json_encode(array_merge(['success' => true], $data));
    exit;
}

function errorResponse($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

// Criar diretório de uploads
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

// Inicializar banco
try {
    $db = new DemoDatabase();
    $pdo = $db->getPDO();
} catch (Exception $e) {
    errorResponse($e->getMessage(), 500);
}

// Roteamento
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        
        case 'list':
            if ($method !== 'GET') {
                throw new Exception('Método não permitido');
            }
            
            $stmt = $pdo->query("
                SELECT v.*, 
                       COUNT(q.id) as question_count,
                       COUNT(DISTINCT vs.id) as view_count
                FROM videos v 
                LEFT JOIN questions q ON v.id = q.video_id 
                LEFT JOIN viewing_sessions vs ON v.id = vs.video_id 
                GROUP BY v.id 
                ORDER BY v.created_at DESC
            ");
            
            $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            jsonResponse(['videos' => $videos]);
            break;
            
        case 'get_video':
            if ($method !== 'GET') {
                throw new Exception('Método não permitido');
            }
            
            $id = $_GET['id'] ?? null;
            if (!$id) {
                throw new Exception('ID do vídeo não fornecido');
            }
            
            $stmt = $pdo->prepare("SELECT * FROM videos WHERE id = ?");
            $stmt->execute([$id]);
            $video = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$video) {
                throw new Exception('Vídeo não encontrado');
            }
            
            jsonResponse(['video' => $video]);
            break;
            
        case 'create_session':
            if ($method !== 'POST') {
                throw new Exception('Método não permitido');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            $videoId = $data['video_id'] ?? null;
            
            if (!$videoId) {
                throw new Exception('ID do vídeo não fornecido');
            }
            
            $sessionToken = uniqid('session_', true);
            
            $stmt = $pdo->prepare("
                INSERT INTO viewing_sessions (video_id, session_token, user_ip, user_agent) 
                VALUES (?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([
                $videoId,
                $sessionToken,
                $_SERVER['REMOTE_ADDR'] ?? 'demo_ip',
                $_SERVER['HTTP_USER_AGENT'] ?? 'demo_browser'
            ]);
            
            if ($result) {
                jsonResponse([
                    'session_id' => $pdo->lastInsertId(),
                    'session_token' => $sessionToken,
                    'message' => 'Sessão criada com sucesso'
                ]);
            } else {
                throw new Exception('Erro ao criar sessão');
            }
            break;
            
        case 'get_questions':
            if ($method !== 'GET') {
                throw new Exception('Método não permitido');
            }
            
            $videoId = $_GET['video_id'] ?? null;
            if (!$videoId) {
                throw new Exception('ID do vídeo não fornecido');
            }
            
            $stmt = $pdo->prepare("
                SELECT * FROM questions 
                WHERE video_id = ? 
                ORDER BY time_position ASC
            ");
            $stmt->execute([$videoId]);
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            jsonResponse(['questions' => $questions]);
            break;
            
        case 'submit_answer':
            if ($method !== 'POST') {
                throw new Exception('Método não permitido');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $required = ['question_id', 'session_id', 'answer'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    throw new Exception("Campo obrigatório: $field");
                }
            }
            
            // Obter pergunta
            $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
            $stmt->execute([$data['question_id']]);
            $question = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$question) {
                throw new Exception('Pergunta não encontrada');
            }
            
            // Verificar resposta
            $isCorrect = false;
            $userAnswer = trim($data['answer']);
            $correctAnswer = trim($question['correct_answer']);
            
            if ($question['question_type'] === 'text') {
                $isCorrect = (stripos($userAnswer, $correctAnswer) !== false) || (strlen($userAnswer) > 10);
            } else {
                $isCorrect = (strtolower($userAnswer) === strtolower($correctAnswer));
            }
            
            // Salvar resposta
            $stmt = $pdo->prepare("
                INSERT INTO responses (question_id, session_id, answer, is_correct) 
                VALUES (?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([
                $data['question_id'],
                $data['session_id'],
                $userAnswer,
                $isCorrect ? 1 : 0
            ]);
            
            if ($result) {
                jsonResponse([
                    'message' => 'Resposta enviada com sucesso!',
                    'is_correct' => $isCorrect,
                    'correct_answer' => $correctAnswer
                ]);
            } else {
                throw new Exception('Erro ao salvar resposta');
            }
            break;
            
        case 'complete_session':
            if ($method !== 'POST') {
                throw new Exception('Método não permitido');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            $sessionId = $data['session_id'] ?? null;
            $userConsent = $data['user_consent'] ?? false;
            $userName = $data['user_name'] ?? null;
            $userEmail = $data['user_email'] ?? null;
            
            if (!$sessionId) {
                throw new Exception('ID da sessão não fornecido');
            }
            
            $completionPercentage = $data['completion_percentage'] ?? 100;
            $totalTimeWatched = $data['total_time_watched'] ?? 0;
            $deviceType = $data['device_type'] ?? 'unknown';
            
            // Atualizar sessão
            $stmt = $pdo->prepare("
                UPDATE viewing_sessions 
                SET completed_at = datetime('now'),
                    user_name = ?,
                    user_email = ?,
                    completion_percentage = ?,
                    total_time_watched = ?,
                    device_type = ?
                WHERE id = ?
            ");
            
            $result = $stmt->execute([
                $userName,
                $userEmail,
                $completionPercentage,
                $totalTimeWatched,
                $deviceType,
                $sessionId
            ]);
            
            if (!$result) {
                throw new Exception('Erro ao finalizar sessão');
            }
            
            // Buscar dados da sessão
            $stmt = $pdo->prepare("
                SELECT vs.*, v.title, v.author, v.duration,
                       COUNT(r.id) as questions_answered,
                       SUM(r.is_correct) as questions_correct
                FROM viewing_sessions vs
                JOIN videos v ON vs.video_id = v.id
                LEFT JOIN responses r ON r.session_id = vs.id
                WHERE vs.id = ?
                GROUP BY vs.id
            ");
            $stmt->execute([$sessionId]);
            $sessionData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$sessionData) {
                throw new Exception('Dados da sessão não encontrados');
            }
            
            $videoData = [
                'id' => $sessionData['video_id'],
                'title' => $sessionData['title'],
                'author' => $sessionData['author'],
                'duration' => $sessionData['duration']
            ];
            
            $emailResult = ['success' => false, 'message' => 'Email não enviado'];
            
            // Enviar email real se consentimento foi dado
            if ($userConsent) {
                try {
                    require_once 'email_system.php';
                    $emailManager = new ConsentEmailManager();
                    
                    $consentData = [
                        'user_name' => $userName,
                        'user_email' => $userEmail,
                        'completion_percentage' => $completionPercentage,
                        'total_time_watched' => $totalTimeWatched,
                        'device_type' => $deviceType
                    ];
                    
                    $emailResult = $emailManager->sendConsentNotification($sessionData, $videoData, $consentData);
                    $emailManager->logConsentToDatabase($pdo, $sessionId, $emailResult, $consentData);
                    
                } catch (Exception $e) {
                    $emailResult = [
                        'success' => false,
                        'message' => 'Erro no sistema de email: ' . $e->getMessage(),
                        'error' => $e->getMessage()
                    ];
                    error_log("Erro no envio de email de consentimento: " . $e->getMessage());
                }
            }
            
            jsonResponse([
                'message' => 'Sessão finalizada com sucesso!',
                'consent_registered' => $userConsent,
                'email_sent' => $emailResult['success'],
                'email_message' => $emailResult['message'],
                'demo_email_data' => $emailResult['demo_data'] ?? null,
                'session_data' => [
                    'completion_percentage' => $completionPercentage,
                    'questions_answered' => $sessionData['questions_answered'],
                    'questions_correct' => $sessionData['questions_correct']
                ]
            ]);
            break;
            
        case 'stats':
            if ($method !== 'GET') {
                throw new Exception('Método não permitido');
            }
            
            $stats = [];
            
            $stmt = $pdo->query("SELECT COUNT(*) FROM videos");
            $stats['videos'] = $stmt->fetchColumn();
            
            $stmt = $pdo->query("SELECT COUNT(*) FROM questions");
            $stats['questions'] = $stmt->fetchColumn();
            
            $stmt = $pdo->query("SELECT COUNT(*) FROM responses");
            $stats['responses'] = $stmt->fetchColumn();
            
            $stmt = $pdo->query("SELECT COUNT(*) FROM viewing_sessions");
            $stats['sessions'] = $stmt->fetchColumn();
            
            $stmt = $pdo->query("SELECT COUNT(*) as total, SUM(is_correct) as correct FROM responses");
            $responseStats = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['accuracy_rate'] = $responseStats['total'] > 0 ? 
                round(($responseStats['correct'] / $responseStats['total']) * 100, 1) : 0;
            
            jsonResponse(['stats' => $stats]);
            break;
            
        case 'upload':
            if ($method !== 'POST') {
                throw new Exception('Método não permitido');
            }
            
            // Verificar se o arquivo foi enviado
            if (!isset($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Erro no upload do arquivo');
            }
            
            $file = $_FILES['video'];
            $title = $_POST['title'] ?? '';
            $author = $_POST['author'] ?? '';
            $description = $_POST['description'] ?? '';
            
            // Validações
            if (empty($title)) {
                throw new Exception('Título é obrigatório');
            }
            
            if ($file['size'] > MAX_FILE_SIZE) {
                throw new Exception('Arquivo muito grande. Máximo: ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB');
            }
            
            // Verificar tipo de arquivo
            $allowedTypes = ['video/mp4', 'video/webm', 'video/avi', 'video/mov', 'video/quicktime'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                throw new Exception('Tipo de arquivo não suportado. Use: MP4, WebM, AVI, MOV');
            }
            
            // Gerar nome único para o arquivo
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('video_', true) . '.' . $extension;
            $filepath = UPLOAD_DIR . $filename;
            
            // Mover arquivo para diretório de uploads
            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                throw new Exception('Erro ao salvar arquivo');
            }
            
            // Inserir no banco de dados
            $stmt = $pdo->prepare("
                INSERT INTO videos (title, description, author, filename, file_size) 
                VALUES (?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([
                $title,
                $description,
                $author,
                $filename,
                $file['size']
            ]);
            
            if ($result) {
                $videoId = $pdo->lastInsertId();
                jsonResponse([
                    'message' => 'Vídeo enviado com sucesso!',
                    'video_id' => $videoId,
                    'filename' => $filename
                ]);
            } else {
                // Se falhou, remover arquivo
                if (file_exists($filepath)) {
                    unlink($filepath);
                }
                throw new Exception('Erro ao salvar no banco de dados');
            }
            break;
            
        case 'test_email':
            if ($method !== 'POST') {
                throw new Exception('Método não permitido');
            }
            
            try {
                require_once 'email_system.php';
                $emailManager = new ConsentEmailManager();
                
                // Dados de teste
                $sessionData = [
                    'id' => 999,
                    'user_ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Test Browser'
                ];
                
                $videoData = [
                    'id' => 1,
                    'title' => 'Teste do Sistema de Email',
                    'author' => 'Sistema de Teste',
                    'duration' => 180
                ];
                
                $consentData = [
                    'user_name' => 'Usuário de Teste',
                    'user_email' => 'teste@exemplo.com',
                    'completion_percentage' => 100
                ];
                
                $result = $emailManager->sendConsentNotification($sessionData, $videoData, $consentData);
                jsonResponse($result);
                
            } catch (Exception $e) {
                errorResponse('Erro no teste de email: ' . $e->getMessage());
            }
            break;
            
        case 'save_question':
            if ($method !== 'POST') {
                throw new Exception('Método não permitido');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validar dados obrigatórios
            $videoId = $data['video_id'] ?? null;
            $timePosition = $data['time_position'] ?? null;
            $questionText = $data['question_text'] ?? '';
            $questionType = $data['question_type'] ?? 'multiple_choice';
            $options = $data['options'] ?? [];
            $correctAnswer = $data['correct_answer'] ?? null;
            
            if (!$videoId) {
                throw new Exception('ID do vídeo é obrigatório');
            }
            
            if ($timePosition === null) {
                throw new Exception('Tempo da pergunta é obrigatório');
            }
            
            if (empty($questionText)) {
                throw new Exception('Texto da pergunta é obrigatório');
            }
            
            if ($questionType === 'multiple_choice' && empty($options)) {
                throw new Exception('Opções são obrigatórias para perguntas de múltipla escolha');
            }
            
            if ($correctAnswer === null) {
                throw new Exception('Resposta correta é obrigatória');
            }
            
            // Verificar se o vídeo existe
            $stmt = $pdo->prepare("SELECT id FROM videos WHERE id = ?");
            $stmt->execute([$videoId]);
            if (!$stmt->fetch()) {
                throw new Exception('Vídeo não encontrado');
            }
            
            // Inserir pergunta no banco
            $stmt = $pdo->prepare("
                INSERT INTO questions (video_id, time_position, question_text, question_type, options, correct_answer) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([
                $videoId,
                $timePosition,
                $questionText,
                $questionType,
                json_encode($options),
                $correctAnswer
            ]);
            
            if ($result) {
                $questionId = $pdo->lastInsertId();
                jsonResponse([
                    'message' => 'Pergunta salva com sucesso!',
                    'question_id' => $questionId,
                    'video_id' => $videoId,
                    'time_position' => $timePosition
                ]);
            } else {
                throw new Exception('Erro ao salvar pergunta no banco de dados');
            }
            break;
            
        case 'delete_question':
            if ($method !== 'POST') {
                throw new Exception('Método não permitido');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            $questionId = $data['question_id'] ?? null;
            
            if (!$questionId) {
                throw new Exception('ID da pergunta é obrigatório');
            }
            
            // Verificar se a pergunta existe
            $stmt = $pdo->prepare("SELECT id FROM questions WHERE id = ?");
            $stmt->execute([$questionId]);
            if (!$stmt->fetch()) {
                throw new Exception('Pergunta não encontrada');
            }
            
            // Deletar pergunta
            $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
            $result = $stmt->execute([$questionId]);
            
            if ($result) {
                jsonResponse([
                    'message' => 'Pergunta deletada com sucesso!',
                    'question_id' => $questionId
                ]);
            } else {
                throw new Exception('Erro ao deletar pergunta');
            }
            break;
            
        case 'update_question':
            if ($method !== 'POST') {
                throw new Exception('Método não permitido');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $questionId = $data['question_id'] ?? null;
            $timePosition = $data['time_position'] ?? null;
            $questionText = $data['question_text'] ?? '';
            $questionType = $data['question_type'] ?? 'multiple_choice';
            $options = $data['options'] ?? [];
            $correctAnswer = $data['correct_answer'] ?? null;
            
            if (!$questionId) {
                throw new Exception('ID da pergunta é obrigatório');
            }
            
            if ($timePosition === null) {
                throw new Exception('Tempo da pergunta é obrigatório');
            }
            
            if (empty($questionText)) {
                throw new Exception('Texto da pergunta é obrigatório');
            }
            
            // Verificar se a pergunta existe
            $stmt = $pdo->prepare("SELECT id FROM questions WHERE id = ?");
            $stmt->execute([$questionId]);
            if (!$stmt->fetch()) {
                throw new Exception('Pergunta não encontrada');
            }
            
            // Atualizar pergunta
            $stmt = $pdo->prepare("
                UPDATE questions 
                SET time_position = ?, question_text = ?, question_type = ?, options = ?, correct_answer = ?
                WHERE id = ?
            ");
            
            $result = $stmt->execute([
                $timePosition,
                $questionText,
                $questionType,
                json_encode($options),
                $correctAnswer,
                $questionId
            ]);
            
            if ($result) {
                jsonResponse([
                    'message' => 'Pergunta atualizada com sucesso!',
                    'question_id' => $questionId
                ]);
            } else {
                throw new Exception('Erro ao atualizar pergunta');
            }
            break;
            
        default:
            throw new Exception('Ação não encontrada: ' . $action);
    }
    
} catch (Exception $e) {
    error_log("Erro na API Demo: " . $e->getMessage());
    errorResponse($e->getMessage());
}
?>

