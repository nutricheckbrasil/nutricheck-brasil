<?php
/**
 * Relatório de Respostas do Paciente em PDF
 * 
 * Gera um PDF com todas as respostas do paciente ao vídeo interativo
 * Inclui: dados do paciente, instituição, perguntas, respostas, estatísticas
 * 
 * Uso: relatorio_respostas_pdf.php?sessao_id=49
 *   ou: relatorio_respostas_pdf.php?paciente_id=61&video_id=1
 */

require_once __DIR__ . '/app/config/constants.php';
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

// Verificar se biblioteca FPDF está disponível
if (!class_exists('FPDF')) {
    // Se não estiver, incluir versão local ou usar alternativa
    require_once __DIR__ . '/app/lib/fpdf/fpdf.php';
}

// Classe personalizada para o PDF
class RelatorioRespostasPDF extends FPDF
{
    private $paciente;
    private $instituicao;
    private $video;
    private $sessao;
    
    public function setPaciente($paciente) {
        $this->paciente = $paciente;
    }
    
    public function setInstituicao($instituicao) {
        $this->instituicao = $instituicao;
    }
    
    public function setVideo($video) {
        $this->video = $video;
    }
    
    public function setSessao($sessao) {
        $this->sessao = $sessao;
    }
    
    // Cabeçalho
    function Header()
    {
        // Logo (se existir)
        $logo_path = __DIR__ . '/public/img/logo.png';
        if (file_exists($logo_path)) {
            $this->Image($logo_path, 10, 6, 30);
        }
        
        // Título
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, utf8_decode('Relatório de Respostas - Vídeo Interativo'), 0, 1, 'C');
        
        // Instituição
        if ($this->instituicao) {
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 5, utf8_decode($this->instituicao['nome'] ?? 'Instituição'), 0, 1, 'C');
        }
        
        // Linha
        $this->Ln(5);
        $this->SetDrawColor(100, 100, 100);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);
    }
    
    // Rodapé
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128);
        
        // Data e hora de geração
        $this->Cell(0, 10, utf8_decode('Gerado em: ' . date('d/m/Y H:i:s')), 0, 0, 'L');
        
        // Número da página
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }
    
    // Informações do paciente
    function InfoPaciente()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(230, 230, 250);
        $this->Cell(0, 8, utf8_decode('Dados do Paciente'), 0, 1, 'L', true);
        $this->Ln(2);
        
        $this->SetFont('Arial', '', 10);
        
        // Nome
        $this->Cell(40, 6, utf8_decode('Nome:'), 0, 0);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 6, utf8_decode($this->paciente['nome'] ?? 'N/A'), 0, 1);
        
        $this->SetFont('Arial', '', 10);
        
        // Email
        if (!empty($this->paciente['email'])) {
            $this->Cell(40, 6, utf8_decode('E-mail:'), 0, 0);
            $this->Cell(0, 6, $this->paciente['email'], 0, 1);
        }
        
        // CPF
        if (!empty($this->paciente['cpf'])) {
            $this->Cell(40, 6, utf8_decode('CPF:'), 0, 0);
            $this->Cell(0, 6, $this->paciente['cpf'], 0, 1);
        }
        
        // Data de nascimento
        if (!empty($this->paciente['data_nascimento'])) {
            $this->Cell(40, 6, utf8_decode('Data de Nascimento:'), 0, 0);
            $this->Cell(0, 6, date('d/m/Y', strtotime($this->paciente['data_nascimento'])), 0, 1);
        }
        
        $this->Ln(3);
    }
    
    // Informações da sessão
    function InfoSessao()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(230, 250, 230);
        $this->Cell(0, 8, utf8_decode('Informações da Sessão'), 0, 1, 'L', true);
        $this->Ln(2);
        
        $this->SetFont('Arial', '', 10);
        
        // Vídeo
        $this->Cell(40, 6, utf8_decode('Vídeo:'), 0, 0);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 6, utf8_decode($this->video['titulo'] ?? 'N/A'), 0, 1);
        
        $this->SetFont('Arial', '', 10);
        
        // Data e hora
        if (!empty($this->sessao['created_at'])) {
            $this->Cell(40, 6, utf8_decode('Data/Hora:'), 0, 0);
            $this->Cell(0, 6, date('d/m/Y H:i:s', strtotime($this->sessao['created_at'])), 0, 1);
        }
        
        // IP
        if (!empty($this->sessao['ip_address'])) {
            $this->Cell(40, 6, utf8_decode('Endereço IP:'), 0, 0);
            $this->Cell(0, 6, $this->sessao['ip_address'], 0, 1);
        }
        
        // Dispositivo
        if (!empty($this->sessao['device_type'])) {
            $this->Cell(40, 6, utf8_decode('Dispositivo:'), 0, 0);
            $dispositivo = ucfirst($this->sessao['device_type']);
            $this->Cell(0, 6, utf8_decode($dispositivo), 0, 1);
        }
        
        // Navegador
        if (!empty($this->sessao['user_agent'])) {
            $this->Cell(40, 6, utf8_decode('Navegador:'), 0, 0);
            $this->MultiCell(0, 6, utf8_decode($this->getBrowserInfo($this->sessao['user_agent'])));
        }
        
        // Status
        if (!empty($this->sessao['status'])) {
            $this->Cell(40, 6, utf8_decode('Status:'), 0, 0);
            $status = ucfirst($this->sessao['status']);
            $this->Cell(0, 6, utf8_decode($status), 0, 1);
        }
        
        // Progresso
        if (isset($this->sessao['progresso'])) {
            $this->Cell(40, 6, utf8_decode('Progresso:'), 0, 0);
            $this->Cell(0, 6, $this->sessao['progresso'] . '%', 0, 1);
        }
        
        $this->Ln(3);
    }
    
    // Extrair informações do navegador
    private function getBrowserInfo($userAgent)
    {
        if (preg_match('/Firefox\/([0-9.]+)/', $userAgent, $matches)) {
            return 'Firefox ' . $matches[1];
        } elseif (preg_match('/Chrome\/([0-9.]+)/', $userAgent, $matches)) {
            return 'Chrome ' . $matches[1];
        } elseif (preg_match('/Safari\/([0-9.]+)/', $userAgent, $matches)) {
            return 'Safari ' . $matches[1];
        } elseif (preg_match('/Edge\/([0-9.]+)/', $userAgent, $matches)) {
            return 'Edge ' . $matches[1];
        }
        return substr($userAgent, 0, 50) . '...';
    }
    
    // Estatísticas
    function Estatisticas($stats)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(255, 250, 230);
        $this->Cell(0, 8, utf8_decode('Estatísticas'), 0, 1, 'L', true);
        $this->Ln(2);
        
        $this->SetFont('Arial', '', 10);
        
        // Total de perguntas
        $this->Cell(60, 6, utf8_decode('Total de Perguntas:'), 0, 0);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 6, $stats['total'], 0, 1);
        
        $this->SetFont('Arial', '', 10);
        
        // Respostas corretas
        $this->Cell(60, 6, utf8_decode('Respostas Corretas:'), 0, 0);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(0, 128, 0);
        $this->Cell(0, 6, $stats['corretas'], 0, 1);
        
        $this->SetTextColor(0);
        $this->SetFont('Arial', '', 10);
        
        // Respostas incorretas
        $this->Cell(60, 6, utf8_decode('Respostas Incorretas:'), 0, 0);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(255, 0, 0);
        $this->Cell(0, 6, $stats['incorretas'], 0, 1);
        
        $this->SetTextColor(0);
        $this->SetFont('Arial', '', 10);
        
        // Percentual de acerto
        $this->Cell(60, 6, utf8_decode('Percentual de Acerto:'), 0, 0);
        $this->SetFont('Arial', 'B', 10);
        $cor = $stats['percentual'] >= 70 ? [0, 128, 0] : ($stats['percentual'] >= 50 ? [255, 140, 0] : [255, 0, 0]);
        $this->SetTextColor($cor[0], $cor[1], $cor[2]);
        $this->Cell(0, 6, number_format($stats['percentual'], 1) . '%', 0, 1);
        
        $this->SetTextColor(0);
        $this->Ln(3);
    }
    
    // Tabela de respostas
    function TabelaRespostas($respostas)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(250, 230, 230);
        $this->Cell(0, 8, utf8_decode('Perguntas e Respostas'), 0, 1, 'L', true);
        $this->Ln(2);
        
        // Cabeçalho da tabela
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(10, 7, utf8_decode('#'), 1, 0, 'C', true);
        $this->Cell(80, 7, utf8_decode('Pergunta'), 1, 0, 'C', true);
        $this->Cell(60, 7, utf8_decode('Resposta'), 1, 0, 'C', true);
        $this->Cell(20, 7, utf8_decode('Status'), 1, 0, 'C', true);
        $this->Cell(20, 7, utf8_decode('Tempo'), 1, 1, 'C', true);
        
        // Dados
        $this->SetFont('Arial', '', 8);
        $num = 1;
        
        foreach ($respostas as $resp) {
            // Número
            $this->Cell(10, 6, $num++, 1, 0, 'C');
            
            // Pergunta (quebrar texto se necessário)
            $x = $this->GetX();
            $y = $this->GetY();
            $this->MultiCell(80, 6, utf8_decode($resp['texto_pergunta']), 1);
            $altura = $this->GetY() - $y;
            
            // Voltar para a linha
            $this->SetXY($x + 80, $y);
            
            // Resposta
            $this->MultiCell(60, 6, utf8_decode($resp['resposta']), 1);
            $altura2 = $this->GetY() - $y;
            $altura = max($altura, $altura2);
            
            // Voltar para a linha
            $this->SetXY($x + 140, $y);
            
            // Status (Correto/Incorreto)
            if ($resp['correta']) {
                $this->SetTextColor(0, 128, 0);
                $this->SetFont('Arial', 'B', 8);
                $status = 'Correto';
            } else {
                $this->SetTextColor(255, 0, 0);
                $this->SetFont('Arial', 'B', 8);
                $status = 'Incorreto';
            }
            $this->Cell(20, $altura, utf8_decode($status), 1, 0, 'C');
            
            $this->SetTextColor(0);
            $this->SetFont('Arial', '', 8);
            
            // Tempo
            $tempo = $resp['tempo_resposta'] . 's';
            $this->Cell(20, $altura, $tempo, 1, 1, 'C');
        }
        
        $this->Ln(3);
    }
}

// ============================================================================
// PROCESSAMENTO
// ============================================================================

try {
    $db = Database::getInstance();
    
    // Obter parâmetros
    $sessao_id = isset($_GET['sessao_id']) ? (int)$_GET['sessao_id'] : 0;
    $paciente_id = isset($_GET['paciente_id']) ? (int)$_GET['paciente_id'] : 0;
    $video_id = isset($_GET['video_id']) ? (int)$_GET['video_id'] : 0;
    
    // Buscar sessão
    if ($sessao_id > 0) {
        $sessao = $db->fetch("SELECT * FROM video_sessoes WHERE id = ?", [$sessao_id]);
        if (!$sessao) {
            die('Sessão não encontrada');
        }
        $paciente_id = $sessao['paciente_id'];
        $video_id = $sessao['video_id'];
    } elseif ($paciente_id > 0 && $video_id > 0) {
        // Buscar última sessão do paciente para este vídeo
        $sessao = $db->fetch(
            "SELECT * FROM video_sessoes 
             WHERE paciente_id = ? AND video_id = ? 
             ORDER BY created_at DESC LIMIT 1",
            [$paciente_id, $video_id]
        );
        if (!$sessao) {
            die('Nenhuma sessão encontrada para este paciente e vídeo');
        }
        $sessao_id = $sessao['id'];
    } else {
        die('Parâmetros inválidos. Use: ?sessao_id=X ou ?paciente_id=X&video_id=Y');
    }
    
    // Buscar dados do paciente
    $paciente = $db->fetch("SELECT * FROM pacientes WHERE id = ?", [$paciente_id]);
    if (!$paciente) {
        die('Paciente não encontrado');
    }
    
    // Buscar dados do vídeo
    $video = $db->fetch("SELECT * FROM videos_interativos WHERE id = ?", [$video_id]);
    if (!$video) {
        die('Vídeo não encontrado');
    }
    
    // Buscar instituição (se houver)
    $instituicao = null;
    if (!empty($paciente['instituicao_id'])) {
        $instituicao = $db->fetch("SELECT * FROM instituicoes WHERE id = ?", [$paciente['instituicao_id']]);
    }
    
    // Buscar respostas
    $respostas = $db->fetchAll(
        "SELECT 
            vr.*,
            vp.texto_pergunta,
            vp.tipo_pergunta
         FROM video_respostas vr
         JOIN video_perguntas vp ON vr.pergunta_id = vp.id
         WHERE vr.sessao_id = ?
         ORDER BY vr.answered_at ASC",
        [$sessao_id]
    );
    
    if (empty($respostas)) {
        die('Nenhuma resposta encontrada para esta sessão');
    }
    
    // Calcular estatísticas
    $total = count($respostas);
    $corretas = 0;
    foreach ($respostas as $resp) {
        if ($resp['correta']) {
            $corretas++;
        }
    }
    $incorretas = $total - $corretas;
    $percentual = $total > 0 ? ($corretas / $total) * 100 : 0;
    
    $stats = [
        'total' => $total,
        'corretas' => $corretas,
        'incorretas' => $incorretas,
        'percentual' => $percentual
    ];
    
    // ============================================================================
    // GERAR PDF
    // ============================================================================
    
    $pdf = new RelatorioRespostasPDF();
    $pdf->setPaciente($paciente);
    $pdf->setInstituicao($instituicao);
    $pdf->setVideo($video);
    $pdf->setSessao($sessao);
    
    $pdf->AliasNbPages();
    $pdf->AddPage();
    
    // Adicionar seções
    $pdf->InfoPaciente();
    $pdf->InfoSessao();
    $pdf->Estatisticas($stats);
    $pdf->TabelaRespostas($respostas);
    
    // Observações finais
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->SetTextColor(100);
    $pdf->MultiCell(0, 5, utf8_decode(
        "Este relatório foi gerado automaticamente pelo sistema NutriCheck. " .
        "As informações aqui contidas são confidenciais e protegidas pela LGPD."
    ));
    
    // Saída do PDF
    $filename = 'relatorio_respostas_' . $paciente['nome'] . '_' . date('Y-m-d_H-i-s') . '.pdf';
    $filename = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $filename);
    
    $pdf->Output('D', $filename); // D = Download, I = Inline (navegador)
    
} catch (Exception $e) {
    die('Erro ao gerar relatório: ' . $e->getMessage());
}
?>

