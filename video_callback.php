<?php
/**
 * CALLBACK PARA SALVAR RESPOSTAS DO VÍDEO INTERATIVO
 * 
 * Este arquivo recebe as respostas do embed.php e salva no banco do NutriCheck
 * 
 * Endpoints:
 * - POST /video_callback.php?action=save_response
 * - POST /video_callback.php?action=complete_video
 * - GET  /video_callback.php?action=get_stats
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Configurações
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/database.php';

// Função para resposta JSON
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Função para erro JSON
function jsonError($message, $status = 400) {
    jsonResponse(['error' => $message, 'success' => false], $status);
}

// Roteamento
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    $db = Database::getInstance();
    
    switch ($action) {
        
        // ==========================================
        // SALVAR RESPOSTA DE PERGUNTA
        // ==========================================
        case 'save_response':
            if ($method !== 'POST') {
                jsonError('Método não permitido', 405);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $paciente_id = $data['paciente_id'] ?? null;
            $paciente_token = $data['paciente_token'] ?? null;
            $video_id = $data['video_id'] ?? null;
            $question_id = $data['question_id'] ?? null;
            $question_text = $data['question_text'] ?? '';
            $answer = $data['answer'] ?? '';
            $is_correct = $data['is_correct'] ?? false;
            $time_position = $data['time_position'] ?? 0;
            
            // Validações
            if (!$paciente_id || !$paciente_token || !$video_id || !$question_id) {
                jsonError('Dados incompletos');
            }
            
            // Verificar se paciente existe e token é válido
            $sql = "SELECT id, nome FROM pacientes WHERE id = ? AND token_acesso = ?";
            $paciente = $db->fetch($sql, [$paciente_id, $paciente_token]);
            
            if (!$paciente) {
                jsonError('Paciente não encontrado ou token inválido', 403);
            }
            
            // Verificar se já existe uma sessão ativa para este paciente/vídeo
            $sql = "SELECT id FROM video_sessoes 
                    WHERE paciente_id = ? AND video_id = ? AND status != 'concluida'
                    ORDER BY id DESC LIMIT 1";
            $sessao = $db->fetch($sql, [$paciente_id, $video_id]);
            
            // Se não existe sessão, criar uma
            if (!$sessao) {
                $session_token = bin2hex(random_bytes(16));
                $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
                $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
                
                $sql = "INSERT INTO video_sessoes 
                        (paciente_id, video_id, session_token, ip_address, user_agent, status, started_at)
                        VALUES (?, ?, ?, ?, ?, 'em_andamento', NOW())";
                $db->query($sql, [$paciente_id, $video_id, $session_token, $ip_address, $user_agent]);
                $sessao_id = $db->lastInsertId();
            } else {
                $sessao_id = $sessao['id'];
            }
            
            // Verificar se já respondeu esta pergunta nesta sessão
            $sql = "SELECT id FROM video_respostas 
                    WHERE sessao_id = ? AND pergunta_id = ?";
            $resposta_existente = $db->fetch($sql, [$sessao_id, $question_id]);
            
            if ($resposta_existente) {
                // Atualizar resposta existente
                $sql = "UPDATE video_respostas 
                        SET resposta = ?, correta = ?, tentativas = tentativas + 1, answered_at = NOW()
                        WHERE id = ?";
                $db->query($sql, [$answer, $is_correct ? 1 : 0, $resposta_existente['id']]);
            } else {
                // Inserir nova resposta
                // Primeiro, verificar se a pergunta existe na tabela video_perguntas
                $sql = "SELECT id FROM video_perguntas WHERE id = ?";
                $pergunta_existe = $db->fetch($sql, [$question_id]);
                
                if (!$pergunta_existe) {
                    // Criar pergunta se não existir
                    $sql = "INSERT INTO video_perguntas 
                            (id, video_id, texto_pergunta, tipo_pergunta, tempo_exibicao, created_at)
                            VALUES (?, ?, ?, 'multipla_escolha', ?, NOW())
                            ON DUPLICATE KEY UPDATE texto_pergunta = VALUES(texto_pergunta)";
                    $db->query($sql, [$question_id, $video_id, $question_text, $time_position]);
                }
                
                // Inserir resposta
                $sql = "INSERT INTO video_respostas 
                        (pergunta_id, sessao_id, paciente_id, resposta, correta, tempo_resposta, answered_at)
                        VALUES (?, ?, ?, ?, ?, ?, NOW())";
                $db->query($sql, [$question_id, $sessao_id, $paciente_id, $answer, $is_correct ? 1 : 0, $time_position]);
            }
            
            jsonResponse([
                'success' => true,
                'message' => 'Resposta salva com sucesso',
                'sessao_id' => $sessao_id
            ]);
            break;
            
        // ==========================================
        // MARCAR VÍDEO COMO CONCLUÍDO
        // ==========================================
        case 'complete_video':
            if ($method !== 'POST') {
                jsonError('Método não permitido', 405);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $paciente_id = $data['paciente_id'] ?? null;
            $paciente_token = $data['paciente_token'] ?? null;
            $video_id = $data['video_id'] ?? null;
            $total_time = $data['total_time'] ?? 0;
            $completion_percentage = $data['completion_percentage'] ?? 100;
            
            if (!$paciente_id || !$paciente_token || !$video_id) {
                jsonError('Dados incompletos');
            }
            
            // Verificar paciente
            $sql = "SELECT id FROM pacientes WHERE id = ? AND token_acesso = ?";
            $paciente = $db->fetch($sql, [$paciente_id, $paciente_token]);
            
            if (!$paciente) {
                jsonError('Paciente não encontrado ou token inválido', 403);
            }
            
            // Buscar sessão ativa
            $sql = "SELECT id FROM video_sessoes 
                    WHERE paciente_id = ? AND video_id = ? AND status != 'concluida'
                    ORDER BY id DESC LIMIT 1";
            $sessao = $db->fetch($sql, [$paciente_id, $video_id]);
            
            if ($sessao) {
                // Atualizar sessão
                $sql = "UPDATE video_sessoes 
                        SET status = 'concluida',
                            completed_at = NOW(),
                            tempo_total_assistido = ?,
                            percentual_conclusao = ?,
                            updated_at = NOW()
                        WHERE id = ?";
                $db->query($sql, [$total_time, $completion_percentage, $sessao['id']]);
                
                // Atualizar estatísticas
                $sql = "INSERT INTO video_estatisticas 
                        (paciente_id, video_id, video_concluido, data_ultima_visualizacao)
                        VALUES (?, ?, 1, NOW())
                        ON DUPLICATE KEY UPDATE
                        video_concluido = 1,
                        data_ultima_visualizacao = NOW()";
                $db->query($sql, [$paciente_id, $video_id]);
            }
            
            jsonResponse([
                'success' => true,
                'message' => 'Vídeo marcado como concluído'
            ]);
            break;
            
        // ==========================================
        // OBTER ESTATÍSTICAS
        // ==========================================
        case 'get_stats':
            if ($method !== 'GET') {
                jsonError('Método não permitido', 405);
            }
            
            $paciente_id = $_GET['paciente_id'] ?? null;
            $video_id = $_GET['video_id'] ?? null;
            
            if (!$paciente_id || !$video_id) {
                jsonError('Parâmetros incompletos');
            }
            
            // Buscar estatísticas
            $sql = "SELECT 
                        COUNT(vr.id) as total_respostas,
                        SUM(CASE WHEN vr.correta = 1 THEN 1 ELSE 0 END) as respostas_corretas,
                        vs.percentual_conclusao,
                        vs.status
                    FROM video_sessoes vs
                    LEFT JOIN video_respostas vr ON vs.id = vr.sessao_id
                    WHERE vs.paciente_id = ? AND vs.video_id = ?
                    GROUP BY vs.id, vs.percentual_conclusao, vs.status
                    ORDER BY vs.id DESC
                    LIMIT 1";
            
            $stats = $db->fetch($sql, [$paciente_id, $video_id]);
            
            if (!$stats) {
                $stats = [
                    'total_respostas' => 0,
                    'respostas_corretas' => 0,
                    'percentual_conclusao' => 0,
                    'status' => 'nao_iniciado'
                ];
            }
            
            // Calcular percentual de acerto
            $percentual_acerto = 0;
            if ($stats['total_respostas'] > 0) {
                $percentual_acerto = round(($stats['respostas_corretas'] / $stats['total_respostas']) * 100, 2);
            }
            
            jsonResponse([
                'success' => true,
                'stats' => [
                    'total_respostas' => (int)$stats['total_respostas'],
                    'respostas_corretas' => (int)$stats['respostas_corretas'],
                    'percentual_acerto' => $percentual_acerto,
                    'percentual_conclusao' => (float)$stats['percentual_conclusao'],
                    'status' => $stats['status']
                ]
            ]);
            break;
            
        // ==========================================
        // AÇÃO INVÁLIDA
        // ==========================================
        default:
            jsonError('Ação não reconhecida', 404);
    }
    
} catch (Exception $e) {
    error_log("Erro em video_callback.php: " . $e->getMessage());
    jsonError('Erro no servidor: ' . $e->getMessage(), 500);
}

