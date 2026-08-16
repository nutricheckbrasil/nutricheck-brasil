<?php
/**
 * Geração de Relatório Pré-Operatório (Nutrição) em PDF
 * 
 * Este arquivo gera um relatório completo em PDF com as respostas do paciente
 * para o nutricionista, incluindo capa inicial, conteúdo organizado e capa final.
 */

// Iniciar buffer de output para evitar que warnings quebrem o PDF
ob_start();

// Suprimir warnings durante a geração do PDF (mas manter erros fatais)
$oldErrorReporting = error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
$oldDisplayErrors = ini_set('display_errors', '0');

require_once __DIR__ . '/../app/config/constants.php';
require_once __DIR__ . '/../app/config/database.php';

$composerAutoload = __DIR__ . '/../vendor/autoload.php';
$composerAvailable = false;

if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
    $composerAvailable = true;
}

if (!defined('PROJECT_LOGO_FILE')) {
    define('PROJECT_LOGO_FILE', PUBLIC_PATH . '/assets/img/logo-nutricheck.svg');
}
if (!defined('PROJECT_LOGO_URL')) {
    define('PROJECT_LOGO_URL', BASE_URL . '/assets/img/logo-nutricheck.svg');
}
if (!defined('PROJECT_LOGO_VIEWBOX_WIDTH')) {
    define('PROJECT_LOGO_VIEWBOX_WIDTH', 900);
}
if (!defined('PROJECT_LOGO_VIEWBOX_HEIGHT')) {
    define('PROJECT_LOGO_VIEWBOX_HEIGHT', 200);
}

if (!defined('ENABLE_TCPDF_GENERATION')) {
    define('ENABLE_TCPDF_GENERATION', false);
}

$pacienteId = isset($_GET['paciente_id']) ? (int) $_GET['paciente_id'] : null;

if (empty($pacienteId)) {
    http_response_code(400);
    echo 'Parâmetro "paciente_id" é obrigatório.';
    exit;
}

try {
    // Usar a classe Database do projeto para conexão
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    $paciente = carregarDadosPaciente($pdo, $pacienteId);

    if (!$paciente) {
        http_response_code(404);
        echo 'Paciente não encontrado.';
        exit;
    }

    $respostas = carregarRespostas($pdo, $pacienteId);

    if (empty($respostas)) {
        http_response_code(404);
        echo 'Nenhuma resposta encontrada para este paciente.';
        exit;
    }

    $dadosPaciente = [
        'nome' => $paciente['nome_completo'],
        'dataNascimento' => $paciente['data_nascimento_formatada'],
        'idade' => $paciente['idade'],
        'cpf' => $paciente['cpf_formatado'],
        'dataEntrevista' => $paciente['ultima_resposta'] ?? date('d/m/Y H:i'),
        'medicoResponsavel' => $paciente['anestesista_nome'] ?? 'Não informado', // Nutricionista responsável (campo DB: anestesista_nome)
        'procedimentoNome' => $paciente['procedimento_nome'] ?? 'Não informado',
        'dataHoraProcedimento' => $paciente['data_hora_procedimento'] ?? 'Não informado'
    ];

    $metricasQuestionario = calcularMetricasQuestionario($respostas);
} catch (Exception $e) {
    http_response_code(500);
    echo 'Erro ao gerar relatório: ' . $e->getMessage();
    exit;
}

// Limpar qualquer output indesejado antes de gerar o PDF
ob_clean();

// Verifica se TCPDF está disponível e habilitado, caso contrário usa HTML
if (ENABLE_TCPDF_GENERATION && class_exists('TCPDF')) {
    gerarPDFComTCPDF($dadosPaciente, $respostas, $metricasQuestionario);
    // Restaurar configurações de erro após gerar o PDF
    error_reporting($oldErrorReporting);
    ini_set('display_errors', $oldDisplayErrors);
    exit;
} else {
    // Restaurar configurações de erro antes do fallback
    error_reporting($oldErrorReporting);
    ini_set('display_errors', $oldDisplayErrors);
    
    if (!$composerAvailable) {
        error_log('[Relatório NutriCheck] Composer autoload não encontrado em vendor/autoload.php');
    }
    if ($composerAvailable) {
        error_log('[Relatório NutriCheck] TCPDF não encontrado mesmo após carregar o Composer. Verifique se tecnickcom/tcpdf foi instalado corretamente.');
    }
    // Fallback: gera HTML que pode ser convertido para PDF pelo navegador
    ob_end_clean();
    gerarHTMLRelatorio($dadosPaciente, $respostas, $metricasQuestionario);
    exit;
}

// Função removida - agora usa Database::getInstance()->getConnection()

function carregarDadosPaciente(PDO $pdo, int $pacienteId): ?array {
    $sql = "
        SELECT 
            p.id,
            p.nome,
            p.sobrenome,
            p.cpf,
            p.data_nascimento,
            p.procedimento_id,
            p.data_procedimento,
            p.hora_procedimento,
            MAX(a.nome) AS anestesista_nome,
            MAX(r.created_at) AS ultima_resposta_raw,
            CONCAT_WS(' ', p.nome, p.sobrenome) AS nome_completo,
            pr.nome AS procedimento_nome
        FROM pacientes p
        LEFT JOIN usuarios a ON a.id = p.anestesista_id
        LEFT JOIN procedimentos pr ON pr.id = p.procedimento_id
        LEFT JOIN paciente_video_respostas r ON r.paciente_id = p.id
        WHERE p.id = :paciente_id
        GROUP BY p.id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':paciente_id' => $pacienteId]);
    $row = $stmt->fetch();

    if (!$row) {
        return null;
    }

    $row['cpf_formatado'] = formatarCPF($row['cpf']);
    $row['data_nascimento_formatada'] = $row['data_nascimento']
        ? formatarDataBR($row['data_nascimento'])
        : 'Não informado';
    $row['idade'] = calcularIdade($row['data_nascimento']);
    $row['ultima_resposta'] = $row['ultima_resposta_raw']
        ? formatarDataBR($row['ultima_resposta_raw'], 'd/m/Y H:i')
        : null;
    
    // Formatar data e hora do procedimento
    $row['data_procedimento_formatada'] = $row['data_procedimento']
        ? formatarDataBR($row['data_procedimento'], 'd/m/Y')
        : null;
    $row['hora_procedimento_formatada'] = $row['hora_procedimento']
        ? date('H:i', strtotime($row['hora_procedimento']))
        : null;
    $row['data_hora_procedimento'] = null;
    if ($row['data_procedimento_formatada']) {
        $row['data_hora_procedimento'] = $row['data_procedimento_formatada'];
        if ($row['hora_procedimento_formatada']) {
            $row['data_hora_procedimento'] .= ' às ' . $row['hora_procedimento_formatada'];
        }
    }

    return $row;
}

function carregarRespostas(PDO $pdo, int $pacienteId): array {
    $sql = "
        SELECT
            r.video_id,
            r.video_title,
            r.video_ordem,
            r.question_id,
            r.question_index,
            r.question_text,
            r.question_title,
            r.answer,
            r.answer_type,
            r.created_at
        FROM paciente_video_respostas r
        WHERE r.paciente_id = :paciente_id
        ORDER BY COALESCE(r.video_ordem, 999), r.question_index
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':paciente_id' => $pacienteId]);
    $rows = $stmt->fetchAll();

    $agrupado = [];

    foreach ($rows as $row) {
        $videoId = $row['video_id'] ?? 'video_' . ($row['video_ordem'] ?? count($agrupado) + 1);

        if (!isset($agrupado[$videoId])) {
            $agrupado[$videoId] = [
                'videoId' => $videoId,
                'videoTitle' => $row['video_title'] ?? strtoupper($videoId),
                'videoOrdem' => $row['video_ordem'] ?? null,
                'answers' => [],
                'created_at' => null
            ];
        }

        $answerType = $row['answer_type'] ?? 'text';
        $answerValue = $row['answer'];

        if ($answerType === 'checkbox') {
            $decoded = json_decode($row['answer'], true);
            $answerValue = is_array($decoded) ? $decoded : [];
        }

        // Armazenar o created_at mais recente de cada vídeo
        if (!empty($row['created_at'])) {
            $videoCreatedAt = strtotime($row['created_at']);
            if ($agrupado[$videoId]['created_at'] === null || $videoCreatedAt > strtotime($agrupado[$videoId]['created_at'])) {
                $agrupado[$videoId]['created_at'] = $row['created_at'];
            }
        }

        $agrupado[$videoId]['answers'][] = [
            'questionId' => $row['question_id'] ?? null,
            'questionIndex' => (int) $row['question_index'],
            'questionText' => $row['question_text'] ?? ($row['question_title'] ?? ''),
            'answer' => $answerValue,
            'type' => $answerType,
            'created_at' => $row['created_at'] ?? null
        ];
    }

    return array_values($agrupado);
}

function respostaParaTexto(array $answer): string {
    $valor = $answer['answer'] ?? '';
    
    if (is_array($valor)) {
        $flatten = [];
        $iterator = function ($item) use (&$flatten) {
            if (is_array($item)) {
                array_walk_recursive($item, function ($child) use (&$flatten) {
                    if ($child !== null && $child !== '') {
                        $flatten[] = $child;
                    }
                });
            } else {
                if ($item !== null && $item !== '') {
                    $flatten[] = $item;
                }
            }
        };
        $iterator($valor);
        return implode(', ', $flatten);
    }
    
    if (is_bool($valor)) {
        return $valor ? 'Sim' : 'Não';
    }
    
    if ($valor === null) {
        return '';
    }
    
    return trim((string) $valor);
}

function formatarCPF(?string $cpf): string {
    if (empty($cpf)) {
        return 'Não informado';
    }

    $numeros = preg_replace('/\D/', '', $cpf);

    if (strlen($numeros) === 11) {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $numeros);
    }

    return $cpf;
}

function formatarDataBR(string $data, string $formato = 'd/m/Y'): string {
    $dateTime = new DateTime($data);
    return $dateTime->format($formato);
}

function calcularIdade(?string $dataNascimento): ?int {
    if (empty($dataNascimento)) {
        return null;
    }

    $nascimento = new DateTime($dataNascimento);
    $hoje = new DateTime();
    return $hoje->diff($nascimento)->y;
}

function formatarIdadeTexto($idade): string {
    return is_numeric($idade) ? ($idade . ' anos') : 'Não informado';
}

function calcularTempoTotalQuestionario(array $respostas): array {
    $tempoTotalMinutos = 0;
    $ultimoVideoRespondido = null;
    $primeiroVideoTimestamp = null;
    $ultimoVideoTimestamp = null;
    $videosCompletos = 0;
    $totalVideosEsperados = 15; // video_1 até video_15

    // Encontrar o primeiro vídeo (video_1 - Introdução)
    $primeiroVideo = null;
    foreach ($respostas as $video) {
        if ($video['videoId'] === 'video_1' || (isset($video['videoOrdem']) && $video['videoOrdem'] == 1)) {
            $primeiroVideo = $video;
            break;
        }
    }

    // Se não encontrar video_1, pegar o primeiro vídeo com timestamp
    if (!$primeiroVideo) {
        foreach ($respostas as $video) {
            if (!empty($video['created_at'])) {
                $primeiroVideo = $video;
                break;
            }
        }
    }

    // Encontrar o último vídeo respondido
    $ultimoVideo = null;
    $ultimoTimestamp = null;
    foreach ($respostas as $video) {
        if (!empty($video['created_at'])) {
            $timestamp = strtotime($video['created_at']);
            if ($ultimoTimestamp === null || $timestamp > $ultimoTimestamp) {
                $ultimoTimestamp = $timestamp;
                $ultimoVideo = $video;
            }
        }
    }

    // Calcular tempo total
    if ($primeiroVideo && !empty($primeiroVideo['created_at']) && $ultimoVideo && !empty($ultimoVideo['created_at'])) {
        $inicio = new DateTime($primeiroVideo['created_at']);
        $fim = new DateTime($ultimoVideo['created_at']);
        $diferenca = $inicio->diff($fim);
        
        // Converter para minutos
        $tempoTotalMinutos = ($diferenca->days * 24 * 60) + ($diferenca->h * 60) + $diferenca->i;
        
        $primeiroVideoTimestamp = $primeiroVideo['created_at'];
        $ultimoVideoTimestamp = $ultimoVideo['created_at'];
        $ultimoVideoRespondido = [
            'videoId' => $ultimoVideo['videoId'],
            'videoTitle' => $ultimoVideo['videoTitle'],
            'videoOrdem' => $ultimoVideo['videoOrdem'] ?? null
        ];
    }

    // Contar quantos vídeos foram respondidos
    foreach ($respostas as $video) {
        if (!empty($video['created_at']) && !empty($video['answers'])) {
            $videosCompletos++;
        }
    }

    $completouTodosVideos = $videosCompletos >= $totalVideosEsperados;

    return [
        'tempoTotalMinutos' => $tempoTotalMinutos,
        'ultimoVideoRespondido' => $ultimoVideoRespondido,
        'primeiroVideoTimestamp' => $primeiroVideoTimestamp,
        'ultimoVideoTimestamp' => $ultimoVideoTimestamp,
        'videosCompletos' => $videosCompletos,
        'totalVideosEsperados' => $totalVideosEsperados,
        'completouTodosVideos' => $completouTodosVideos
    ];
}

function calcularMetricasQuestionario(array $respostas): array {
    $perguntasRespondidas = 0;
    foreach ($respostas as $video) {
        $perguntasRespondidas += count($video['answers'] ?? []);
    }

    $totalPerguntas = calcularTotalPerguntasEsperadas($respostas);

    if ($totalPerguntas <= 0) {
        $totalPerguntas = $perguntasRespondidas;
    }

    if ($perguntasRespondidas > $totalPerguntas) {
        $totalPerguntas = $perguntasRespondidas;
    }

    // Calcular tempo total baseado nos timestamps
    $dadosTempo = calcularTempoTotalQuestionario($respostas);
    $tempoTotalVideos = $dadosTempo['tempoTotalMinutos'];
    
    $tempoMedioPorPergunta = $perguntasRespondidas > 0 ? round($tempoTotalVideos / $perguntasRespondidas, 1) : 0;
    
    // Calcular percentual baseado em vídeos completados, não apenas perguntas
    $videosCompletos = $dadosTempo['videosCompletos'];
    $totalVideosEsperados = $dadosTempo['totalVideosEsperados'];
    $percentualCompleto = $totalVideosEsperados > 0 ? min(100, round(($videosCompletos / $totalVideosEsperados) * 100)) : 0;

    return [
        'perguntasRespondidas' => $perguntasRespondidas,
        'totalPerguntas' => max(1, $totalPerguntas),
        'tempoTotalVideos' => $tempoTotalVideos,
        'tempoMedioPorPergunta' => $tempoMedioPorPergunta,
        'percentualCompleto' => $percentualCompleto,
        'ultimoVideoRespondido' => $dadosTempo['ultimoVideoRespondido'],
        'videosCompletos' => $dadosTempo['videosCompletos'],
        'totalVideosEsperados' => $dadosTempo['totalVideosEsperados'],
        'completouTodosVideos' => $dadosTempo['completouTodosVideos']
    ];
}

function calcularTotalPerguntasEsperadas(array $respostas): int {
    $definicoes = carregarDefinicoesQuestionario();
    if (empty($definicoes)) {
        return 0;
    }

    $respostasMap = [];
    foreach ($respostas as $video) {
        foreach ($video['answers'] as $answer) {
            if (!empty($answer['questionId'])) {
                $respostasMap[$answer['questionId']] = $answer['answer'];
            }
        }
    }

    $total = 0;

    foreach ($definicoes as $videoDef) {
        $perguntas = $videoDef['questions'] ?? [];
        foreach ($perguntas as $question) {
            if (questionDisponivelParaPaciente($question, $respostasMap)) {
                $total++;
            }
        }
    }

    return $total;
}

function questionDisponivelParaPaciente(array $questionDef, array $respostasMap): bool {
    if (empty($questionDef['showIf'])) {
        return true;
    }

    $condicao = $questionDef['showIf'];
    $questionId = $condicao['questionId'] ?? null;

    if (!$questionId) {
        return true;
    }

    if (!array_key_exists($questionId, $respostasMap)) {
        return false;
    }

    $valorResposta = $respostasMap[$questionId];

    if (array_key_exists('equals', $condicao)) {
        return compararValorResposta($valorResposta, $condicao['equals']);
    }

    if (array_key_exists('notEquals', $condicao)) {
        return !compararValorResposta($valorResposta, $condicao['notEquals']);
    }

    if (!empty($condicao['in']) && is_array($condicao['in'])) {
        foreach ($condicao['in'] as $esperado) {
            if (compararValorResposta($valorResposta, $esperado)) {
                return true;
            }
        }
        return false;
    }

    if (!empty($condicao['notIn']) && is_array($condicao['notIn'])) {
        foreach ($condicao['notIn'] as $esperado) {
            if (compararValorResposta($valorResposta, $esperado)) {
                return false;
            }
        }
        return true;
    }

    return true;
}

function compararValorResposta($valorResposta, $esperado): bool {
    if (is_array($valorResposta)) {
        if (is_array($esperado)) {
            return count(array_intersect($valorResposta, $esperado)) > 0;
        }
        return in_array($esperado, $valorResposta, true);
    }

    if (is_array($esperado)) {
        return in_array($valorResposta, $esperado, true);
    }

    return $valorResposta === $esperado;
}

function carregarDefinicoesQuestionario(): array {
    static $cache = null;

    if ($cache !== null) {
        return $cache;
    }

    $path = __DIR__ . '/../app/config/questionario.php';
    if (file_exists($path)) {
        $data = require $path;
        $cache = is_array($data) ? $data : [];
    } else {
        $cache = [];
    }

    return $cache;
}

function getLogoSvgContent(): string {
    static $cache = null;
    
    if ($cache !== null) {
        return $cache;
    }
    
    if (defined('PROJECT_LOGO_FILE') && file_exists(PROJECT_LOGO_FILE)) {
        $cache = file_get_contents(PROJECT_LOGO_FILE);
    } else {
        $cache = '';
    }
    
    return $cache;
}

function desenharLogoProjeto($pdf, $centerX, $centerY, $width = 360) {
    $svgContent = getLogoSvgContent();
    if (empty($svgContent) || !is_string($svgContent)) {
        return;
    }
    
    // Garantir que o SVG é uma string válida e não vazia
    $svgContent = trim($svgContent);
    if (empty($svgContent) || strlen($svgContent) < 10) {
        return;
    }
    
    $viewboxW = PROJECT_LOGO_VIEWBOX_WIDTH ?: 600;
    $viewboxH = PROJECT_LOGO_VIEWBOX_HEIGHT ?: 200;
    $height = $width * ($viewboxH / $viewboxW);
    
    $x = $centerX - ($width / 2);
    $y = $centerY - ($height / 2);
    
    // Suprimir warnings específicos do TCPDF durante renderização do SVG
    $oldErrorReporting = error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
    
    try {
        // Usar método mais seguro: passar o SVG como string direta (sem @)
        // O TCPDF aceita SVG inline quando passado corretamente
        $pdf->ImageSVG('@' . $svgContent, $x, $y, $width, $height, '', '', '', 0, false);
    } catch (Exception $e) {
        // Ignora falhas de renderização do SVG para não quebrar a geração do PDF
        error_log('[Relatório PDF] Erro ao renderizar logo SVG: ' . $e->getMessage());
    } finally {
        // Restaurar configuração de erros
        error_reporting($oldErrorReporting);
    }
}

function gerarPDFComTCPDF($paciente, $respostas, array $metricas) {
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Configurações do documento
    $pdf->SetCreator('Sistema de Entrevista Pré-anestésica');
    $pdf->SetAuthor('Dr(a). Liege');
    $pdf->SetTitle('Relatório Pré-anestésico - ' . $paciente['nome']);
    $pdf->SetSubject('Avaliação Pré-anestésica');
    
    // Remove header e footer padrão
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Margens padrão (serão removidas na primeira página)
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(TRUE, 20);
    
    // ========== CAPA INICIAL ==========
    $pdf->AddPage();
    
    // Remove margens apenas da primeira página
    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(FALSE);
    
    $pageWidth = $pdf->getPageWidth();
    $pageHeight = $pdf->getPageHeight();
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Rect(0, 0, $pageWidth, $pageHeight, 'F');
    
    // Logo centralizado
    desenharLogoProjeto($pdf, $pageWidth / 2, $pageHeight / 2);
    
    // ========== PÁGINA DE DADOS DO PACIENTE (estilo Examination Report) ==========
    $pdf->AddPage();
    
    // Restaura margens para as próximas páginas
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(TRUE, 20);
    
    // Desenha cabeçalho azul (aparece em todas as páginas exceto capa e última)
    $alturaHeader = desenharCabecalho($pdf, $pageWidth, $pageHeight);
    
    // Fundo branco para o conteúdo
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Rect(0, $alturaHeader, $pageWidth, $pageHeight - $alturaHeader, 'F');
    
    // Dados do paciente em duas colunas (estilo do modelo exato)
    $pdf->SetY($alturaHeader + 25);
    
    // Configurações das colunas
    $xEsquerda = 30;
    $xDireita = 130;
    $larguraColuna = 90;
    $espacamentoVertical = 12;
    
    // Primeira linha
    // Label esquerda
    $pdf->SetX($xEsquerda);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetTextColor(150, 150, 150); // Cinza mais claro para labels
    $pdf->Cell($larguraColuna, 6, 'Paciente', 0, 0, 'L');
    
    // Label direita
    $pdf->SetX($xDireita);
    $pdf->Cell($larguraColuna, 6, 'Data de Nascimento', 0, 1, 'L');
    
    // Valor esquerda
    $pdf->SetX($xEsquerda);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetTextColor(0, 0, 0); // Preto para valores
    $pdf->Cell($larguraColuna, 8, $paciente['nome'], 0, 0, 'L');
    
    // Valor direita
    $pdf->SetX($xDireita);
    $pdf->Cell($larguraColuna, 8, $paciente['dataNascimento'], 0, 1, 'L');
    
    $pdf->Ln(3);
    
    // Segunda linha
    // Label esquerda
    $pdf->SetX($xEsquerda);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetTextColor(150, 150, 150);
    $pdf->Cell($larguraColuna, 6, 'Data da Entrevista', 0, 0, 'L');
    
    // Label direita
    $pdf->SetX($xDireita);
    $pdf->Cell($larguraColuna, 6, 'Idade', 0, 1, 'L');
    
    // Valor esquerda
    $pdf->SetX($xEsquerda);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell($larguraColuna, 8, $paciente['dataEntrevista'], 0, 0, 'L');
    
    // Valor direita
    $pdf->SetX($xDireita);
    $pdf->Cell($larguraColuna, 8, formatarIdadeTexto($paciente['idade']), 0, 1, 'L');
    
    $pdf->Ln(3);
    
    // Terceira linha
    // Label esquerda
    $pdf->SetX($xEsquerda);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetTextColor(150, 150, 150);
    $pdf->Cell($larguraColuna, 6, 'CPF', 0, 0, 'L');
    
    // Label direita
    $pdf->SetX($xDireita);
    $pdf->Cell($larguraColuna, 6, 'Nutricionista Responsável', 0, 1, 'L');
    
    // Valor esquerda
    $pdf->SetX($xEsquerda);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell($larguraColuna, 8, $paciente['cpf'], 0, 0, 'L');
    
    // Valor direita
    $pdf->SetX($xDireita);
    $pdf->Cell($larguraColuna, 8, $paciente['medicoResponsavel'], 0, 1, 'L');
    
    $pdf->Ln(3);
    
    // Quarta linha - Procedimento
    // Label esquerda
    $pdf->SetX($xEsquerda);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetTextColor(150, 150, 150);
    $pdf->Cell($larguraColuna, 6, 'Procedimento', 0, 0, 'L');
    
    // Label direita
    $pdf->SetX($xDireita);
    $pdf->Cell($larguraColuna, 6, 'Data e Hora do Procedimento', 0, 1, 'L');
    
    // Valor esquerda
    $pdf->SetX($xEsquerda);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell($larguraColuna, 8, $paciente['procedimentoNome'], 0, 0, 'L');
    
    // Valor direita
    $pdf->SetX($xDireita);
    $pdf->Cell($larguraColuna, 8, $paciente['dataHoraProcedimento'], 0, 1, 'L');
    
    // Linha cinza separadora (ajustada para depois dos dados)
    $yLinha = $pdf->GetY() + 10;
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->SetLineWidth(0.5);
    $pdf->Line(0, $yLinha, $pageWidth, $yLinha);
    
    // ========== SEÇÃO DE ANÁLISE VISUAL DAS RESPOSTAS ==========
    $yInicioAnalise = $yLinha + 20;
    $pdf->SetY($yInicioAnalise);
    
    // Título da seção
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->SetTextColor(60, 120, 216);
    $pdf->SetX(30);
    $pdf->Cell(0, 10, 'Análise da Entrevista', 0, 1, 'L');
    
    $pdf->Ln(5);
    
    // Métricas do questionário
    $perguntasRespondidas = $metricas['perguntasRespondidas'];
    $totalPerguntas = $metricas['totalPerguntas'];
    $tempoTotalVideos = $metricas['tempoTotalVideos'];
    $percentualCompleto = $metricas['percentualCompleto'];
    $tempoMedioPorPergunta = $metricas['tempoMedioPorPergunta'];
    $ultimoVideoRespondido = $metricas['ultimoVideoRespondido'] ?? null;
    $videosCompletos = $metricas['videosCompletos'] ?? 0;
    $totalVideosEsperados = $metricas['totalVideosEsperados'] ?? 15;
    $completouTodosVideos = $metricas['completouTodosVideos'] ?? false;
    
    // Calcula risco baseado nas respostas (mockado)
    $respostasSim = 0;
    $respostasNao = 0;
    foreach ($respostas as $video) {
        foreach ($video['answers'] as $answer) {
            if ($answer['type'] === 'boolean') {
                if ($answer['answer'] === 'Sim' || $answer['answer'] === 'Vamos Começar!!') {
                    $respostasSim++;
                } else {
                    $respostasNao++;
                }
            }
        }
    }
    
    // Classificação de risco (mockado - baseado em lógica simplificada)
    $risco = 'Baixo';
    $corRisco = [40, 167, 69]; // Verde
    $descricaoRisco = 'Paciente apresenta perfil de baixo risco anestésico. Avaliação pré-operatória padrão recomendada.';
    
    if ($respostasSim > 15) {
        $risco = 'Alto';
        $corRisco = [220, 53, 69]; // Vermelho
        $descricaoRisco = 'Paciente apresenta múltiplos fatores de risco. Recomenda-se avaliação pré-operatória detalhada e acompanhamento pelo nutricionista antes do procedimento.';
    } elseif ($respostasSim > 8) {
        $risco = 'Médio';
        $corRisco = [255, 193, 7]; // Amarelo
        $descricaoRisco = 'Paciente apresenta alguns fatores de risco. Sugere-se avaliação pré-operatória adicional antes do procedimento.';
    }
    
    // Cards de métricas (4 cards em linha - ajustados para não sobrepor)
    $xCard1 = 15;
    $xCard2 = 60;
    $xCard3 = 105;
    $xCard4 = 150;
    $larguraCard = 38;
    $alturaCard = 30;
    $yCards = $pdf->GetY();
    
    // Card 1: Perguntas Respondidas
    $pdf->SetFillColor(102, 126, 234);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->RoundedRect($xCard1, $yCards, $larguraCard, $alturaCard, 3, 'F');
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->SetXY($xCard1, $yCards + 3);
    $pdf->Cell($larguraCard, 8, $perguntasRespondidas . '/' . $totalPerguntas, 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetXY($xCard1, $yCards + 12);
    $pdf->Cell($larguraCard, 6, 'Perguntas', 0, 1, 'C');
    $pdf->SetXY($xCard1, $yCards + 18);
    $pdf->Cell($larguraCard, 6, 'Respondidas', 0, 1, 'C');
    
    // Card 2: Tempo Total
    $pdf->SetFillColor(40, 167, 69);
    $pdf->RoundedRect($xCard2, $yCards, $larguraCard, $alturaCard, 3, 'F');
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->SetXY($xCard2, $yCards + 3);
    $pdf->Cell($larguraCard, 8, $tempoTotalVideos . ' min', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetXY($xCard2, $yCards + 12);
    $pdf->Cell($larguraCard, 6, 'Tempo Total', 0, 1, 'C');
    $pdf->SetXY($xCard2, $yCards + 18);
    $pdf->Cell($larguraCard, 6, 'de Vídeos', 0, 1, 'C');
    
    // Card 3: Percentual Completo
    $pdf->SetFillColor(255, 193, 7);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->RoundedRect($xCard3, $yCards, $larguraCard, $alturaCard, 3, 'F');
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->SetXY($xCard3, $yCards + 3);
    $pdf->Cell($larguraCard, 8, $percentualCompleto . '%', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetXY($xCard3, $yCards + 12);
    $pdf->Cell($larguraCard, 6, 'Questionário', 0, 1, 'C');
    $pdf->SetXY($xCard3, $yCards + 18);
    $pdf->Cell($larguraCard, 6, 'Completo', 0, 1, 'C');
    
    // Card 4: Tempo Médio por Pergunta
    $pdf->SetFillColor(255, 87, 34);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->RoundedRect($xCard4, $yCards, $larguraCard, $alturaCard, 3, 'F');
    $pdf->SetFont('helvetica', 'B', 18);
    $pdf->SetXY($xCard4, $yCards + 3);
    $pdf->Cell($larguraCard, 8, $tempoMedioPorPergunta . ' min', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetXY($xCard4, $yCards + 12);
    $pdf->Cell($larguraCard, 6, 'Tempo Médio', 0, 1, 'C');
    $pdf->SetXY($xCard4, $yCards + 18);
    $pdf->Cell($larguraCard, 6, 'por Pergunta', 0, 1, 'C');
    
    $pdf->Ln(5);
    
    // Observação sobre questionário incompleto (se aplicável)
    if (!$completouTodosVideos && $ultimoVideoRespondido) {
        $yObservacao = $pdf->GetY();
        $pdf->SetFillColor(255, 243, 205); // Amarelo claro
        $pdf->SetDrawColor(255, 193, 7); // Amarelo
        $pdf->SetLineWidth(0.5);
        $pdf->RoundedRect(30, $yObservacao, $pageWidth - 60, 20, 2, 'DF');
        
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor(133, 100, 4); // Marrom escuro
        $pdf->SetXY(35, $yObservacao + 3);
        $pdf->Cell(0, 6, 'Observação: Questionário Incompleto', 0, 1, 'L');
        
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetXY(35, $yObservacao + 10);
        $ultimoVideoTitulo = $ultimoVideoRespondido['videoTitle'] ?? $ultimoVideoRespondido['videoId'];
        $textoObservacao = "O paciente respondeu até o vídeo \"{$ultimoVideoTitulo}\" ({$videosCompletos} de {$totalVideosEsperados} vídeos completos). O tempo total calculado é baseado no período entre o início (vídeo 1 - Introdução) e o último vídeo respondido.";
        $pdf->MultiCell($pageWidth - 70, 5, $textoObservacao, 0, 'L');
        
        $pdf->Ln(3);
    }
    
    $yRisco = $pdf->GetY() + 5;
    
    // Seção de Classificação de Risco (IA)
    $pdf->SetY($yRisco);
    $pdf->SetX(30);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetTextColor(44, 62, 80);
    $pdf->Cell(0, 8, 'Classificação de Risco Anestésico (IA)', 0, 1, 'L');
    
    $pdf->Ln(3);
    
    // Card de Risco
    $larguraCardRisco = 150;
    $alturaCardRisco = 45;
    $xCardRisco = 30;
    $yCardRisco = $pdf->GetY();
    
    // Borda do card de risco
    $pdf->SetDrawColor($corRisco[0], $corRisco[1], $corRisco[2]);
    $pdf->SetLineWidth(2);
    $pdf->RoundedRect($xCardRisco, $yCardRisco, $larguraCardRisco, $alturaCardRisco, 3, 'D');
    
    // Fundo do card (branco com leve tom da cor)
    $pdf->SetFillColor(255, 255, 255);
    $pdf->RoundedRect($xCardRisco + 1, $yCardRisco + 1, $larguraCardRisco - 2, $alturaCardRisco - 2, 3, 'F');
    
    // Indicador visual de risco (círculo colorido)
    $xCirculo = $xCardRisco + 10;
    $yCirculo = $yCardRisco + 10;
    $pdf->SetFillColor($corRisco[0], $corRisco[1], $corRisco[2]);
    $pdf->Circle($xCirculo, $yCirculo, 8, 0, 360, 'FD');
    
    // Texto do risco
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetTextColor($corRisco[0], $corRisco[1], $corRisco[2]);
    $pdf->SetXY($xCardRisco + 25, $yCardRisco + 5);
    $pdf->Cell(100, 8, 'Risco: ' . $risco, 0, 1, 'L');
    
    // Descrição da classificação
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetTextColor(44, 62, 80);
    $pdf->SetXY($xCardRisco + 10, $yCardRisco + 20);
    $pdf->MultiCell($larguraCardRisco - 20, 5, $descricaoRisco, 0, 'L');
    
    // Barra de progresso de completude (visual)
    $pdf->SetY($yCardRisco + $alturaCardRisco + 15);
    $pdf->SetX(30);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetTextColor(44, 62, 80);
    $pdf->Cell(50, 6, 'Progresso do Questionário:', 0, 0, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(20, 6, $percentualCompleto . '%', 0, 1, 'L');
    
    // Barra de progresso visual
    $xBarra = 30;
    $yBarra = $pdf->GetY() + 2;
    $larguraBarra = 120;
    $alturaBarra = 8;
    
    // Fundo da barra (cinza claro)
    $pdf->SetFillColor(230, 230, 230);
    $pdf->RoundedRect($xBarra, $yBarra, $larguraBarra, $alturaBarra, 2, 'F');
    
    // Preenchimento da barra (verde)
    $larguraPreenchimento = ($percentualCompleto / 100) * $larguraBarra;
    $pdf->SetFillColor(40, 167, 69);
    $pdf->RoundedRect($xBarra, $yBarra, $larguraPreenchimento, $alturaBarra, 2, 'F');
    
    // Indicadores de estatísticas adicionais
    $pdf->SetY($yBarra + $alturaBarra + 15);
    // Recomendação de IA (em destaque)
    $pdf->SetY($pdf->GetY() + 10);
    $pdf->SetX(30);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetTextColor(60, 120, 216);
    $pdf->Cell(0, 8, 'Recomendação do Sistema:', 0, 1, 'L');
    
    // Card de recomendação
    $yRec = $pdf->GetY() + 3;
    $alturaRec = 35;
    $pdf->SetFillColor(240, 248, 255);
    $pdf->RoundedRect(30, $yRec, 150, $alturaRec, 3, 'F');
    $pdf->SetDrawColor(102, 126, 234);
    $pdf->SetLineWidth(1);
    $pdf->RoundedRect(30, $yRec, 150, $alturaRec, 3, 'D');
    
    // Ícone/emoji de IA (usando texto)
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->SetTextColor(102, 126, 234);
    $pdf->SetXY(35, $yRec + 5);
    $pdf->Cell(10, 8, '🤖', 0, 0, 'L');
    
    // Texto da recomendação
    $recomendacaoTexto = 'Com base nas respostas fornecidas, o sistema sugere que ';
    if ($risco === 'Alto') {
        $recomendacaoTexto .= 'o paciente seja avaliado presencialmente pelo nutricionista antes do procedimento, devido aos múltiplos fatores de risco identificados.';
    } elseif ($risco === 'Médio') {
        $recomendacaoTexto .= 'seja realizada uma avaliação pré-operatória adicional com o nutricionista para discussão dos fatores de risco identificados.';
    } else {
        $recomendacaoTexto .= 'o paciente está apto para o procedimento conforme protocolo padrão de avaliação pré-anestésica.';
    }
    
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetTextColor(44, 62, 80);
    $pdf->SetXY(48, $yRec + 5);
    $pdf->MultiCell(125, 4, $recomendacaoTexto, 0, 'L');
    
    // ========== PÁGINA DE VISUALIZAÇÃO DAS RESPOSTAS PARA O ANESTESISTA ==========
    $pdf->AddPage();
    
    // Desenha cabeçalho azul
    $alturaHeader = desenharCabecalho($pdf, $pageWidth, $pageHeight);
    
    // Fundo branco para o conteúdo
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Rect(0, $alturaHeader, $pageWidth, $pageHeight - $alturaHeader, 'F');
    
    // Conteúdo das respostas organizadas (começa após o cabeçalho)
    $pdf->SetY($alturaHeader + 20);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetTextColor(44, 62, 80);
    
    $categorias = organizarPorCategoria($respostas);
    
    foreach ($categorias as $categoriaNome => $videos) {
        // Título da categoria (estilo da imagem - fundo azul, texto branco)
        // Verifica espaço antes de adicionar (categoria precisa de ~20mm)
        if ($pdf->GetY() > ($pageHeight - 60)) {
            $pdf->AddPage();
            $alturaHeader = desenharCabecalho($pdf, $pageWidth, $pageHeight);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect(0, $alturaHeader, $pageWidth, $pageHeight - $alturaHeader, 'F');
            $pdf->SetY($alturaHeader + 20);
            $pdf->SetTextColor(44, 62, 80);
        }
        
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFillColor(60, 120, 216);
        $pdf->SetX(0);
        $pdf->Cell(0, 12, $categoriaNome, 0, 1, 'L', true);
        $pdf->Ln(5);
        
        foreach ($videos as $video) {
            // Título do vídeo (estilo da imagem)
            // Verifica espaço antes de adicionar (vídeo precisa de ~15mm mínimo)
            if ($pdf->GetY() > ($pageHeight - 40)) {
                $pdf->AddPage();
                $alturaHeader = desenharCabecalho($pdf, $pageWidth, $pageHeight);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Rect(0, $alturaHeader, $pageWidth, $pageHeight - $alturaHeader, 'F');
                $pdf->SetY($alturaHeader + 20);
                $pdf->SetTextColor(44, 62, 80);
            }
            
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetTextColor(44, 62, 80);
            $pdf->Cell(0, 8, $video['videoTitle'], 0, 1, 'L');
            $pdf->Ln(2);
            
            // Respostas do vídeo - agrupadas, fluindo naturalmente
            foreach ($video['answers'] as $answer) {
                $respostaTexto = respostaParaTexto($answer);
                $answerLength = strlen($respostaTexto);
                $questionLength = strlen($answer['questionText'] ?? '');
                // Estima altura necessária para pergunta + resposta (~20mm por par)
                $alturaNecessaria = 20;
                if ($answer['type'] === 'text' || $answerLength > 100) {
                    $linhas = ceil($questionLength / 80) + ceil($answerLength / 80);
                    $alturaNecessaria = max(20, $linhas * 7);
                }
                
                // Só cria nova página se realmente não couber
                if ($pdf->GetY() + $alturaNecessaria > ($pageHeight - 20)) {
                    $pdf->AddPage();
                    $alturaHeader = desenharCabecalho($pdf, $pageWidth, $pageHeight);
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->Rect(0, $alturaHeader, $pageWidth, $pageHeight - $alturaHeader, 'F');
                    $pdf->SetY($alturaHeader + 20);
                    $pdf->SetTextColor(44, 62, 80);
                }
                
                // Pergunta (estilo da imagem - cinza, negrito)
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->SetTextColor(108, 117, 125);
                $pdf->SetX(15);
                $pdf->MultiCell(0, 5, 'Pergunta ' . $answer['questionIndex'] . ': ' . $answer['questionText'], 0, 'L');
                
                // Resposta (estilo da imagem)
                $pdf->SetFont('helvetica', '', 10);
                $pdf->SetX(15);
                
                // Cor da resposta baseada no tipo (verde para Sim, vermelho para Não, preto para outros)
                if ($answer['type'] === 'boolean') {
                    $valorNormalizado = mb_strtolower(trim($respostaTexto));
                    $positivo = in_array($valorNormalizado, ['sim', 'vamos começar!!', 'sim!', 'sim.']);
                    $cor = $positivo ? [40, 167, 69] : [220, 53, 69];
                    $pdf->SetTextColor($cor[0], $cor[1], $cor[2]);
                } else {
                    $pdf->SetTextColor(44, 62, 80);
                }
                
                // Texto "Resposta: "
                $pdf->SetFont('helvetica', '', 10);
                $pdf->Cell(50, 6, 'Resposta: ', 0, 0, 'L');
                
                // Valor da resposta (com cor aplicada)
                if ($answer['type'] === 'text' || ($answerLength > 100)) {
                    $pdf->MultiCell(0, 5, $respostaTexto, 0, 'L');
                } else {
                    $pdf->Cell(0, 6, $respostaTexto, 0, 1, 'L');
                }
                
                // Reset da cor
                $pdf->SetTextColor(44, 62, 80);
                $pdf->Ln(4);
            }
            
            $pdf->Ln(3);
        }
        
        $pdf->Ln(5);
    }
    
    // ========== CAPA FINAL ==========
    $pdf->AddPage();
    
    // Remove margens apenas da última página (igual à primeira)
    $pdf->SetMargins(0, 0, 0);
    $pdf->SetAutoPageBreak(FALSE);
    
    $pageWidth = $pdf->getPageWidth();
    $pageHeight = $pdf->getPageHeight();
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Rect(0, 0, $pageWidth, $pageHeight, 'F');
    
    // Logo centralizado na capa final
    desenharLogoProjeto($pdf, $pageWidth / 2, $pageHeight / 2);
    
    // Limpar qualquer output antes de enviar o PDF
    ob_end_clean();
    
    // Gera o PDF
    $pdf->Output('relatorio_pre_anestesico_' . str_replace(' ', '_', $paciente['nome']) . '.pdf', 'D');
    exit;
}

function desenharCabecalho($pdf, $pageWidth, $pageHeight) {
    // Header azul maior
    $alturaHeader = 70; // Aumentado de 50 para 70
    $pdf->SetFillColor(60, 120, 216);
    $pdf->Rect(0, 0, $pageWidth, $alturaHeader, 'F');
    
    // Título "Relatório Pré-anestésico" no header azul (centralizado, maior)
    $pdf->SetFont('helvetica', 'B', 24); // Aumentado de 20 para 24
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetY(20); // Ajustado para o header maior
    $pdf->Cell(0, 12, 'Relatório Pré-anestésico', 0, 1, 'C');
    
    // Tag "Beta" abaixo do título (centralizado, levemente à direita)
    $pdf->SetFont('helvetica', '', 11); // Aumentado de 10 para 11
    $pdf->SetTextColor(220, 220, 220);
    $pdf->SetY(38); // Ajustado para o header maior
    $pdf->SetX($pageWidth / 2 + 50); // Ajustado para ficar mais à direita
    $pdf->Cell(30, 5, 'Beta', 0, 1, 'L');
    
    // Logo "ANESTESIOCHECK" no canto superior direito do header
    $pdf->SetFont('helvetica', 'B', 14); // Aumentado de 12 para 14
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetXY($pageWidth - 65, 15); // Ajustado para o header maior
    $pdf->Cell(60, 10, 'ANESTESIOCHECK', 0, 1, 'R');
    
    // Linha cinza abaixo do header
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->SetLineWidth(0.5);
    $pdf->Line(0, $alturaHeader, $pageWidth, $alturaHeader);
    
    // Retorna a altura do header para ajustar o conteúdo abaixo
    return $alturaHeader;
}

function calcularEstatisticas($respostas) {
    $estatisticas = [
        'sim' => 0,
        'nao' => 0,
        'totalPerguntas' => 0,
        'tipos' => [
            'boolean' => 0,
            'text' => 0,
            'choice' => 0,
            'checkbox' => 0
        ]
    ];
    
    foreach ($respostas as $video) {
        foreach ($video['answers'] as $answer) {
            $estatisticas['totalPerguntas']++;
            
            // Conta tipos
            if (isset($estatisticas['tipos'][$answer['type']])) {
                $estatisticas['tipos'][$answer['type']]++;
            }
            
            // Conta Sim/Não
            if ($answer['type'] === 'boolean') {
                if ($answer['answer'] === 'Sim' || $answer['answer'] === 'Vamos Começar!!') {
                    $estatisticas['sim']++;
                } else {
                    $estatisticas['nao']++;
                }
            }
        }
    }
    
    return $estatisticas;
}

/**
 * Organiza as respostas dos vídeos por categoria médica
 * 
 * MAPEAMENTO DE VÍDEOS PARA CATEGORIAS:
 * 
 * 1. INTRODUÇÃO E HISTÓRICO CLÍNICO:
 *    - video_1: Introdução
 *    - video_2: Histórico Clínico
 * 
 * 2. AVALIAÇÃO CARDIOVASCULAR:
 *    - video_3: Avaliação Cardiovascular
 *    - video_4: Avaliação de Ritmo Cardíaco
 *    - video_5: Avaliação de Doenças Metabólicas
 * 
 * 3. AVALIAÇÃO RESPIRATÓRIA:
 *    - video_7: Avaliação Respiratória
 *    - video_8: Avaliação de Sintomas Respiratórios
 * 
 * 4. AVALIAÇÃO NEUROLÓGICA E PSIQUIÁTRICA:
 *    - video_9: Avaliação Neurológica e Psiquiátrica
 * 
 * 5. AVALIAÇÃO GERAL E OUTRAS CONDIÇÕES:
 *    - video_6: Outras Doenças
 *    - video_10: Alergias
 *    - video_11: Medicamentos e Drogas
 *    - video_12: Histórico de Câncer e Perda de Peso
 *    - video_13: Avaliação Física
 * 
 * 6. EXAMES E CLASSIFICAÇÕES:
 *    - video_14: Classificação de Mallampati
 *    - video_15: Exames Disponíveis
 */
function organizarPorCategoria($respostas) {
    $categorias = [
        '1. INTRODUÇÃO E HISTÓRICO CLÍNICO' => [],
        '2. AVALIAÇÃO CARDIOVASCULAR' => [],
        '3. AVALIAÇÃO RESPIRATÓRIA' => [],
        '4. AVALIAÇÃO NEUROLÓGICA E PSIQUIÁTRICA' => [],
        '5. AVALIAÇÃO GERAL E OUTRAS CONDIÇÕES' => [],
        '6. EXAMES E CLASSIFICAÇÕES' => []
    ];
    
    foreach ($respostas as $video) {
        $videoId = $video['videoId'];
        
        if (in_array($videoId, ['video_1', 'video_2'])) {
            $categorias['1. INTRODUÇÃO E HISTÓRICO CLÍNICO'][] = $video;
        } elseif (in_array($videoId, ['video_3', 'video_4', 'video_5'])) {
            $categorias['2. AVALIAÇÃO CARDIOVASCULAR'][] = $video;
        } elseif (in_array($videoId, ['video_7', 'video_8'])) {
            $categorias['3. AVALIAÇÃO RESPIRATÓRIA'][] = $video;
        } elseif (in_array($videoId, ['video_9'])) {
            $categorias['4. AVALIAÇÃO NEUROLÓGICA E PSIQUIÁTRICA'][] = $video;
        } elseif (in_array($videoId, ['video_6', 'video_10', 'video_11', 'video_12', 'video_13'])) {
            $categorias['5. AVALIAÇÃO GERAL E OUTRAS CONDIÇÕES'][] = $video;
        } elseif (in_array($videoId, ['video_14', 'video_15'])) {
            $categorias['6. EXAMES E CLASSIFICAÇÕES'][] = $video;
        }
    }
    
    // Remove categorias vazias
    return array_filter($categorias, function($videos) {
        return !empty($videos);
    });
}

function gerarHTMLRelatorio($paciente, $respostas, array $metricas) {
    // Fallback: gera HTML que pode ser impresso como PDF pelo navegador
    header('Content-Type: text/html; charset=utf-8');
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Relatório Pré-anestésico - <?php echo htmlspecialchars($paciente['nome']); ?></title>
        <style>
            @media print {
                @page { 
                    margin: 0;
                    size: A4;
                }
                @page:first { 
                    margin: 0;
                }
                .page-break { 
                    page-break-after: always;
                    position: relative;
                    min-height: 100vh;
                    margin-bottom: 0;
                    box-sizing: border-box;
                    overflow: visible;
                }
                * {
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                    color-adjust: exact !important;
                }
                .capa {
                    background: #ffffff !important;
                    margin: 0 !important;
                    padding: 0 !important;
                }
                .page-footer {
                    position: absolute !important;
                    bottom: 0 !important;
                    left: 0 !important;
                    right: 0 !important;
                    width: 100% !important;
                    height: 50px !important;
                    display: flex !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                    color-adjust: exact !important;
                    page-break-inside: avoid !important;
                    break-inside: avoid !important;
                }
                /* Garantir que o conteúdo não seja cortado pelo rodapé */
                .page-break:not(.capa):not(.capa-final) {
                    padding-bottom: 50px !important;
                }
                .page-footer .page-number {
                    color: #5D9CEC !important;
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }
                .capa .page-footer,
                .capa-final .page-footer {
                    display: none !important;
                }
            }
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                color: #2c3e50;
            }
            .capa {
                background: #fff;
                min-height: 100vh;
                height: 100vh;
                padding: 0;
                margin: 0;
                position: relative;
                color: #0d6efd;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .capa > div {
                margin: 0;
                padding: 0;
            }
            .logo-wrapper {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-bottom: 30px;
            }
            .logo-wrapper img {
                width: 420px;
                max-width: 75vw;
                height: auto;
            }
            .capa h1 {
                color: white;
                font-size: 48px;
                font-weight: bold;
                margin: 0 0 10px 0;
                text-align: center;
                letter-spacing: 1px;
                position: relative;
                z-index: 2;
            }
            .capa h2 {
                color: white;
                font-size: 20px;
                font-weight: normal;
                text-align: center;
                margin: 0;
                position: relative;
                z-index: 2;
            }
            .categoria {
                margin: 30px 0;
            }
            .categoria h2 {
                background: #667eea;
                color: white;
                padding: 12px;
                margin: 0;
            }
            .video-section {
                margin: 20px 0;
                padding: 15px;
                border-left: 4px solid #667eea;
                background: #f8f9fa;
            }
            .video-title {
                color: #667eea;
                font-weight: bold;
                font-size: 14px;
                margin-bottom: 10px;
            }
            .pergunta {
                margin: 10px 0;
            }
            .pergunta strong {
                color: #2c3e50;
            }
            .resposta {
                margin-left: 20px;
                margin-top: 5px;
            }
            .resposta-sim {
                color: #28a745;
                font-weight: bold;
            }
            .resposta-nao {
                color: #dc3545;
                font-weight: bold;
            }
            .rodape {
                text-align: center;
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #ddd;
                color: #6c757d;
                font-size: 12px;
            }
            .page-footer {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 50px;
                border-top: 1px solid #5D9CEC;
                background: white;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0 30px;
                font-family: Arial, sans-serif;
                z-index: 1000;
                box-sizing: border-box;
            }
            /* Garantir que páginas com rodapé tenham altura suficiente */
            .page-break:not(.capa):not(.capa-final) {
                min-height: 100vh;
                position: relative;
            }
            .page-footer .page-number {
                color: #5D9CEC;
                font-size: 14px;
                font-weight: 500;
            }
        </style>
    </head>
    <body>
        <!-- CAPA INICIAL -->
        <div class="capa page-break">
            <div class="logo-wrapper">
                <img src="<?= PROJECT_LOGO_URL ?>" alt="Logotipo NutriCheck">
            </div>
            <div class="capa-infos" style="text-align: center; margin-top: 30px; font-family: 'Segoe UI', Arial, sans-serif;">
                <h1 style="color: #0078d7; margin: 0; font-size: 28px; font-weight: 600;">Relatório Pré-Anestésico</h1>
                <p style="margin: 12px 0 6px; font-size: 16px; color: #555;">Paciente:</p>
                <p style="margin: 0; font-size: 20px; font-weight: 600; color: #222;"><?php echo htmlspecialchars($paciente['nome']); ?></p>
                <?php if (!empty($paciente['medicoResponsavel'])): ?>
                    <p style="margin: 18px 0 0; font-size: 14px; color: #555;">Nutricionista Responsável:</p>
                    <p style="margin: 0; font-size: 16px; font-weight: 500; color: #222;"><?php echo htmlspecialchars($paciente['medicoResponsavel']); ?></p>
                <?php endif; ?>
                <p style="margin: 18px 0 0; font-size: 14px; color: #555;">Data da Entrevista:</p>
                <p style="margin: 0; font-size: 16px; font-weight: 500; color: #222;"><?php echo htmlspecialchars($paciente['dataEntrevista']); ?></p>
            </div>
        </div>
        
        <!-- PÁGINA DE DADOS DO PACIENTE (estilo Examination Report) -->
        <div class="page-break" style="background: white; position: relative;">
            <div class="page-footer">
                <div></div>
                <div class="page-number"><span class="current-page">1</span>/<span class="total-pages">1</span></div>
            </div>
            <!-- Header azul completo (maior) -->
            <div style="background: #3c78d8 !important; background-color: #3c78d8 !important; color: white; padding: 14px 20px; margin: 0; min-height: 52px; position: relative; -webkit-print-color-adjust: exact; print-color-adjust: exact; color-adjust: exact;">
                <div style="text-align: center; margin-bottom: 2px;">
                    <h2 style="color: white; font-size: 24px; font-weight: bold; margin: 0;">Relatório Pré-anestésico</h2>
                    <span style="font-size: 10px; color: #dcdcdc; margin-left: 16px;">Beta</span>
                </div>
                <div style="position: absolute; top: 16px; right: 20px; font-size: 13px; font-weight: bold;">
                    ANESTESIOCHECK
                </div>
            </div>
            
            <!-- Linha cinza abaixo do header -->
            <div style="border-bottom: 1px solid #c8c8c8; margin: 0;"></div>
            
            <!-- Dados do paciente em duas colunas (formato do modelo) -->
            <div style="padding: 25px 30px; background: white;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; padding: 6px 0; vertical-align: top;">
                            <div style="color: #969696; font-size: 9px; margin-bottom: 2px; font-family: Arial, sans-serif;">Paciente</div>
                            <div style="color: #000000; font-size: 11px; font-weight: normal; font-family: Arial, sans-serif;"><?php echo htmlspecialchars($paciente['nome']); ?></div>
                        </td>
                        <td style="width: 50%; padding: 6px 0; vertical-align: top;">
                            <div style="color: #969696; font-size: 9px; margin-bottom: 2px; font-family: Arial, sans-serif;">Data de Nascimento</div>
                            <div style="color: #000000; font-size: 11px; font-weight: normal; font-family: Arial, sans-serif;"><?php echo htmlspecialchars($paciente['dataNascimento']); ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 9px 0 6px 0; vertical-align: top;">
                            <div style="color: #969696; font-size: 9px; margin-bottom: 2px; font-family: Arial, sans-serif;">Data da Entrevista</div>
                            <div style="color: #000000; font-size: 11px; font-weight: normal; font-family: Arial, sans-serif;"><?php echo htmlspecialchars($paciente['dataEntrevista']); ?></div>
                        </td>
                        <td style="padding: 9px 0 6px 0; vertical-align: top;">
                            <div style="color: #969696; font-size: 9px; margin-bottom: 2px; font-family: Arial, sans-serif;">Idade</div>
                            <div style="color: #000000; font-size: 11px; font-weight: normal; font-family: Arial, sans-serif;"><?php echo htmlspecialchars(formatarIdadeTexto($paciente['idade'])); ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 9px 0 6px 0; vertical-align: top;">
                            <div style="color: #969696; font-size: 9px; margin-bottom: 2px; font-family: Arial, sans-serif;">CPF</div>
                            <div style="color: #000000; font-size: 11px; font-weight: normal; font-family: Arial, sans-serif;"><?php echo htmlspecialchars($paciente['cpf']); ?></div>
                        </td>
                        <td style="padding: 9px 0 6px 0; vertical-align: top;">
                            <div style="color: #969696; font-size: 9px; margin-bottom: 2px; font-family: Arial, sans-serif;">Nutricionista Responsável</div>
                            <div style="color: #000000; font-size: 11px; font-weight: normal; font-family: Arial, sans-serif;"><?php echo htmlspecialchars($paciente['medicoResponsavel']); ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 9px 0 6px 0; vertical-align: top;">
                            <div style="color: #969696; font-size: 9px; margin-bottom: 2px; font-family: Arial, sans-serif;">Procedimento</div>
                            <div style="color: #000000; font-size: 11px; font-weight: normal; font-family: Arial, sans-serif;"><?php echo htmlspecialchars($paciente['procedimentoNome']); ?></div>
                        </td>
                        <td style="padding: 9px 0 6px 0; vertical-align: top;">
                            <div style="color: #969696; font-size: 9px; margin-bottom: 2px; font-family: Arial, sans-serif;">Data e Hora do Procedimento</div>
                            <div style="color: #000000; font-size: 11px; font-weight: normal; font-family: Arial, sans-serif;"><?php echo htmlspecialchars($paciente['dataHoraProcedimento']); ?></div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Linha cinza separadora -->
            <div style="border-bottom: 1px solid #c8c8c8; margin: 0;"></div>
            
            <!-- SEÇÃO DE ANÁLISE VISUAL DAS RESPOSTAS -->
            <div style="padding: 25px 30px; background: white;">
                <h3 style="color: #3c78d8; font-size: 14px; font-weight: bold; margin: 0 0 15px 0;">Análise da Entrevista</h3>
                
                <?php
                $perguntasRespondidas = $metricas['perguntasRespondidas'];
                $totalPerguntas = $metricas['totalPerguntas'];
                $tempoTotalVideos = $metricas['tempoTotalVideos'];
                $percentualCompleto = $metricas['percentualCompleto'];
                $tempoMedioPorPergunta = $metricas['tempoMedioPorPergunta'];
                $ultimoVideoRespondido = $metricas['ultimoVideoRespondido'] ?? null;
                $videosCompletos = $metricas['videosCompletos'] ?? 0;
                $totalVideosEsperados = $metricas['totalVideosEsperados'] ?? 15;
                $completouTodosVideos = $metricas['completouTodosVideos'] ?? false;
                
                // Calcula risco baseado nas respostas reais
                $respostasSim = 0;
                $respostasNao = 0;
                foreach ($respostas as $video) {
                    foreach ($video['answers'] as $answer) {
                        if ($answer['type'] === 'boolean') {
                            if ($answer['answer'] === 'Sim' || $answer['answer'] === 'Vamos Começar!!') {
                                $respostasSim++;
                            } else {
                                $respostasNao++;
                            }
                        }
                    }
                }
                
                $risco = 'Baixo';
                $corRisco = '#28a745';
                $descricaoRisco = 'Paciente apresenta perfil de baixo risco anestésico. Avaliação pré-operatória padrão recomendada.';
                
                if ($respostasSim > 15) {
                    $risco = 'Alto';
                    $corRisco = '#dc3545';
                    $descricaoRisco = 'Paciente apresenta múltiplos fatores de risco. Recomenda-se avaliação pré-operatória detalhada e acompanhamento pelo nutricionista antes do procedimento.';
                } elseif ($respostasSim > 8) {
                    $risco = 'Médio';
                    $corRisco = '#ffc107';
                    $descricaoRisco = 'Paciente apresenta alguns fatores de risco. Sugere-se avaliação pré-operatória adicional antes do procedimento.';
                }
                
                $recomendacaoTexto = 'Com base nas respostas fornecidas, o sistema sugere que ';
                if ($risco === 'Alto') {
                    $recomendacaoTexto .= 'o paciente seja avaliado presencialmente pelo nutricionista antes do procedimento, devido aos múltiplos fatores de risco identificados.';
                } elseif ($risco === 'Médio') {
                    $recomendacaoTexto .= 'seja realizada uma avaliação pré-operatória adicional com o nutricionista para discussão dos fatores de risco identificados.';
                } else {
                    $recomendacaoTexto .= 'o paciente está apto para o procedimento conforme protocolo padrão de avaliação pré-anestésica.';
                }
                ?>
                
                <!-- Cards de métricas -->
                <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
                    <!-- Card 1: Perguntas Respondidas -->
                    <div style="background: #667eea; color: white; padding: 12px; border-radius: 8px; flex: 1; min-width: 80px; text-align: center;">
                        <div style="font-size: 20px; font-weight: bold; margin-bottom: 5px;"><?php echo $perguntasRespondidas; ?>/<?php echo $totalPerguntas; ?></div>
                        <div style="font-size: 9px;">Perguntas</div>
                        <div style="font-size: 9px;">Respondidas</div>
                    </div>
                    
                    <!-- Card 2: Tempo Total -->
                    <div style="background: #28a745; color: white; padding: 12px; border-radius: 8px; flex: 1; min-width: 80px; text-align: center;">
                        <div style="font-size: 20px; font-weight: bold; margin-bottom: 5px;"><?php echo $tempoTotalVideos; ?> min</div>
                        <div style="font-size: 9px;">Tempo Total</div>
                        <div style="font-size: 9px;">de Vídeos</div>
                    </div>
                    
                    <!-- Card 3: Percentual Completo -->
                    <div style="background: #ffc107; color: #000; padding: 12px; border-radius: 8px; flex: 1; min-width: 80px; text-align: center;">
                        <div style="font-size: 20px; font-weight: bold; margin-bottom: 5px;"><?php echo $percentualCompleto; ?>%</div>
                        <div style="font-size: 9px;">Questionário</div>
                        <div style="font-size: 9px;">Completo</div>
                    </div>
                    
                    <!-- Card 4: Tempo Médio -->
                    <div style="background: #ff5722; color: white; padding: 12px; border-radius: 8px; flex: 1; min-width: 80px; text-align: center;">
                        <div style="font-size: 18px; font-weight: bold; margin-bottom: 5px;"><?php echo $tempoMedioPorPergunta; ?> min</div>
                        <div style="font-size: 9px;">Tempo Médio</div>
                        <div style="font-size: 9px;">por Pergunta</div>
                    </div>
                </div>
                
                <?php if (!$completouTodosVideos && $ultimoVideoRespondido): ?>
                <!-- Observação sobre questionário incompleto -->
                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; margin-bottom: 20px; border-radius: 4px;">
                    <div style="font-size: 11px; font-weight: bold; color: #856404; margin-bottom: 5px;">
                        ⚠️ Observação: Questionário Incompleto
                    </div>
                    <div style="font-size: 10px; color: #856404; line-height: 1.4;">
                        O paciente respondeu até o vídeo <strong><?php echo htmlspecialchars($ultimoVideoRespondido['videoTitle'] ?? $ultimoVideoRespondido['videoId']); ?></strong> 
                        (<?php echo $videosCompletos; ?> de <?php echo $totalVideosEsperados; ?> vídeos completos). 
                        O tempo total calculado é baseado no período entre o início (vídeo 1 - Introdução) e o último vídeo respondido.
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Classificação de Risco -->
                <h4 style="color: #2c3e50; font-size: 12px; font-weight: bold; margin: 20px 0 10px 0;">Classificação de Risco Anestésico (IA)</h4>
                
                <div style="border: 2px solid <?php echo $corRisco; ?>; border-radius: 8px; padding: 15px; margin-bottom: 20px; background: white;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <div style="width: 16px; height: 16px; background: <?php echo $corRisco; ?>; border-radius: 50%;"></div>
                        <div style="font-size: 16px; font-weight: bold; color: <?php echo $corRisco; ?>;">Risco: <?php echo $risco; ?></div>
                    </div>
                    <div style="font-size: 10px; color: #2c3e50; line-height: 1.4;">
                        <?php echo htmlspecialchars($descricaoRisco); ?>
                    </div>
                </div>
                
                <!-- Barra de progresso -->
                <div style="margin: 15px 0;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span style="font-size: 10px; font-weight: bold; color: #2c3e50;">Progresso do Questionário:</span>
                        <span style="font-size: 10px; color: #2c3e50;"><?php echo $percentualCompleto; ?>%</span>
                    </div>
                    <div style="width: 100%; height: 10px; background: #e6e6e6; border-radius: 5px; overflow: hidden;">
                        <div style="width: <?php echo $percentualCompleto; ?>%; height: 100%; background: #28a745; border-radius: 5px;"></div>
                    </div>
                </div>
                
                <!-- Recomendação do Sistema -->
                <div style="margin-top: 20px;">
                    <h4 style="color: #3c78d8; font-size: 10px; font-weight: bold; margin-bottom: 10px;">Recomendação do Sistema:</h4>
                    <div style="background: #f0f8ff; border: 1px solid #667eea; border-radius: 8px; padding: 12px;">
                        <div style="display: flex; gap: 10px;">
                            <span style="font-size: 14px;">🤖</span>
                            <div style="font-size: 9px; color: #2c3e50; line-height: 1.5;">
                                <?php echo htmlspecialchars($recomendacaoTexto); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- PÁGINAS DE VISUALIZAÇÃO DAS RESPOSTAS PARA O ANESTESISTA -->
        <?php
        $categorias = organizarPorCategoria($respostas);
        $categoriasArray = [];
        foreach ($categorias as $categoriaNome => $videos) {
            $categoriasArray[] = ['nome' => $categoriaNome, 'videos' => $videos];
        }
        
        // Agrupar categorias de 2 em 2
        $categoriasAgrupadas = array_chunk($categoriasArray, 2);
        $pageIndex = 0;
        
        foreach ($categoriasAgrupadas as $grupoCategorias):
            $pageIndex++;
        ?>
        <div class="page-break" style="background: white; padding: 0; position: relative;">
            <div class="page-footer">
                <div></div>
                <div class="page-number"><span class="current-page"><?php echo (1 + $pageIndex); ?></span>/<span class="total-pages">1</span></div>
            </div>
            <!-- Header azul completo (igual ao da página 2) -->
            <div style="background: #3c78d8 !important; background-color: #3c78d8 !important; color: white; padding: 14px 20px; margin: 0; min-height: 52px; position: relative; -webkit-print-color-adjust: exact; print-color-adjust: exact; color-adjust: exact;">
                <div style="text-align: center; margin-bottom: 2px;">
                    <h2 style="color: white; font-size: 24px; font-weight: bold; margin: 0;">Relatório Pré-anestésico</h2>
                    <span style="font-size: 10px; color: #dcdcdc; margin-left: 16px;">Beta</span>
                </div>
                <div style="position: absolute; top: 16px; right: 20px; font-size: 13px; font-weight: bold;">
                    ANESTESIOCHECK
                </div>
            </div>
            
            <!-- Linha cinza abaixo do header -->
            <div style="border-bottom: 1px solid #c8c8c8; margin: 0;"></div>
            
            <!-- Conteúdo das respostas -->
            <div style="padding: 20px;">
                <?php foreach ($grupoCategorias as $categoria): ?>
                <div style="margin-bottom: 20px;">
                    <h2 style="background: #3c78d8; color: white; padding: 10px; margin: 0 0 15px 0; font-size: 14px; font-weight: bold;">
                        <?php echo htmlspecialchars($categoria['nome']); ?>
                    </h2>
                    
                    <?php foreach ($categoria['videos'] as $video): ?>
                        <div style="margin-bottom: 15px;">
                            <h3 style="color: #2c3e50; font-size: 11px; font-weight: bold; margin-bottom: 10px;">
                                <?php echo htmlspecialchars($video['videoTitle']); ?>
                            </h3>
                            
                            <?php foreach ($video['answers'] as $answer): ?>
                                <div style="margin-bottom: 12px; padding-left: 15px;">
                                    <div style="color: #6c757d; font-size: 9px; font-weight: bold; margin-bottom: 3px;">
                                        Pergunta <?php echo $answer['questionIndex']; ?>: <?php echo htmlspecialchars($answer['questionText']); ?>
                                    </div>
                                    <div style="font-size: 10px; color: <?php 
                                        if ($answer['type'] === 'boolean') {
                                            echo ($answer['answer'] === 'Sim' || $answer['answer'] === 'Vamos Começar!!') ? '#28a745' : '#dc3545';
                                        } else {
                                            echo '#2c3e50';
                                        }
                                    ?>; padding-left: 10px;">
                                        <strong>Resposta:</strong> <?php 
                                            if ($answer['type'] === 'checkbox' && is_array($answer['answer'])) {
                                                echo htmlspecialchars(implode(', ', $answer['answer']));
                                            } else {
                                                echo htmlspecialchars($answer['answer']);
                                            }
                                        ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        
        <!-- CAPA FINAL -->
        <div class="capa capa-final page-break" style="background: #fff; position: relative;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; text-align: center;">
                <div class="logo-wrapper" style="margin-bottom: 0;">
                    <img src="<?= PROJECT_LOGO_URL ?>" alt="Logotipo NutriCheck">
                </div>
            </div>
        </div>
        
        <script>
            // Calcular e atualizar numeração de páginas
            window.onload = function() {
                // Contar total de páginas (excluindo capa inicial e final)
                var pages = document.querySelectorAll('.page-break');
                var totalPages = pages.length - 2; // Exclui capa inicial e final
                
                // Atualizar todos os rodapés com o total correto
                var pageNumbers = document.querySelectorAll('.total-pages');
                pageNumbers.forEach(function(el) {
                    el.textContent = totalPages;
                });
                
                // Atualizar números das páginas individuais
                var currentPage = 1;
                pages.forEach(function(page, index) {
                    // Pular capa inicial (index 0) e capa final (último)
                    if (index > 0 && index < pages.length - 1) {
                        var footer = page.querySelector('.page-footer .current-page');
                        if (footer) {
                            footer.textContent = currentPage;
                            currentPage++;
                        }
                    }
                });
            };
            
            // Auto-print quando carregar (opcional - pode remover se não quiser)
            // window.onload = function() { window.print(); }
        </script>
    </body>
    </html>
    <?php
}
?>

