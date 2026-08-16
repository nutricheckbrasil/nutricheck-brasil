<?php
/**
 * AJAX - Salvar Respostas do Vídeo Interativo
 * 
 * Recebe as respostas das perguntas e salva no banco de dados
 * vinculadas ao paciente
 */

// Configurações básicas
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Incluir configurações
require_once APP_PATH . '/config/constants.php';
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/database.php';

// Configurar headers para JSON
header('Content-Type: application/json');

// Função de log para debug
function logDebug($message, $data = null) {
    $logFile = dirname(__DIR__) . '/logs/save_video_response_debug.log';
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}";
    if ($data !== null) {
        $logMessage .= "\n" . print_r($data, true);
    }
    $logMessage .= "\n" . str_repeat('-', 80) . "\n";
    
    @file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Verificar se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    exit;
}

try {
    // Pegar dados do POST
    $action = $_POST['action'] ?? '';
    logDebug('Iniciando save_video_response', ['action' => $action, 'POST' => $_POST]);
    
    if ($action !== 'save_response') {
        throw new Exception('Ação inválida');
    }
    
    $paciente_id = $_POST['paciente_id'] ?? null;
    $token = $_POST['token'] ?? null;
    $video_id = $_POST['video_id'] ?? '';
    $video_title = $_POST['video_title'] ?? '';
    $question_id = $_POST['question_id'] ?? '';
    $question_index = $_POST['question_index'] ?? 0;
    $question_text = $_POST['question_text'] ?? '';
    $answer = $_POST['answer'] ?? '';
    $type = $_POST['type'] ?? 'boolean';
    $timestamp = $_POST['timestamp'] ?? date('Y-m-d H:i:s');
    
    logDebug('Dados recebidos', [
        'paciente_id' => $paciente_id,
        'video_id' => $video_id,
        'video_title' => $video_title,
        'question_id' => $question_id,
        'question_index' => $question_index,
        'question_text' => substr($question_text, 0, 100) . '...',
        'answer' => is_array($answer) ? json_encode($answer) : substr($answer, 0, 100),
        'type' => $type
    ]);
    
    // Validações básicas
    if (!$paciente_id || !$token) {
        throw new Exception('Paciente ID ou token não informado');
    }
    
    $db = Database::getInstance();
    logDebug('Database instance obtida');
    
    // Verificar se o token é válido para este paciente
    $sql = "SELECT id FROM pacientes WHERE id = ? AND token_acesso = ?";
    $paciente = $db->fetch($sql, [$paciente_id, $token]);
    
    if (!$paciente) {
        logDebug('Token inválido ou paciente não encontrado', ['paciente_id' => $paciente_id]);
        throw new Exception('Token inválido ou paciente não encontrado');
    }
    
    logDebug('Token válido, paciente encontrado');
    
    // Verificar se a tabela existe, senão criar
    $table_exists = $db->fetch("SHOW TABLES LIKE 'paciente_video_respostas'");
    
    if (!$table_exists) {
        // Criar tabela se não existir
        $create_table_sql = "
            CREATE TABLE IF NOT EXISTS paciente_video_respostas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                paciente_id INT NOT NULL,
                video_id VARCHAR(50) NOT NULL,
                video_title VARCHAR(255),
                question_id VARCHAR(50),
                question_index INT,
                question_text TEXT,
                answer TEXT,
                answer_type VARCHAR(20),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_paciente (paciente_id),
                INDEX idx_video (video_id),
                FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        $db->query($create_table_sql);
    }
    
    // Verificar se já existe uma resposta para esta pergunta (evitar duplicatas)
    $sql = "SELECT id FROM paciente_video_respostas 
            WHERE paciente_id = ? AND video_id = ? AND question_index = ?";
    logDebug('Verificando resposta existente', [
        'sql' => $sql,
        'params' => [$paciente_id, $video_id, $question_index]
    ]);
    
    $existing = $db->fetch($sql, [$paciente_id, $video_id, $question_index]);
    logDebug('Resultado da verificação', ['existing' => $existing]);
    
    if ($existing) {
        // Atualizar resposta existente
        $sql = "UPDATE paciente_video_respostas 
                SET answer = ?, answer_type = ?, created_at = NOW()
                WHERE id = ?";
        
        logDebug('Atualizando resposta existente', [
            'sql' => $sql,
            'params' => [$answer, $type, $existing['id']]
        ]);
        
        try {
            $db->query($sql, [$answer, $type, $existing['id']]);
            logDebug('Resposta atualizada com sucesso');
        } catch (Exception $e) {
            logDebug('ERRO ao atualizar resposta', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
        
        $response = [
            'success' => true,
            'message' => 'Resposta atualizada com sucesso',
            'response_id' => $existing['id'],
            'action' => 'updated'
        ];
    } else {
        // Inserir nova resposta
        $sql = "INSERT INTO paciente_video_respostas 
                (paciente_id, video_id, video_title, question_id, question_index, 
                 question_text, answer, answer_type, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $params = [
            $paciente_id,
            $video_id,
            $video_title,
            $question_id,
            $question_index,
            $question_text,
            $answer,
            $type
        ];
        
        logDebug('Inserindo nova resposta', [
            'sql' => $sql,
            'params' => $params,
            'video_id' => $video_id
        ]);
        
        try {
            $db->query($sql, $params);
            $response_id = $db->lastInsertId();
            logDebug('Resposta inserida com sucesso', ['response_id' => $response_id]);
        } catch (Exception $e) {
            logDebug('ERRO ao inserir resposta', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'video_id' => $video_id
            ]);
            throw $e;
        }
        
        $response = [
            'success' => true,
            'message' => 'Resposta salva com sucesso',
            'response_id' => $response_id,
            'action' => 'inserted'
        ];
    }
    
    // Atualizar status do questionário do paciente se for a primeira resposta
    try {
        $pdo = $db->getConnection();
        
        // Verificar se é a primeira resposta do paciente
        $countSql = "SELECT COUNT(*) FROM paciente_video_respostas WHERE paciente_id = ?";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute([$paciente_id]);
        $totalRespostas = (int) $countStmt->fetchColumn();
        
        logDebug('Verificando total de respostas do paciente', [
            'paciente_id' => $paciente_id,
            'total_respostas' => $totalRespostas
        ]);
        
        // Se for a primeira resposta (total = 1), atualizar status para incompleto
        if ($totalRespostas === 1) {
            $updateStatusSql = "UPDATE pacientes 
                               SET status = ?, 
                                   questionario_status = 'incompleto',
                                   updated_at = NOW() 
                               WHERE id = ?";
            
            logDebug('Primeira resposta detectada - atualizando status para incompleto', [
                'paciente_id' => $paciente_id,
                'novo_status' => STATUS_QUESTIONARIO_INCOMPLETO
            ]);
            
            $db->query($updateStatusSql, [STATUS_QUESTIONARIO_INCOMPLETO, $paciente_id]);
            
            logDebug('Status do paciente atualizado para incompleto com sucesso');
        }
    } catch (Exception $e) {
        // Log do erro mas não interrompe o fluxo principal
        logDebug('ERRO ao atualizar status do paciente', [
            'error' => $e->getMessage(),
            'paciente_id' => $paciente_id
        ]);
    }
    
    logDebug('Resposta processada com sucesso', ['response' => $response]);
    echo json_encode($response);
    
} catch (PDOException $e) {
    logDebug('ERRO PDO em save_video_response', [
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
        'sqlState' => $e->errorInfo[0] ?? null,
        'driverCode' => $e->errorInfo[1] ?? null,
        'driverMessage' => $e->errorInfo[2] ?? null,
        'trace' => $e->getTraceAsString()
    ]);
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} catch (Exception $e) {
    logDebug('ERRO genérico em save_video_response', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
