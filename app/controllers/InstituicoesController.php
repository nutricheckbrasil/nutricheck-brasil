<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/RBAC.php';
require_once __DIR__ . '/../classes/InstitutionContext.php';
require_once __DIR__ . '/../classes/QRCodeGenerator.php';

class InstituicoesController extends BaseController {
    private $rbac;
    private $context;
    private $qrGenerator;
    
    public function __construct() {
        parent::__construct();
        $this->rbac = new RBAC();
        $this->context = new InstitutionContext();
        $this->qrGenerator = new QRCodeGenerator();
    }
    
    /**
     * Listar instituições (apenas admin)
     */
    public function index() {
        if (!$this->rbac->isAdmin($this->getCurrentUserId())) {
            $this->redirect('/dashboard', 'Acesso negado. Apenas administradores podem visualizar instituições.', 'error');
            return;
        }
        
        $sql = "
            SELECT 
                i.*,
                (SELECT COUNT(*) FROM usuarios u WHERE u.instituicao_id = i.id AND u.status = 'ativo') as total_usuarios,
                (SELECT COUNT(*) FROM pacientes p WHERE p.instituicao_id = i.id) as total_pacientes,
                (SELECT COUNT(*) FROM qr_codes q WHERE q.instituicao_id = i.id AND q.tipo = 'instituicao' AND q.ativo = TRUE) as tem_qr_code
            FROM instituicoes i
            ORDER BY i.nome
        ";
        
        $instituicoes = $this->db->fetchAll($sql);
        
        // Definir variáveis para a view
        $title = 'Gerenciar Instituições';
        
        // Capturar o conteúdo da view
        ob_start();
        require_once APP_PATH . '/views/instituicoes/index.php';
        $content = ob_get_clean();
        
        // Incluir o layout principal
        require_once APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Criar nova instituição
     */
    public function create() {
        if (!$this->rbac->isAdmin($this->getCurrentUserId())) {
            $this->redirect('/dashboard', 'Acesso negado.', 'error');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->db->getConnection()->beginTransaction();
                
                $nome = $_POST['nome'] ?? '';
                $cnpj = $_POST['cnpj'] ?? '';
                $email = $_POST['email'] ?? '';
                $senha = $_POST['senha'] ?? '';
                $telefone = $_POST['telefone'] ?? '';
                $responsavel = $_POST['responsavel'] ?? '';
                $cargo = $_POST['cargo'] ?? '';
                $cep = $_POST['cep'] ?? '';
                $logradouro = $_POST['logradouro'] ?? '';
                $numero = $_POST['numero'] ?? '';
                $complemento = $_POST['complemento'] ?? '';
                $bairro = $_POST['bairro'] ?? '';
                $cidade = $_POST['cidade'] ?? '';
                $estado = $_POST['estado'] ?? '';
                
                // Validações
                if (empty($nome) || empty($cnpj) || empty($email) || empty($senha) || empty($responsavel) || empty($cargo)) {
                    throw new Exception('Campos obrigatórios não preenchidos');
                }
                
                // Gerar slug único
                $slug = $this->generateSlug($nome);
                
                // Verificar se CNPJ já existe
                $existing = $this->db->fetch("SELECT id FROM instituicoes WHERE cnpj = ?", [$cnpj]);
                if ($existing) {
                    throw new Exception('CNPJ já cadastrado');
                }
                
                // Verificar se email já existe
                $existing = $this->db->fetch("SELECT id FROM instituicoes WHERE email = ?", [$email]);
                if ($existing) {
                    throw new Exception('Email já cadastrado');
                }
                
                // Criar instituição
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                
                // Montar endereço completo com os dados do CEP
                $endereco_completo = '';
                if (!empty($logradouro)) {
                    $endereco_completo = $logradouro;
                    if (!empty($numero)) {
                        $endereco_completo .= ', ' . $numero;
                    }
                    if (!empty($complemento)) {
                        $endereco_completo .= ', ' . $complemento;
                    }
                    if (!empty($bairro)) {
                        $endereco_completo .= ', ' . $bairro;
                    }
                    if (!empty($cidade) && !empty($estado)) {
                        $endereco_completo .= ', ' . $cidade . '/' . $estado;
                    }
                    if (!empty($cep)) {
                        $endereco_completo .= ', CEP: ' . $cep;
                    }
                }
                
                // Processar upload de foto
                $foto_path = null;
                $logo_path = null;
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                    $foto_path = $this->processarUploadFoto($_FILES['foto'], $nome);
                    $logo_path = $foto_path; // Usar a mesma imagem para logo e foto
                }
                
                $sql = "
                    INSERT INTO instituicoes (nome, cnpj, slug, email, senha_hash, endereco, telefone, responsavel, cargo, status, foto_path, logo_path) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'ativo', ?, ?)
                ";
                
                $this->db->query($sql, [$nome, $cnpj, $slug, $email, $senha_hash, $endereco_completo, $telefone, $responsavel, $cargo, $foto_path, $logo_path]);
                $instituicao_id = $this->db->lastInsertId();
                
                // Gerar QR Code da instituição
                $this->generateInstitutionQR($instituicao_id, $slug);
                
                $this->db->getConnection()->commit();
                
                $this->redirect('/instituicoes', 'Instituição criada com sucesso!', 'success');
                
            } catch (Exception $e) {
                $this->db->getConnection()->rollback();
                $_SESSION['errors'] = ['Erro ao criar instituição: ' . $e->getMessage()];
                $_SESSION['dados'] = $_POST;
                $this->redirect('/instituicoes/create');
            }
        } else {
            $dados = $_SESSION['dados'] ?? [];
            $errors = $_SESSION['errors'] ?? [];
            unset($_SESSION['errors'], $_SESSION['dados']);
            
            // Capturar o conteúdo da view
            ob_start();
            require_once APP_PATH . '/views/instituicoes/create.php';
            $content = ob_get_clean();
            
            // Incluir o layout principal
            require_once APP_PATH . '/views/layouts/main.php';
        }
    }
    
    /**
     * Editar instituição
     */
    public function edit($id = null) {
        if (!$this->rbac->isAdmin($this->getCurrentUserId())) {
            $this->redirect('/dashboard', 'Acesso negado.', 'error');
            return;
        }
        
        // Se não veio como parâmetro, tenta pegar do GET
        if (!$id) {
            $id = $_GET['id'] ?? null;
        }
        
        if (!$id) {
            $this->redirect('/instituicoes', 'ID da instituição não informado.', 'error');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $nome = $_POST['nome'] ?? '';
                $cnpj = $_POST['cnpj'] ?? '';
                $email = $_POST['email'] ?? '';
                $endereco = $_POST['endereco'] ?? '';
                $telefone = $_POST['telefone'] ?? '';
                $responsavel = $_POST['responsavel'] ?? '';
                $cargo = $_POST['cargo'] ?? '';
                $cep = $_POST['cep'] ?? '';
                $cidade = $_POST['cidade'] ?? '';
                $estado = $_POST['estado'] ?? '';
                $numero = $_POST['numero'] ?? '';
                $status = isset($_POST['ativo']) ? 'ativo' : 'inativo';
                
                // Validações
                if (empty($nome) || empty($cnpj) || empty($email) || empty($responsavel) || empty($cargo)) {
                    throw new Exception('Campos obrigatórios não preenchidos');
                }
                
                // Verificar se CNPJ já existe (exceto para esta instituição)
                $existing = $this->db->fetch("SELECT id FROM instituicoes WHERE cnpj = ? AND id != ?", [$cnpj, $id]);
                if ($existing) {
                    throw new Exception('CNPJ já cadastrado em outra instituição');
                }
                
                // Verificar se email já existe (exceto para esta instituição)
                $existing = $this->db->fetch("SELECT id FROM instituicoes WHERE email = ? AND id != ?", [$email, $id]);
                if ($existing) {
                    throw new Exception('Email já cadastrado em outra instituição');
                }
                
                // Montar endereço completo com os dados do CEP
                $endereco_completo = '';
                if (!empty($logradouro)) {
                    $endereco_completo = $logradouro;
                    if (!empty($numero)) {
                        $endereco_completo .= ', ' . $numero;
                    }
                    if (!empty($complemento)) {
                        $endereco_completo .= ', ' . $complemento;
                    }
                    if (!empty($bairro)) {
                        $endereco_completo .= ', ' . $bairro;
                    }
                    if (!empty($cidade) && !empty($estado)) {
                        $endereco_completo .= ', ' . $cidade . '/' . $estado;
                    }
                    if (!empty($cep)) {
                        $endereco_completo .= ', CEP: ' . $cep;
                    }
                } else {
                    // Se não há dados do CEP, usar o campo endereco original
                    $endereco_completo = $endereco;
                }
                
                // Processar upload de foto se enviada
                $foto_path = null;
                $logo_path = null;
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                    $foto_path = $this->processarUploadFoto($_FILES['foto'], $nome);
                    $logo_path = $foto_path; // Usar a mesma imagem para logo e foto
                }
                
                // Atualizar instituição
                $sql = "
                    UPDATE instituicoes 
                    SET nome = ?, cnpj = ?, email = ?, endereco = ?, telefone = ?, responsavel = ?, cargo = ?, status = ?
                    WHERE id = ?
                ";
                
                $this->db->query($sql, [$nome, $cnpj, $email, $endereco_completo, $telefone, $responsavel, $cargo, $status, $id]);
                
                // Se nova foto foi enviada, atualizar os campos foto_path e logo_path
                if ($foto_path) {
                    $this->db->query("UPDATE instituicoes SET foto_path = ?, logo_path = ? WHERE id = ?", [$foto_path, $logo_path, $id]);
                }
                
                // Se mudou a senha, atualizar
                if (!empty($_POST['senha'])) {
                    $senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                    $this->db->query("UPDATE instituicoes SET senha_hash = ? WHERE id = ?", [$senha_hash, $id]);
                }
                
                $this->redirect('/instituicoes', 'Instituição atualizada com sucesso!', 'success');
                
            } catch (Exception $e) {
                $_SESSION['errors'] = ['Erro ao atualizar instituição: ' . $e->getMessage()];
                $_SESSION['dados'] = $_POST;
                $this->redirect('/instituicoes/edit?id=' . $id);
            }
        } else {
            $instituicao = $this->db->fetch("SELECT * FROM instituicoes WHERE id = ?", [$id]);
            
            if (!$instituicao) {
                $this->redirect('/instituicoes', 'Instituição não encontrada.', 'error');
                return;
            }
            
            $dados = $_SESSION['dados'] ?? [];
            $errors = $_SESSION['errors'] ?? [];
            unset($_SESSION['errors'], $_SESSION['dados']);
            
            // Capturar o conteúdo da view
            ob_start();
            require_once APP_PATH . '/views/instituicoes/edit.php';
            $content = ob_get_clean();
            
            // Incluir o layout principal
            require_once APP_PATH . '/views/layouts/main.php';
        }
    }
    
    /**
     * Visualizar instituição
     */
    public function view($id = null) {
        if (!$this->rbac->isAdmin($this->getCurrentUserId())) {
            $this->redirect('/dashboard', 'Acesso negado.', 'error');
            return;
        }
        
        // Se não veio como parâmetro, tenta pegar do GET
        if (!$id) {
            $id = $_GET['id'] ?? null;
        }
        
        if (!$id) {
            $this->redirect('/instituicoes', 'ID da instituição não informado.', 'error');
            return;
        }
        
        $instituicao = $this->db->fetch("SELECT * FROM instituicoes WHERE id = ?", [$id]);
        
        if (!$instituicao) {
            $this->redirect('/instituicoes', 'Instituição não encontrada.', 'error');
            return;
        }
        
        // Estatísticas da instituição
        $stats = [
            'total_usuarios' => $this->db->fetch("SELECT COUNT(*) as count FROM usuarios WHERE instituicao_id = ? AND status = 'ativo'", [$id])['count'],
            'total_pacientes' => $this->db->fetch("SELECT COUNT(*) as count FROM pacientes WHERE instituicao_id = ?", [$id])['count'],
            'total_anestesistas' => $this->db->fetch("SELECT COUNT(*) as count FROM usuarios WHERE instituicao_id = ? AND perfil_id = 3 AND status = 'ativo'", [$id])['count']
        ];
        
        // QR Codes da instituição
        $qr_codes = $this->db->fetchAll("SELECT * FROM qr_codes WHERE instituicao_id = ? AND tipo = 'instituicao'", [$id]);
        
        // Capturar o conteúdo da view
        ob_start();
        require_once APP_PATH . '/views/instituicoes/view.php';
        $content = ob_get_clean();
        
        // Incluir o layout principal
        require_once APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Regenerar QR Code da instituição
     */
    public function regenerateQR($id = null) {
        if (!$this->rbac->isAdmin($this->getCurrentUserId())) {
            $this->redirect('/dashboard', 'Acesso negado.', 'error');
            return;
        }
        
        // Se não veio como parâmetro, tenta pegar do GET
        if (!$id) {
            $id = $_GET['id'] ?? null;
        }
        
        if (!$id) {
            $this->redirect('/instituicoes', 'ID da instituição não informado.', 'error');
            return;
        }
        
        try {
            $instituicao = $this->db->fetch("SELECT * FROM instituicoes WHERE id = ?", [$id]);
            
            if (!$instituicao) {
                throw new Exception('Instituição não encontrada');
            }
            
            $this->generateInstitutionQR($id, $instituicao['slug']);
            
            $this->redirect('/instituicoes', 'QR Code regenerado com sucesso!', 'success');
            
        } catch (Exception $e) {
            $this->redirect('/instituicoes', 'Erro ao regenerar QR Code: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Gerar/Regenerar QR Code da instituição (AJAX)
     */
    public function generateQR() {
        if (!$this->rbac->isAdmin($this->getCurrentUserId())) {
            $this->jsonResponse(['success' => false, 'message' => 'Acesso negado']);
            return;
        }
        
        $id = $_POST['instituicao_id'] ?? null;
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'ID da instituição não informado']);
            return;
        }
        
        try {
            $instituicao = $this->db->fetch("SELECT * FROM instituicoes WHERE id = ?", [$id]);
            
            if (!$instituicao) {
                throw new Exception('Instituição não encontrada');
            }
            
            $this->generateInstitutionQR($id, $instituicao['slug']);
            
            $this->jsonResponse(['success' => true, 'message' => 'QR Code gerado com sucesso!']);
            
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Gerar slug único
     */
    private function generateSlug($nome) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nome)));
        $original_slug = $slug;
        $counter = 1;
        
        while (true) {
            $existing = $this->db->fetch("SELECT id FROM instituicoes WHERE slug = ?", [$slug]);
            if (!$existing) {
                break;
            }
            $slug = $original_slug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Gerar QR Code da instituição
     */
    private function generateInstitutionQR($instituicao_id, $slug) {
        $base_url = 'http://' . $_SERVER['HTTP_HOST'];
        $url = $base_url . '/p/' . $slug;
        
        // Gerar código único
        $codigo = 'INST_' . $instituicao_id . '_' . time() . '_' . bin2hex(random_bytes(8));
        
        // Gerar arquivo QR Code
        $qr_path = $this->qrGenerator->generate($url, 'instituicao', $instituicao_id);
        
        // Salvar no banco
        $sql = "
            INSERT INTO qr_codes (instituicao_id, tipo, codigo, url_publica, arquivo_path, ativo) 
            VALUES (?, 'instituicao', ?, ?, ?, TRUE)
            ON DUPLICATE KEY UPDATE 
            codigo = VALUES(codigo),
            url_publica = VALUES(url_publica),
            arquivo_path = VALUES(arquivo_path),
            updated_at = NOW()
        ";
        
        $this->db->query($sql, [$instituicao_id, $codigo, $url, $qr_path]);
    }
    
    /**
     * Obter ID do usuário atual
     */
    private function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Processar upload de foto
     */
    private function processarUploadFoto($arquivo, $nome_instituicao) {
        // Diretório de upload
        $upload_dir = 'public/uploads/instituicoes/';
        
        // Criar diretório se não existir
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Validar tipo de arquivo
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($arquivo['type'], $tipos_permitidos)) {
            throw new Exception('Tipo de arquivo não permitido. Use JPG, PNG ou GIF.');
        }
        
        // Validar tamanho (2MB)
        if ($arquivo['size'] > 2 * 1024 * 1024) {
            throw new Exception('Arquivo muito grande. Máximo 2MB.');
        }
        
        // Gerar nome único para o arquivo
        $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $nome_arquivo = 'instituicao_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extensao;
        $caminho_completo = $upload_dir . $nome_arquivo;
        
        // Mover arquivo
        if (!move_uploaded_file($arquivo['tmp_name'], $caminho_completo)) {
            throw new Exception('Erro ao fazer upload do arquivo.');
        }
        
        // Redimensionar imagem se necessário
        $this->redimensionarImagem($caminho_completo, 800, 600);
        
        return $caminho_completo;
    }
    
    /**
     * Redimensionar imagem mantendo proporção
     */
    private function redimensionarImagem($caminho, $largura_max, $altura_max) {
        $info = getimagesize($caminho);
        if (!$info) return;
        
        $largura_original = $info[0];
        $altura_original = $info[1];
        $tipo = $info[2];
        
        // Se a imagem já é menor que o máximo, não redimensionar
        if ($largura_original <= $largura_max && $altura_original <= $altura_max) {
            return;
        }
        
        // Calcular novas dimensões mantendo proporção
        $ratio = min($largura_max / $largura_original, $altura_max / $altura_original);
        $nova_largura = intval($largura_original * $ratio);
        $nova_altura = intval($altura_original * $ratio);
        
        // Criar imagem original
        switch ($tipo) {
            case IMAGETYPE_JPEG:
                $imagem_original = imagecreatefromjpeg($caminho);
                break;
            case IMAGETYPE_PNG:
                $imagem_original = imagecreatefrompng($caminho);
                break;
            case IMAGETYPE_GIF:
                $imagem_original = imagecreatefromgif($caminho);
                break;
            default:
                return;
        }
        
        // Criar nova imagem redimensionada
        $imagem_nova = imagecreatetruecolor($nova_largura, $nova_altura);
        
        // Preservar transparência para PNG e GIF
        if ($tipo == IMAGETYPE_PNG || $tipo == IMAGETYPE_GIF) {
            imagealphablending($imagem_nova, false);
            imagesavealpha($imagem_nova, true);
            $transparente = imagecolorallocatealpha($imagem_nova, 255, 255, 255, 127);
            imagefilledrectangle($imagem_nova, 0, 0, $nova_largura, $nova_altura, $transparente);
        }
        
        // Redimensionar
        imagecopyresampled($imagem_nova, $imagem_original, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura_original, $altura_original);
        
        // Salvar imagem redimensionada
        switch ($tipo) {
            case IMAGETYPE_JPEG:
                imagejpeg($imagem_nova, $caminho, 85);
                break;
            case IMAGETYPE_PNG:
                imagepng($imagem_nova, $caminho, 8);
                break;
            case IMAGETYPE_GIF:
                imagegif($imagem_nova, $caminho);
                break;
        }
        
        // Limpar memória
        imagedestroy($imagem_original);
        imagedestroy($imagem_nova);
    }
    
    /**
     * Resposta JSON
     */
    protected function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
