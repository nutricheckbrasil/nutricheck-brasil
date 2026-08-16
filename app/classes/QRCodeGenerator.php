<?php

class QRCodeGenerator {
    private $upload_path;
    
    public function __construct() {
        $this->upload_path = __DIR__ . '/../../public/uploads/qr_codes/';
        
        // Criar diretório se não existir
        if (!is_dir($this->upload_path)) {
            mkdir($this->upload_path, 0755, true);
        }
    }
    
    /**
     * Gerar QR Code
     */
    public function generate($url, $tipo, $entity_id) {
        // Gerar nome do arquivo
        $filename = 'qr_' . $tipo . '_' . $entity_id . '_' . time() . '.png';
        $filepath = $this->upload_path . $filename;
        
        // Usar biblioteca externa ou API para gerar QR Code
        // Por enquanto, vamos usar uma implementação simples
        
        // Tamanho do QR Code
        $size = 300;
        
        // Gerar QR Code usando API online gratuita
        $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . urlencode($url);
        
        // Baixar e salvar com timeout e user agent
        $context = stream_context_create([
            'http' => [
                'timeout' => 30,
                'user_agent' => 'AnestesiaCheck/1.0'
            ]
        ]);
        
        $qr_data = file_get_contents($qr_url, false, $context);
        
        if ($qr_data === false) {
            throw new Exception('Erro ao gerar QR Code: Não foi possível conectar com o serviço');
        }
        
        // Salvar arquivo
        if (file_put_contents($filepath, $qr_data) === false) {
            throw new Exception('Erro ao salvar arquivo QR Code: Verifique as permissões do diretório');
        }
        
        return 'uploads/qr_codes/' . $filename;
    }
    
    /**
     * Gerar QR Code para anestesista
     */
    public function generateAnestesistaQR($instituicao_id, $anestesista_id, $instituicao_slug) {
        $base_url = 'http://' . $_SERVER['HTTP_HOST'];
        $url = $base_url . '/p/' . $instituicao_slug . '/' . $anestesista_id;
        
        // Gerar código único
        $codigo = 'ANEST_' . $instituicao_id . '_' . $anestesista_id . '_' . time() . '_' . bin2hex(random_bytes(8));
        
        // Gerar arquivo QR Code
        $qr_path = $this->generate($url, 'anestesista', $anestesista_id);
        
        return [
            'codigo' => $codigo,
            'url' => $url,
            'arquivo_path' => $qr_path
        ];
    }
    
    /**
     * Validar se QR Code é válido
     */
    public function validateQR($codigo) {
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM qr_codes WHERE codigo = ? AND ativo = TRUE";
        $qr = $db->fetch($sql, [$codigo]);
        
        if (!$qr) {
            return false;
        }
        
        // Verificar se não expirou (se houver campo de expiração)
        // Por enquanto, todos são válidos se ativos
        
        return $qr;
    }
    
    /**
     * Desativar QR Code
     */
    public function deactivateQR($codigo) {
        $db = Database::getInstance();
        
        $sql = "UPDATE qr_codes SET ativo = FALSE WHERE codigo = ?";
        return $db->query($sql, [$codigo]);
    }
    
    /**
     * Obter estatísticas de uso do QR Code
     */
    public function getQRStats($qr_id) {
        $db = Database::getInstance();
        
        // Contar pacientes criados via este QR
        $sql = "SELECT COUNT(*) as count FROM pacientes WHERE qr_code_id = ?";
        $result = $db->fetch($sql, [$qr_id]);
        
        return [
            'total_pacientes' => $result['count']
        ];
    }
    
    /**
     * Limpar QR Codes antigos
     */
    public function cleanupOldQRCodes($days_old = 30) {
        $db = Database::getInstance();
        
        $sql = "
            SELECT arquivo_path 
            FROM qr_codes 
            WHERE ativo = FALSE 
            AND created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
        ";
        
        $old_qrs = $db->fetchAll($sql, [$days_old]);
        
        $deleted_files = 0;
        foreach ($old_qrs as $qr) {
            $full_path = __DIR__ . '/../../public/' . $qr['arquivo_path'];
            if (file_exists($full_path)) {
                if (unlink($full_path)) {
                    $deleted_files++;
                }
            }
        }
        
        // Deletar registros do banco
        $sql = "
            DELETE FROM qr_codes 
            WHERE ativo = FALSE 
            AND created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
        ";
        
        $db->query($sql, [$days_old]);
        
        return $deleted_files;
    }
    
    /**
     * Obter URL pública do QR Code
     */
    public function getPublicURL($arquivo_path) {
        $base_url = 'http://' . $_SERVER['HTTP_HOST'];
        return $base_url . '/' . $arquivo_path;
    }
    
    /**
     * Verificar se arquivo QR Code existe
     */
    public function fileExists($arquivo_path) {
        $full_path = __DIR__ . '/../../public/' . $arquivo_path;
        return file_exists($full_path);
    }
    
    /**
     * Obter informações do QR Code por URL
     */
    public function getQRByURL($url) {
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM qr_codes WHERE url_publica = ? AND ativo = TRUE";
        return $db->fetch($sql, [$url]);
    }
    
    /**
     * Listar QR Codes de uma instituição
     */
    public function getInstitutionQRCodes($instituicao_id) {
        $db = Database::getInstance();
        
        $sql = "
            SELECT 
                q.*,
                u.nome as anestesista_nome
            FROM qr_codes q
            LEFT JOIN usuarios u ON q.anestesista_id = u.id
            WHERE q.instituicao_id = ?
            ORDER BY q.tipo, q.created_at DESC
        ";
        
        return $db->fetchAll($sql, [$instituicao_id]);
    }
    
    /**
     * Regenerar QR Code (mantém mesmo código, atualiza arquivo)
     */
    public function regenerateQR($qr_id) {
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM qr_codes WHERE id = ?";
        $qr = $db->fetch($sql, [$qr_id]);
        
        if (!$qr) {
            throw new Exception('QR Code não encontrado');
        }
        
        // Gerar novo arquivo
        $new_path = $this->generate($qr['url_publica'], $qr['tipo'], $qr['instituicao_id']);
        
        // Atualizar no banco
        $sql = "UPDATE qr_codes SET arquivo_path = ?, updated_at = NOW() WHERE id = ?";
        $db->query($sql, [$new_path, $qr_id]);
        
        // Deletar arquivo antigo se existir
        if ($qr['arquivo_path'] && $qr['arquivo_path'] !== $new_path) {
            $old_path = __DIR__ . '/../../public/' . $qr['arquivo_path'];
            if (file_exists($old_path)) {
                unlink($old_path);
            }
        }
        
        return $new_path;
    }
}
