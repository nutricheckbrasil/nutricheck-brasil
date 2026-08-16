<?php
/**
 * API para Sistema de Vídeo Interativo
 * Anestesia Check - Versão MySQL
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
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
        // LISTAR VÍDEOS ATIVOS
        // ==========================================
        case 'list_videos':
            if ($method !== 'GET') {
                jsonError('Método não permitido', 405);
            }
            
            $sql = "SELECT v.*, 
                           COUNT(DISTINCT vp.id) as total_perguntas,
                           COUNT(DISTINCT vs.id) as total_visualizacoes
                    FROM videos_interativos v
                    LEFT JOIN video_perguntas vp ON v.id = vp.video_id
                    LEFT JOIN video_sessoes vs ON v.id = vs.video_id
                    WHERE v.ativo = 1
                    GROUP BY v.id
                    ORDER BY v.created_at DESC";
            
            $videos = $db->query($sql);
            jsonResponse(['success' => true, 'videos' => $videos]);
            break;
            
        // ==========================================
        // OBTER VÍDEO ESPECÍFICO
        // ==========================================
        case 'get_video':
            if ($method !== 'GET') {
                jsonError('Método não permitido', 405);
            }
            
            $videoId = $_GET['video_id'] ?? null;
            if (!$videoId) {
                jsonError('ID do vídeo não fornecido');
            }
            
            $sql = "SELECT * FROM videos_interativos WHERE id = ? AND ativo = 1";
            $video = $db->fetch($sql, [$videoId]);
            
            if (!$video) {
                jsonError('Vídeo não encontrado', 404);
            }
            
            jsonResponse(['success' => true, 'video' => $video]);
            break;
            
        // ==========================================
        // OBTER PERGUNTAS DO VÍDEO
        // ==========================================
        case 'get_questions':
            if ($method !== 'GET') {
                jsonError('Método não permitido', 405);
            }
            
            $videoId = $_GET['video_id'] ?? null;
            if (!$videoId) {
                jsonError('ID do vídeo não fornecido');
            }
            
            $sql = "SELECT id, texto_pergunta, tipo_pergunta, opcoes, tempo_exibicao, obrigatoria, pontuacao, ordem
                    FROM video_perguntas
                    WHERE video_id = ?
                    ORDER BY tempo_exibicao ASC, ordem ASC";
            
            $perguntas = $db->query($sql, [$videoId]);
            
            // Decodificar JSON das opções
            foreach ($perguntas as &$pergunta) {
                if ($pergunta['opcoes']) {
                    $pergunta['opcoes'] = json_decode($pergunta['opcoes'], true);
                }
            }
            
            jsonResponse(['success' => true, 'perguntas' => $perguntas]);
            break;
            
        // ==========================================
        // CRIAR SESSÃO DE VISUALIZAÇÃO
        // ==========================================
        case 'create_session':
            if ($method !== 'POST') {
                jsonError('Método não permitido', 405);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $pacienteId = $data['paciente_id'] ?? null;
            $videoId = $data['video_id'] ?? null;
            
            if (!$pacienteId || !$videoId) {
                jsonError('Dados incompletos: paciente_id e video_id são obrigatórios');
            }
            
            // Verificar se paciente existe
            $paciente = $db->fetch("SELECT id FROM pacientes WHERE id = ?", [$pacienteId]);
            if (!$paciente) {
                jsonError('Paciente não encontrado', 404);
            }
            
            // Verificar se vídeo existe e está ativo
            $video = $db->fetch("SELECT id FROM videos_interativos WHERE id = ? AND ativo = 1", [$videoId]);
            if (!$video) {
                jsonError('Vídeo não encontrado ou inativo', 404);
            }
            
            // Gerar token único
            $sessionToken = bin2hex(random_bytes(32));
            
            // Capturar informações do cliente
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
            $deviceType = 'unknown';
            
            // Detectar tipo de dispositivo
            if ($userAgent) {
                if (preg_match('/mobile/i', $userAgent)) {
                    $deviceType = 'mobile';
                } elseif (preg_match('/tablet/i', $userAgent)) {
                    $deviceType = 'tablet';
                } else {
                    $deviceType = 'desktop';
                }
            }
            
            // Inserir sessão
            $sql = "INSERT INTO video_sessoes 
                    (paciente_id, video_id, session_token, ip_address, user_agent, device_type, status)
                    VALUES (?, ?, ?, ?, ?, ?, 'iniciada')";
            
            $db->query($sql, [$pacienteId, $videoId, $sessionToken, $ipAddress, $userAgent, $deviceType]);
            $sessionId = $db->lastInsertId();
            
            // Atualizar estatísticas
            $sql = "INSERT INTO video_estatisticas 
                    (paciente_id, video_id, total_visualizacoes, data_primeira_visualizacao, data_ultima_visualizacao)
                    VALUES (?, ?, 1, NOW(), NOW())
                    ON DUPLICATE KEY UPDATE
                    total_visualizacoes = total_visualizacoes + 1,
                    data_ultima_visualizacao = NOW()";
            $db->query($sql, [$pacienteId, $videoId]);
            
            jsonResponse([
                'success' => true,
                'session_id' => $sessionId,
                'session_token' => $sessionToken,
                'message' => 'Sessão criada com sucesso'
            ]);
            break;
            
        // ==========================================
        // ATUALIZAR PROGRESSO DA SESSÃO
        // ==========================================
        case 'update_progress':
            if ($method !== 'POST') {
                jsonError('Método não permitido', 405);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $sessionId = $data['session_id'] ?? null;
            $posicao = $data['posicao'] ?? 0;
            $tempoAssistido = $data['tempo_assistido'] ?? 0;
            $percentual = $data['percentual'] ?? 0;
            
            if (!$sessionId) {
                jsonError('ID da sessão não fornecido');
            }
            
            $sql = "UPDATE video_sessoes 
                    SET ultima_posicao = ?,
                        tempo_total_assistido = ?,
                        percentual_conclusao = ?,
                        status = 'em_andamento',
                        updated_at = NOW()
                    WHERE id = ?";
            
            $db->query($sql, [$posicao, $tempoAssistido, $percentual, $sessionId]);
            
            jsonResponse(['success' => true, 'message' => 'Progresso atualizado']);
            break;
            
        // ==========================================
        // ENVIAR RESPOSTA
        // ==========================================
        case 'submit_answer':
            if ($method !== 'POST') {
                jsonError('Método não permitido', 405);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $perguntaId = $data['pergunta_id'] ?? null;
            $sessaoId = $data['sessao_id'] ?? null;
            $resposta = $data['resposta'] ?? null;
            $tempoResposta = $data['tempo_resposta'] ?? null;
            
            if (!$perguntaId || !$sessaoId || $resposta === null) {
                jsonError('Dados incompletos');
            }
            
            // Buscar pergunta
            $sql = "SELECT * FROM video_perguntas WHERE id = ?";
            $pergunta = $db->fetch($sql, [$perguntaId]);
            
            if (!$pergunta) {
                jsonError('Pergunta não encontrada', 404);
            }
            
            // Buscar sessão para pegar paciente_id
            $sql = "SELECT paciente_id FROM video_sessoes WHERE id = ?";
            $sessao = $db->fetch($sql, [$sessaoId]);
            
            if (!$sessao) {
                jsonError('Sessão não encontrada', 404);
            }
            
            // Verificar se resposta está correta
            $correta = false;
            $respostaUsuario = trim($resposta);
            $respostaCorreta = trim($pergunta['resposta_correta'] ?? '');
            
            if ($pergunta['tipo_pergunta'] === 'texto_livre') {
                // Para texto livre, aceita qualquer resposta com mais de 5 caracteres
                $correta = strlen($respostaUsuario) > 5;
            } else {
                // Para múltipla escolha e verdadeiro/falso
                $correta = (strcasecmp($respostaUsuario, $respostaCorreta) === 0);
            }
            
            // Verificar se já existe resposta para esta pergunta nesta sessão
            $sql = "SELECT id, tentativas FROM video_respostas 
                    WHERE pergunta_id = ? AND sessao_id = ?";
            $respostaExistente = $db->fetch($sql, [$perguntaId, $sessaoId]);
            
            if ($respostaExistente) {
                // Atualizar resposta existente
                $sql = "UPDATE video_respostas 
                        SET resposta = ?,
                            correta = ?,
                            tempo_resposta = ?,
                            tentativas = tentativas + 1,
                            answered_at = NOW()
                        WHERE id = ?";
                $db->query($sql, [$respostaUsuario, $correta ? 1 : 0, $tempoResposta, $respostaExistente['id']]);
            } else {
                // Inserir nova resposta
                $sql = "INSERT INTO video_respostas 
                        (pergunta_id, sessao_id, paciente_id, resposta, correta, tempo_resposta)
                        VALUES (?, ?, ?, ?, ?, ?)";
                $db->query($sql, [$perguntaId, $sessaoId, $sessao['paciente_id'], $respostaUsuario, $correta ? 1 : 0, $tempoResposta]);
            }
            
            jsonResponse([
                'success' => true,
                'correta' => $correta,
                'resposta_correta' => $pergunta['tipo_pergunta'] !== 'texto_livre' ? $respostaCorreta : null,
                'explicacao' => $pergunta['explicacao'] ?? null,
                'message' => $correta ? 'Resposta correta!' : 'Resposta incorreta'
            ]);
            break;
            
        // ==========================================
        // CONCLUIR SESSÃO
        // ==========================================
        case 'complete_session':
            if ($method !== 'POST') {
                jsonError('Método não permitido', 405);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $sessionId = $data['session_id'] ?? null;
            
            if (!$sessionId) {
                jsonError('ID da sessão não fornecido');
            }
            
            // Atualizar sessão
            $sql = "UPDATE video_sessoes 
                    SET status = 'concluida',
                        completed_at = NOW(),
                        percentual_conclusao = 100,
                        updated_at = NOW()
                    WHERE id = ?";
            
            $db->query($sql, [$sessionId]);
            
            // Buscar estatísticas da sessão
            $sql = "SELECT 
                        vs.paciente_id,
                        vs.video_id,
                        COUNT(vr.id) as total_respostas,
                        SUM(CASE WHEN vr.correta = 1 THEN 1 ELSE 0 END) as respostas_corretas
                    FROM video_sessoes vs
                    LEFT JOIN video_respostas vr ON vs.id = vr.sessao_id
                    WHERE vs.id = ?
                    GROUP BY vs.paciente_id, vs.video_id";
            
            $stats = $db->fetch($sql, [$sessionId]);
            
            if ($stats) {
                $percentualAcerto = $stats['total_respostas'] > 0 
                    ? ($stats['respostas_corretas'] * 100.0 / $stats['total_respostas']) 
                    : 0;
                
                // Atualizar estatísticas
                $sql = "UPDATE video_estatisticas 
                        SET video_concluido = 1,
                            percentual_acerto = ?
                        WHERE paciente_id = ? AND video_id = ?";
                $db->query($sql, [$percentualAcerto, $stats['paciente_id'], $stats['video_id']]);
            }
            
            jsonResponse([
                'success' => true,
                'message' => 'Sessão concluída com sucesso',
                'estatisticas' => $stats
            ]);
            break;
            
        // ==========================================
        // OBTER ESTATÍSTICAS DO PACIENTE
        // ==========================================
        case 'get_patient_stats':
            if ($method !== 'GET') {
                jsonError('Método não permitido', 405);
            }
            
            $pacienteId = $_GET['paciente_id'] ?? null;
            $videoId = $_GET['video_id'] ?? null;
            
            if (!$pacienteId) {
                jsonError('ID do paciente não fornecido');
            }
            
            if ($videoId) {
                // Estatísticas de um vídeo específico
                $sql = "SELECT * FROM video_estatisticas 
                        WHERE paciente_id = ? AND video_id = ?";
                $stats = $db->fetch($sql, [$pacienteId, $videoId]);
            } else {
                // Estatísticas de todos os vídeos do paciente
                $sql = "SELECT ve.*, vi.titulo as video_titulo
                        FROM video_estatisticas ve
                        JOIN videos_interativos vi ON ve.video_id = vi.id
                        WHERE ve.paciente_id = ?
                        ORDER BY ve.data_ultima_visualizacao DESC";
                $stats = $db->query($sql, [$pacienteId]);
            }
            
            jsonResponse(['success' => true, 'estatisticas' => $stats]);
            break;
            
        // ==========================================
        // AÇÃO INVÁLIDA
        // ==========================================
        default:
            jsonError('Ação não reconhecida', 404);
    }
    
} catch (Exception $e) {
    jsonError('Erro no servidor: ' . $e->getMessage(), 500);
}

