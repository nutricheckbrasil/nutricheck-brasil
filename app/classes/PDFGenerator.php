<?php
/**
 * Classe para geração de PDFs - NutriCheck
 * 
 * Esta classe gerencia a geração de relatórios PDF para avaliações pré-anestésicas,
 * utilizando a biblioteca FPDF.
 * 
 * Adaptada do sistema ConsetiCheck para o NutriCheck
 */

require_once __DIR__ . '/../lib/fpdf/fpdf.php';

class AnestesiaPDF extends FPDF {
    private $institution_data;
    
    public function setInstitutionData($data) {
        $this->institution_data = $data;
    }
    
    // Função para converter UTF-8 para caracteres compatíveis com FPDF
    private function convertText($text) {
        // Mapeamento manual de caracteres especiais
        $replacements = [
            'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
            'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ç' => 'c', 'ñ' => 'n',
            'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'Ä' => 'A',
            'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ô' => 'O', 'Ö' => 'O',
            'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'Ç' => 'C', 'Ñ' => 'N'
        ];
        
        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }
    
    // Cabeçalho
    function Header() {
        // Logo ou título
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 15, $this->convertText('RELATÓRIO DE AVALIAÇÃO PRÉ-ANESTÉSICA'), 0, 1, 'C');
        
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 8, $this->convertText('NutriCheck - Sistema de Nutrição Pré-Operatória'), 0, 1, 'C');
        
        if ($this->institution_data) {
            $this->SetFont('Arial', 'I', 10);
            $this->Cell(0, 6, $this->convertText($this->institution_data['nome'] ?? $this->institution_data['name'] ?? ''), 0, 1, 'C');
        }
        
        // Linha
        $this->Ln(5);
        $this->Line(20, $this->GetY(), 190, $this->GetY());
        $this->Ln(10);
    }
    
    // Rodapé
    function Footer() {
        $this->SetY(-30);
        $this->Line(20, $this->GetY(), 190, $this->GetY());
        $this->Ln(3);
        
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 5, $this->convertText('Documento gerado automaticamente em ' . date('d/m/Y H:i:s')), 0, 1, 'C');
        $this->Cell(0, 5, $this->convertText('Este documento é válido para fins de auditoria e registro médico'), 0, 1, 'C');
        
        // Número da página
        $this->Cell(0, 5, $this->convertText('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
    
    // Adicionar seção com título
    function addSection($title) {
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 14);
        $this->SetFillColor(240, 240, 240);
        $this->Cell(0, 10, $this->convertText($title), 0, 1, 'L', true);
        $this->Ln(3);
    }
    
    // Adicionar campo de dados
    function addField($label, $value, $width = 50) {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell($width, 6, $this->convertText($label . ':'), 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, $this->convertText($value), 0, 1, 'L');
    }
    
    // Adicionar texto multilinha
    function addMultilineText($text, $maxWidth = 170) {
        $this->SetFont('Arial', '', 9);
        
        // Converter texto
        $text = $this->convertText($text);
        
        // Quebrar texto em linhas
        $words = explode(' ', $text);
        $line = '';
        
        foreach ($words as $word) {
            $testLine = $line . $word . ' ';
            $testWidth = $this->GetStringWidth($testLine);
            
            if ($testWidth > $maxWidth && $line !== '') {
                $this->Cell(0, 5, trim($line), 0, 1, 'L');
                $line = $word . ' ';
            } else {
                $line = $testLine;
            }
        }
        
        if ($line !== '') {
            $this->Cell(0, 5, trim($line), 0, 1, 'L');
        }
    }
    
    // Sobrescrever Cell para sempre converter texto
    function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '') {
        if (is_string($txt)) {
            $txt = $this->convertText($txt);
        }
        parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }
    
    // Sobrescrever MultiCell para sempre converter texto
    function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false) {
        if (is_string($txt)) {
            $txt = $this->convertText($txt);
        }
        parent::MultiCell($w, $h, $txt, $border, $align, $fill);
    }
}

class PDFGenerator {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Gerar PDF de avaliação pré-anestésica
     * 
     * @param int $patient_id ID do paciente
     * @param int $institution_id ID da instituição
     * @param string $email_destinatario Email para envio (opcional)
     * @return array Resultado da geração
     */
    public function generateEvaluationPDF($patient_id, $institution_id, $email_destinatario = null) {
        try {
            // Buscar dados do paciente
            $patient_data = $this->getPatientData($patient_id);
            if (!$patient_data) {
                throw new Exception("Paciente não encontrado com ID: " . $patient_id);
            }
            
            // Buscar dados da instituição
            $institution_data = $this->getInstitutionData($institution_id);
            if (!$institution_data) {
                throw new Exception("Instituição não encontrada com ID: " . $institution_id);
            }
            
            // Buscar dados da avaliação
            $evaluation_data = $this->getEvaluationData($patient_id, $institution_id);
            
            // Criar PDF
            $pdf = new AnestesiaPDF();
            $pdf->setInstitutionData($institution_data);
            $pdf->AliasNbPages();
            $pdf->AddPage();
            
            // Dados do paciente
            $pdf->addSection('DADOS DO PACIENTE');
            $this->addPatientSection($pdf, $patient_data);
            
            // Dados da instituição
            $pdf->addSection('DADOS DA INSTITUIÇÃO');
            $this->addInstitutionSection($pdf, $institution_data);
            
            // Dados da avaliação
            if ($evaluation_data) {
                $pdf->addSection('DADOS DA AVALIAÇÃO');
                $this->addEvaluationSection($pdf, $evaluation_data);
            }
            
            // Informações de auditoria
            $pdf->addSection('INFORMAÇÕES DE AUDITORIA');
            $this->addAuditSection($pdf, $patient_id, $institution_id);
            
            // Gerar nome do arquivo
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "avaliacao_paciente_{$patient_id}_instituicao_{$institution_id}_{$timestamp}.pdf";
            
            // Criar diretório se não existir
            $dir = BASE_PATH . '/public/uploads/pdfs';
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            $filepath = $dir . '/' . $filename;
            
            // Salvar PDF
            $pdf->Output('F', $filepath);
            
            // Enviar email se destinatário foi fornecido
            $email_sent = false;
            if ($email_destinatario) {
                $email_sent = $this->sendPDFByEmail(
                    $email_destinatario,
                    $patient_data,
                    $institution_data,
                    $evaluation_data,
                    $filepath,
                    $filename
                );
            }
            
            // Log da operação
            $this->logPDFGeneration($patient_id, $institution_id, $filename, $email_sent, $email_destinatario);
            
            return [
                'success' => true,
                'filename' => $filename,
                'filepath' => $filepath,
                'url' => '/public/uploads/pdfs/' . $filename,
                'email_sent' => $email_sent,
                'email_destinatario' => $email_destinatario,
                'patient_data' => $patient_data,
                'institution_data' => $institution_data
            ];
            
        } catch (Exception $e) {
            if (function_exists('logError')) {
                logError("Erro ao gerar PDF", [
                    'patient_id' => $patient_id,
                    'institution_id' => $institution_id,
                    'error' => $e->getMessage()
                ]);
            }
            throw $e;
        }
    }
    
    /**
     * Buscar dados do paciente
     */
    private function getPatientData($patient_id) {
        $sql = "SELECT * FROM pacientes WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$patient_id]);
        return $stmt->fetch();
    }
    
    /**
     * Buscar dados da instituição
     */
    private function getInstitutionData($institution_id) {
        $sql = "SELECT * FROM instituicoes WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$institution_id]);
        return $stmt->fetch();
    }
    
    /**
     * Buscar dados da avaliação
     */
    private function getEvaluationData($patient_id, $institution_id) {
        $sql = "SELECT * FROM avaliacoes WHERE paciente_id = ? AND instituicao_id = ? ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$patient_id, $institution_id]);
        return $stmt->fetch();
    }
    
    /**
     * Adicionar seção de dados do paciente ao PDF
     */
    private function addPatientSection($pdf, $patient_data) {
        $fields = [
            'Nome Completo' => $patient_data['nome'] ?? $patient_data['name'] ?? 'N/A',
            'Email' => $patient_data['email'] ?? 'N/A',
            'Telefone' => $patient_data['telefone'] ?? $patient_data['phone'] ?? 'N/A',
            'CPF' => $patient_data['cpf'] ?? 'N/A',
            'Data de Nascimento' => isset($patient_data['data_nascimento']) ? date('d/m/Y', strtotime($patient_data['data_nascimento'])) : 'N/A',
            'Endereço' => $patient_data['endereco'] ?? $patient_data['address'] ?? 'N/A',
            'Cidade' => $patient_data['cidade'] ?? $patient_data['city'] ?? 'N/A',
            'Estado' => $patient_data['estado'] ?? $patient_data['state'] ?? 'N/A',
            'CEP' => $patient_data['cep'] ?? $patient_data['zip_code'] ?? 'N/A',
            'Data de Cadastro' => isset($patient_data['created_at']) ? date('d/m/Y H:i', strtotime($patient_data['created_at'])) : date('d/m/Y H:i')
        ];
        
        foreach ($fields as $label => $value) {
            $pdf->addField($label, $value);
        }
    }
    
    /**
     * Adicionar seção de dados da instituição ao PDF
     */
    private function addInstitutionSection($pdf, $institution_data) {
        $fields = [
            'Nome da Instituição' => $institution_data['nome'] ?? $institution_data['name'] ?? 'N/A',
            'Email Institucional' => $institution_data['email'] ?? 'N/A',
            'Telefone' => $institution_data['telefone'] ?? $institution_data['phone'] ?? 'N/A',
            'CNPJ' => $institution_data['cnpj'] ?? 'N/A',
            'Endereço' => $institution_data['endereco'] ?? $institution_data['address'] ?? 'N/A',
            'Cidade' => $institution_data['cidade'] ?? $institution_data['city'] ?? 'N/A',
            'Estado' => $institution_data['estado'] ?? $institution_data['state'] ?? 'N/A'
        ];
        
        foreach ($fields as $label => $value) {
            $pdf->addField($label, $value);
        }
    }
    
    /**
     * Adicionar seção de dados da avaliação ao PDF
     */
    private function addEvaluationSection($pdf, $evaluation_data) {
        $fields = [
            'Data da Avaliação' => isset($evaluation_data['created_at']) ? date('d/m/Y H:i', strtotime($evaluation_data['created_at'])) : date('d/m/Y H:i'),
            'Status' => $evaluation_data['status'] ?? 'Concluída',
            'Classificação ASA' => $evaluation_data['classificacao_asa'] ?? 'N/A',
            'Peso (kg)' => $evaluation_data['peso'] ?? 'N/A',
            'Altura (cm)' => $evaluation_data['altura'] ?? 'N/A',
            'IMC' => $evaluation_data['imc'] ?? 'N/A'
        ];
        
        foreach ($fields as $label => $value) {
            $pdf->addField($label, $value);
        }
        
        // Adicionar observações se existirem
        if (!empty($evaluation_data['observacoes'])) {
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 6, 'Observações:', 0, 1, 'L');
            $pdf->addMultilineText($evaluation_data['observacoes']);
        }
    }
    
    /**
     * Adicionar seção de auditoria ao PDF
     */
    private function addAuditSection($pdf, $patient_id, $institution_id) {
        $fields = [
            'Data de Geração' => date('d/m/Y H:i:s'),
            'ID do Paciente' => $patient_id,
            'ID da Instituição' => $institution_id,
            'Hash de Verificação' => md5($patient_id . $institution_id . time())
        ];
        
        foreach ($fields as $label => $value) {
            $pdf->addField($label, $value);
        }
    }
    
    /**
     * Enviar PDF por email
     */
    private function sendPDFByEmail($email_destinatario, $patient_data, $institution_data, $evaluation_data, $filepath, $filename) {
        try {
            require_once __DIR__ . '/EmailSender.php';
            
            $emailSender = new EmailSender();
            
            // Gerar template do email
            $email_body = $emailSender->generateEvaluationEmailTemplate(
                $patient_data,
                $institution_data,
                $evaluation_data ?? [],
                $filename
            );
            
            // Assunto do email
            $subject = "Relatório de Avaliação Pré-Anestésica - " . 
                      ($patient_data['nome'] ?? $patient_data['name'] ?? 'Paciente') . 
                      " - " . date('d/m/Y H:i');
            
            // Enviar email
            return $emailSender->sendEmailWithPDF(
                $email_destinatario,
                $subject,
                $email_body,
                $filepath,
                $filename
            );
            
        } catch (Exception $e) {
            if (function_exists('logError')) {
                logError("Erro ao enviar email com PDF", [
                    'email' => $email_destinatario,
                    'error' => $e->getMessage()
                ]);
            }
            return false;
        }
    }
    
    /**
     * Registrar log da geração de PDF
     */
    private function logPDFGeneration($patient_id, $institution_id, $filename, $email_sent, $email_destinatario) {
        $log_dir = BASE_PATH . '/logs';
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0755, true);
        }
        
        $log_entry = date('Y-m-d H:i:s') . 
                    " - PDF gerado" . 
                    ($email_sent ? " e email ENVIADO" : " mas email FALHOU") . 
                    ($email_destinatario ? " para {$email_destinatario}" : "") . 
                    " - Arquivo: {$filename}" . 
                    " - Paciente: {$patient_id}" . 
                    " - Instituição: {$institution_id}\n";
        
        file_put_contents($log_dir . '/pdf_generation.log', $log_entry, FILE_APPEND | LOCK_EX);
    }
}

