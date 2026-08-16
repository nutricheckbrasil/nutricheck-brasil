<?php
/**
 * AJAX - Finalizar Entrevista em Vídeo
 * 
 * Marca a entrevista como concluída e atualiza o status do paciente
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
    $logFile = dirname(__DIR__) . '/logs/finish_video_debug.log';
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
    logDebug('Iniciando finish_video_interview', ['action' => $action, 'POST' => $_POST]);
    
    if ($action !== 'finish_interview') {
        throw new Exception('Ação inválida');
    }
    
    $paciente_id = $_POST['paciente_id'] ?? null;
    $token = $_POST['token'] ?? null;
    $total_responses = $_POST['total_responses'] ?? 0;
    
    logDebug('Dados recebidos', [
        'paciente_id' => $paciente_id,
        'token' => substr($token ?? '', 0, 10) . '...',
        'total_responses' => $total_responses
    ]);
    
    // Validações básicas
    if (!$paciente_id || !$token) {
        throw new Exception('Paciente ID ou token não informado');
    }
    
    $db = Database::getInstance();
    logDebug('Database instance obtida');
    
    // Verificar se o token é válido para este paciente
    $sql = "SELECT id, status FROM pacientes WHERE id = ? AND token_acesso = ?";
    $paciente = $db->fetch($sql, [$paciente_id, $token]);
    
    if (!$paciente) {
        throw new Exception('Token inválido ou paciente não encontrado');
    }
    
    // Calcular estatísticas das respostas para validar conclusão
    try {
        logDebug('Iniciando calcularEstatisticasQuestionario', ['paciente_id' => $paciente_id]);
        $questionarioStats = calcularEstatisticasQuestionario($db, (int) $paciente_id);
        logDebug('Estatísticas calculadas com sucesso', $questionarioStats);
    } catch (Exception $e) {
        logDebug('ERRO ao calcular estatísticas', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        throw new Exception('Erro ao calcular estatísticas: ' . $e->getMessage());
    }
    
    try {
        logDebug('Iniciando salvarEstatisticasPaciente', ['paciente_id' => $paciente_id]);
        salvarEstatisticasPaciente($db, (int) $paciente_id, $questionarioStats);
        logDebug('Estatísticas salvas com sucesso');
    } catch (Exception $e) {
        logDebug('ERRO ao salvar estatísticas', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        throw new Exception('Erro ao salvar estatísticas: ' . $e->getMessage());
    }
    
    try {
        logDebug('Iniciando atualizarPacienteComEstatistica', ['paciente_id' => $paciente_id]);
        atualizarPacienteComEstatistica($db, (int) $paciente_id, $questionarioStats);
        logDebug('Paciente atualizado com sucesso');
    } catch (Exception $e) {
        logDebug('ERRO ao atualizar paciente', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        throw new Exception('Erro ao atualizar paciente: ' . $e->getMessage());
    }
    
    $questionarioCompleto = $questionarioStats['status'] === 'completo';
    logDebug('Status do questionário', ['completo' => $questionarioCompleto, 'status' => $questionarioStats['status']]);
    
    // Atualizar status principal do paciente
    $novoStatusPaciente = $questionarioCompleto
        ? STATUS_QUESTIONARIO_RESPONDIDO
        : STATUS_QUESTIONARIO_INCOMPLETO;
    
    logDebug('Atualizando status principal do paciente', [
        'paciente_id' => $paciente_id,
        'novo_status' => $novoStatusPaciente,
        'questionario_completo' => $questionarioCompleto
    ]);
    
    $sql = "UPDATE pacientes 
            SET status = ?,
                updated_at = NOW()
            WHERE id = ?";
    $db->query($sql, [$novoStatusPaciente, $paciente_id]);
    
    logDebug('Status principal do paciente atualizado com sucesso');
    
    // Registrar log de conclusão
    $descricao = "Paciente concluiu a entrevista em vídeo com {$total_responses} respostas registradas";
    
    // Verificar se a tabela de logs existe
    try {
        $pdo = $db->getConnection();
        $stmt = $pdo->query("SHOW TABLES LIKE 'logs_atividades'");
        $table_exists = $stmt->fetch();
        if ($table_exists) {
            $sql = "INSERT INTO logs_atividades 
                    (tipo, descricao, paciente_id, created_at) 
                    VALUES ('video_concluido', :descricao, :paciente_id, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':descricao' => $descricao,
                ':paciente_id' => $paciente_id
            ]);
        }
    } catch (Exception $e) {
        // Ignorar erro se a tabela não existir
    }
    
    $mensagemFinal = $questionarioCompleto
        ? 'Entrevista finalizada com sucesso'
        : 'Entrevista finalizada, porém há vídeos pendentes';
    
    echo json_encode([
        'success' => true,
        'message' => $mensagemFinal,
        'paciente_id' => $paciente_id,
        'total_responses' => $total_responses,
        'questionario' => $questionarioStats
    ]);
    
} catch (PDOException $e) {
    logDebug('ERRO PDO no catch principal', [
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
        'error' => $e->getMessage(),
        'debug' => [
            'sqlState' => $e->errorInfo[0] ?? null,
            'driverCode' => $e->errorInfo[1] ?? null,
            'driverMessage' => $e->errorInfo[2] ?? null,
        ]
    ]);
} catch (Exception $e) {
    logDebug('ERRO genérico no catch principal', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

/**
 * Carrega o mapa de vídeos esperados a partir da definição do questionário.
 */
function carregarDefinicoesVideos(): array {
    static $videos = null;
    
    if ($videos !== null) {
        return $videos;
    }
    
    $videos = [];
    $path = APP_PATH . '/config/questionario.php';
    
    if (file_exists($path)) {
        $data = require $path;
        $ordem = 1;
        foreach ($data as $videoId => $info) {
            $videos[$videoId] = [
                'id' => $videoId,
                'title' => $info['title'] ?? strtoupper($videoId),
                'order' => $ordem++
            ];
        }
    }
    
    return $videos;
}


function calcularEstatisticasQuestionario(Database $db, int $pacienteId): array {
    logDebug('calcularEstatisticasQuestionario - INÍCIO', ['pacienteId' => $pacienteId]);
    
    $pdo = $db->getConnection();
    $videosEsperados = carregarDefinicoesVideos();
    $totalVideos = count($videosEsperados);
    
    logDebug('Vídeos esperados carregados', ['total' => $totalVideos, 'videos' => array_keys($videosEsperados)]);
    
    if ($totalVideos === 0) {
        logDebug('Nenhum vídeo esperado encontrado');
        return [
            'status' => 'nao_iniciado',
            'total_videos' => 0,
            'videos_respondidos' => 0,
            'percentual' => 0,
            'ultimo_video_id' => null,
            'ultimo_video_titulo' => null,
            'data_primeira_resposta' => null,
            'data_ultima_resposta' => null,
            'videos_pendentes' => [],
            'videos_pendentes_ids' => [],
        ];
    }
    
    // Verificar quantos registros existem para este paciente
    $checkSql = "SELECT COUNT(*) as total FROM paciente_video_respostas WHERE paciente_id = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$pacienteId]);
    $totalRegistros = (int) $checkStmt->fetchColumn();
    logDebug('Total de registros na tabela', ['pacienteId' => $pacienteId, 'total_registros' => $totalRegistros]);
    
    // Se não há nenhum registro, retornar "nao_iniciado"
    if ($totalRegistros === 0) {
        logDebug('Nenhum registro encontrado - retornando nao_iniciado');
        $videosPendentes = array_map(function ($video) {
            return [
                'id' => $video['id'],
                'title' => $video['title'],
            ];
        }, array_values($videosEsperados));
        
        return [
            'status' => 'nao_iniciado',
            'total_videos' => $totalVideos,
            'videos_respondidos' => 0,
            'percentual' => 0,
            'ultimo_video_id' => null,
            'ultimo_video_titulo' => null,
            'data_primeira_resposta' => null,
            'data_ultima_resposta' => null,
            'videos_pendentes' => $videosPendentes,
            'videos_pendentes_ids' => array_column($videosPendentes, 'id'),
        ];
    }
    
    // Verificar alguns registros para debug
    $sampleSql = "SELECT video_id, COUNT(*) as qtd FROM paciente_video_respostas WHERE paciente_id = ? GROUP BY video_id LIMIT 5";
    $sampleStmt = $pdo->prepare($sampleSql);
    $sampleStmt->execute([$pacienteId]);
    $sampleRows = $sampleStmt->fetchAll(PDO::FETCH_ASSOC);
    logDebug('Amostra de registros encontrados', ['amostra' => $sampleRows]);
    
    // Verificar especificamente o video_2
    $checkVideo2Sql = "SELECT video_id, question_id, question_text, answer, created_at FROM paciente_video_respostas WHERE paciente_id = ? AND (video_id LIKE '%video_2%' OR video_id LIKE '%2%' OR question_text LIKE '%Histórico Clínico%') ORDER BY created_at";
    $checkVideo2Stmt = $pdo->prepare($checkVideo2Sql);
    $checkVideo2Stmt->execute([$pacienteId]);
    $video2Rows = $checkVideo2Stmt->fetchAll(PDO::FETCH_ASSOC);
    logDebug('Verificação específica do video_2', ['registros' => $video2Rows]);
    
    // Listar TODOS os video_id únicos para debug
    $allVideosSql = "SELECT DISTINCT video_id FROM paciente_video_respostas WHERE paciente_id = ? ORDER BY video_id";
    $allVideosStmt = $pdo->prepare($allVideosSql);
    $allVideosStmt->execute([$pacienteId]);
    $allVideos = $allVideosStmt->fetchAll(PDO::FETCH_COLUMN);
    logDebug('Todos os video_id únicos encontrados', ['videos' => $allVideos]);
    
    // Query principal para buscar respostas agrupadas por vídeo
    $sql = "SELECT video_id, COUNT(*) AS total_respostas, 
                   MIN(created_at) AS primeira_resposta, 
                   MAX(created_at) AS ultima_resposta
            FROM paciente_video_respostas
            WHERE paciente_id = :paciente_id
            GROUP BY video_id";
    
    logDebug('Executando query principal', ['sql' => $sql, 'pacienteId' => $pacienteId]);
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':paciente_id' => $pacienteId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    logDebug('Resultado da query', ['total_rows' => count($rows), 'rows' => $rows]);
    
    $videosRespondidos = 0;
    $primeiraRespostaGlobal = null;
    $ultimaRespostaGlobal = null;
    $ultimoVideoInfo = null;
    $videosComResposta = [];
    
    logDebug('Processando rows da query', ['total_rows_processar' => count($rows)]);
    
    foreach ($rows as $row) {
        $videoId = $row['video_id'];
        logDebug('Processando row', ['video_id' => $videoId, 'row' => $row]);
        
        if (!isset($videosEsperados[$videoId])) {
            logDebug('Video ID não está nos vídeos esperados', ['video_id' => $videoId]);
            continue;
        }
        
        $videosComResposta[$videoId] = true;
        $videosRespondidos++;
        
        $primeiraResp = $row['primeira_resposta'];
        $ultimaResp = $row['ultima_resposta'];
        
        if ($primeiraResp && ($primeiraRespostaGlobal === null || $primeiraResp < $primeiraRespostaGlobal)) {
            $primeiraRespostaGlobal = $primeiraResp;
        }
        
        if ($ultimaResp && ($ultimaRespostaGlobal === null || $ultimaResp > $ultimaRespostaGlobal)) {
            $ultimaRespostaGlobal = $ultimaResp;
            $ultimoVideoInfo = [
                'id' => $videoId,
                'title' => $videosEsperados[$videoId]['title'],
            ];
        }
    }
    
    logDebug('Vídeos com resposta processados', [
        'videos_respondidos' => $videosRespondidos,
        'videos_com_resposta_ids' => array_keys($videosComResposta),
        'total_registros' => $totalRegistros
    ]);
    
    $videosPendentesIds = array_values(array_diff(array_keys($videosEsperados), array_keys($videosComResposta)));
    $videosPendentes = array_map(function ($videoId) use ($videosEsperados) {
        return [
            'id' => $videoId,
            'title' => $videosEsperados[$videoId]['title'],
        ];
    }, $videosPendentesIds);
    
    // Lógica de status:
    // - Se há pelo menos uma resposta na tabela (totalRegistros > 0), o status mínimo é "incompleto"
    // - Se não há nenhuma resposta, é "nao_iniciado"
    // - Se todos os vídeos foram respondidos, é "completo"
    if ($totalRegistros === 0) {
        $status = 'nao_iniciado';
    } elseif ($videosRespondidos >= $totalVideos) {
        $status = 'completo';
    } else {
        // Se há respostas mas não completou todos os vídeos, é "incompleto"
        // Mesmo que videosRespondidos seja 0 (por algum problema de mapeamento), 
        // se totalRegistros > 0, significa que iniciou o questionário
        $status = 'incompleto';
    }
    
    $percentual = $totalVideos > 0
        ? round(($videosRespondidos / $totalVideos) * 100, 2)
        : 0;
    
    $resultado = [
        'status' => $status,
        'total_videos' => $totalVideos,
        'videos_respondidos' => $videosRespondidos,
        'percentual' => $percentual,
        'ultimo_video_id' => $ultimoVideoInfo['id'] ?? null,
        'ultimo_video_titulo' => $ultimoVideoInfo['title'] ?? null,
        'data_primeira_resposta' => $primeiraRespostaGlobal,
        'data_ultima_resposta' => $ultimaRespostaGlobal,
        'videos_pendentes' => $videosPendentes,
        'videos_pendentes_ids' => $videosPendentesIds,
    ];
    
    logDebug('calcularEstatisticasQuestionario - RESULTADO FINAL', $resultado);
    
    return $resultado;
}

function salvarEstatisticasPaciente(Database $db, int $pacienteId, array $stats): void {
    logDebug('salvarEstatisticasPaciente - INÍCIO', ['pacienteId' => $pacienteId, 'stats' => $stats]);
    
    try {
        $pdo = $db->getConnection();
        logDebug('PDO connection obtida');
        
        $videosPendentesJson = json_encode($stats['videos_pendentes'], JSON_UNESCAPED_UNICODE);
        logDebug('JSON de videos_pendentes criado', ['json' => $videosPendentesJson]);
        
        // Verificar se já existe registro
        $checkSql = "SELECT id FROM paciente_video_estatisticas WHERE paciente_id = ?";
        logDebug('Verificando se registro existe', ['sql' => $checkSql, 'pacienteId' => $pacienteId]);
        
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$pacienteId]);
        $exists = $checkStmt->fetch();
        
        logDebug('Resultado da verificação', ['exists' => (bool)$exists]);
        
        if ($exists) {
            // UPDATE
            $sql = "UPDATE paciente_video_estatisticas 
                    SET total_videos = ?,
                        videos_respondidos = ?,
                        percentual_conclusao = ?,
                        status = ?,
                        ultimo_video_id = ?,
                        ultimo_video_titulo = ?,
                        data_primeira_resposta = ?,
                        data_ultima_resposta = ?,
                        videos_pendentes = ?,
                        updated_at = NOW()
                    WHERE paciente_id = ?";
            
            $params = [
                $stats['total_videos'],
                $stats['videos_respondidos'],
                $stats['percentual'],
                $stats['status'],
                $stats['ultimo_video_id'],
                $stats['ultimo_video_titulo'],
                $stats['data_primeira_resposta'],
                $stats['data_ultima_resposta'],
                $videosPendentesJson,
                $pacienteId
            ];
            
            logDebug('Executando UPDATE', ['sql' => $sql, 'params' => $params]);
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            logDebug('UPDATE executado com sucesso');
        } else {
            // INSERT
            $sql = "INSERT INTO paciente_video_estatisticas (
                        paciente_id,
                        total_videos,
                        videos_respondidos,
                        percentual_conclusao,
                        status,
                        ultimo_video_id,
                        ultimo_video_titulo,
                        data_primeira_resposta,
                        data_ultima_resposta,
                        videos_pendentes,
                        created_at,
                        updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            
            $params = [
                $pacienteId,
                $stats['total_videos'],
                $stats['videos_respondidos'],
                $stats['percentual'],
                $stats['status'],
                $stats['ultimo_video_id'],
                $stats['ultimo_video_titulo'],
                $stats['data_primeira_resposta'],
                $stats['data_ultima_resposta'],
                $videosPendentesJson
            ];
            
            logDebug('Executando INSERT', ['sql' => $sql, 'params' => $params]);
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            logDebug('INSERT executado com sucesso');
        }
    } catch (PDOException $e) {
        logDebug('ERRO PDO em salvarEstatisticasPaciente', [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'sqlState' => $e->errorInfo[0] ?? null,
            'driverCode' => $e->errorInfo[1] ?? null,
            'driverMessage' => $e->errorInfo[2] ?? null,
            'trace' => $e->getTraceAsString()
        ]);
        throw $e;
    } catch (Exception $e) {
        logDebug('ERRO genérico em salvarEstatisticasPaciente', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        throw $e;
    }
    
    logDebug('salvarEstatisticasPaciente - FIM');
}


function atualizarPacienteComEstatistica(Database $db, int $pacienteId, array $stats): void {
    logDebug('atualizarPacienteComEstatistica - INÍCIO', ['pacienteId' => $pacienteId, 'stats' => $stats]);
    
    try {
        $pdo = $db->getConnection();
        
        $sql = "UPDATE pacientes 
                SET questionario_status = :status,
                    questionario_percentual = :percentual,
                    questionario_videos_respondidos = :respondidos,
                    questionario_total_videos = :total,
                    questionario_ultimo_video = :ultimo_video,
                    questionario_atualizado_em = NOW()
                WHERE id = :paciente_id";
        
        $params = [
            ':status' => $stats['status'],
            ':percentual' => (int) round($stats['percentual']),
            ':respondidos' => $stats['videos_respondidos'],
            ':total' => $stats['total_videos'],
            ':ultimo_video' => $stats['ultimo_video_titulo'] ?: $stats['ultimo_video_id'],
            ':paciente_id' => $pacienteId,
        ];
        
        logDebug('Executando UPDATE pacientes', ['sql' => $sql, 'params' => $params]);
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        logDebug('UPDATE pacientes executado com sucesso');
    } catch (PDOException $e) {
        logDebug('ERRO PDO em atualizarPacienteComEstatistica', [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'sqlState' => $e->errorInfo[0] ?? null,
            'driverCode' => $e->errorInfo[1] ?? null,
            'driverMessage' => $e->errorInfo[2] ?? null,
            'trace' => $e->getTraceAsString()
        ]);
        throw $e;
    } catch (Exception $e) {
        logDebug('ERRO genérico em atualizarPacienteComEstatistica', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        throw $e;
    }
    
    logDebug('atualizarPacienteComEstatistica - FIM');
}

