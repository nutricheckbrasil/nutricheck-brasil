<?php
require_once APP_PATH . '/controllers/BaseController.php';

class AnestesistasController extends BaseController {
    
    private function getInstituicaoId() {
        // Para instituições, usar o user_id como instituicao_id
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            return $_SESSION['user_id'];
        } else {
            // Para admin, permitir selecionar qualquer instituição
            // Por enquanto, retornar null para que o admin possa ver todas
            return null;
        }
    }
    
    public function index() {
        $this->requiresAuth();
        
        // Verificar se é instituição (user_type = 'instituicao') ou admin/medico (perfil_id = 1 ou 2)
        $is_instituicao = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao';
        $is_admin_medico = isset($_SESSION['perfil_id']) && in_array($_SESSION['perfil_id'], [2, 6]);
        
        if (!$is_instituicao && !$is_admin_medico) {
            $_SESSION['flash_message'] = 'Acesso negado. Apenas administradores e instituições podem gerenciar anestesistas.';
            $this->redirect(BASE_URL . '/');
            return;
        }
        
        $filtro = $this->getGet('filtro', 'todos');
        $search = $this->getGet('search', '');
        
        $where_conditions = ['u.perfil_id = 3']; // Apenas anestesistas
        
        $instituicao_id = $this->getInstituicaoId();
        $params = [];
        
        // Se não for admin (instituicao_id não é null), filtrar por instituição
        if ($instituicao_id !== null) {
            $where_conditions[] = 'u.instituicao_id = ?';
            $params[] = $instituicao_id;
        }
        
        if ($filtro !== 'todos') {
            switch ($filtro) {
                case 'ativos':
                    $where_conditions[] = 'u.status = "ativo"';
                    break;
                case 'inativos':
                    $where_conditions[] = 'u.status = "inativo"';
                    break;
            }
        }
        
        if (!empty($search)) {
            $where_conditions[] = '(u.nome LIKE ? OR u.email LIKE ? OR u.crm LIKE ?)';
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        $where_clause = implode(' AND ', $where_conditions);
        
        $sql = "SELECT u.*, 
                       i.nome as instituicao_nome
                FROM usuarios u 
                LEFT JOIN instituicoes i ON u.instituicao_id = i.id
                WHERE {$where_clause}
                ORDER BY u.nome";
        
        $anestesistas = $this->db->fetchAll($sql, $params);
        
        // Estatísticas
        $stats_where = "perfil_id = 3";
        $stats_params = [];
        
        if ($instituicao_id !== null) {
            $stats_where .= " AND instituicao_id = ?";
            $stats_params[] = $instituicao_id;
        }
        
        $stats_sql = "SELECT 
                        COUNT(*) as total_anestesistas,
                        COUNT(CASE WHEN status = 'ativo' THEN 1 END) as ativos,
                        COUNT(CASE WHEN status = 'inativo' THEN 1 END) as inativos,
                        COUNT(CASE WHEN qr_code IS NOT NULL THEN 1 END) as com_qr_code
                      FROM usuarios 
                      WHERE {$stats_where}";
        
        $stats = $this->db->fetch($stats_sql, $stats_params);
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/anestesistas/index.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    public function create() {
        $this->requiresAuth();
        
        // Verificar se é instituição (user_type = 'instituicao') ou admin/medico (perfil_id = 1 ou 2)
        $is_instituicao = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao';
        $is_admin_medico = isset($_SESSION['perfil_id']) && in_array($_SESSION['perfil_id'], [2, 6]);
        
        if (!$is_instituicao && !$is_admin_medico) {
            $_SESSION['flash_message'] = 'Acesso negado. Apenas administradores e instituições podem gerenciar anestesistas.';
            $this->redirect(BASE_URL . '/');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRequired($_POST, ['nome', 'email', 'senha', 'crm']);
            
            if (empty($errors)) {
                // Verificar se email já existe
                $existing = $this->db->fetch("SELECT id FROM usuarios WHERE email = ?", [$_POST['email']]);
                if ($existing) {
                    $errors[] = 'Este email já está em uso.';
                }
                
                // Verificar se CRM já existe
                $existing_crm = $this->db->fetch("SELECT id FROM usuarios WHERE crm = ?", [$_POST['crm']]);
                if ($existing_crm) {
                    $errors[] = 'Este CRM já está em uso.';
                }
            }
            
            if (empty($errors)) {
                $nome = $this->getPost('nome');
                $email = $this->getPost('email');
                $senha = password_hash($this->getPost('senha'), PASSWORD_DEFAULT);
                $crm = $this->getPost('crm');
                $telefone = $this->getPost('telefone');
                $instituicao_id = $this->getPost('instituicao_id');
                
                // Se não for admin, usar a instituição do usuário logado
                if ($instituicao_id === null) {
                    $instituicao_id = $this->getInstituicaoId();
                }
                
                // Gerar QR Code único
                $qr_code = $this->generateQRCode();
                
                // Processar upload de foto
                $foto_path = null;
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                    $foto_path = $this->uploadFoto($_FILES['foto'], 'anestesistas');
                }
                
                $sql = "INSERT INTO usuarios (instituicao_id, perfil_id, nome, email, senha_hash, crm, telefone, qr_code, foto_path, status) 
                        VALUES (?, 3, ?, ?, ?, ?, ?, ?, ?, 'ativo')";
                
                $this->db->query($sql, [
                    $instituicao_id,
                    $nome,
                    $email,
                    $senha,
                    $crm,
                    $telefone,
                    $qr_code,
                    $foto_path
                ]);
                
                $anestesista_id = $this->db->lastInsertId();
                
                // Gerar e salvar QR Code
                $this->generateQRCodeImage($anestesista_id, $qr_code);
                
                $_SESSION['flash_message'] = 'Anestesista cadastrado com sucesso!';
                $this->redirect('/anestesistas');
            }
        }
        
        // Buscar instituições para admin
        $instituicoes = [];
        if ($this->getInstituicaoId() === null) {
            // Admin pode ver todas as instituições
            $instituicoes = $this->db->fetchAll("
                SELECT id, nome 
                FROM instituicoes 
                WHERE nome != 'ADMIN' 
                AND nome != 'admin' 
                AND nome != 'Sistema Administrativo'
                AND nome != 'Sistema Admin'
                AND id != 1
                ORDER BY nome
            ");
        }
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/anestesistas/create.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    public function edit($id) {
        $this->requiresAuth();
        
        // Verificar se é instituição (user_type = 'instituicao') ou admin/medico (perfil_id = 1 ou 2)
        $is_instituicao = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao';
        $is_admin_medico = isset($_SESSION['perfil_id']) && in_array($_SESSION['perfil_id'], [2, 6]);
        
        if (!$is_instituicao && !$is_admin_medico) {
            $_SESSION['flash_message'] = 'Acesso negado. Apenas administradores e instituições podem gerenciar anestesistas.';
            $this->redirect(BASE_URL . '/');
            return;
        }
        
        $anestesista = $this->db->fetch("SELECT * FROM usuarios WHERE id = ? AND instituicao_id = ? AND perfil_id = 3", 
                                       [$id, $this->getInstituicaoId()]);
        
        if (!$anestesista) {
            $_SESSION['flash_message'] = 'Anestesista não encontrado.';
            $this->redirect('/anestesistas');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRequired($_POST, ['nome', 'email', 'crm']);
            
            if (empty($errors)) {
                // Verificar se email já existe (exceto o próprio)
                $existing = $this->db->fetch("SELECT id FROM usuarios WHERE email = ? AND id != ?", 
                                           [$_POST['email'], $id]);
                if ($existing) {
                    $errors[] = 'Este email já está em uso.';
                }
                
                // Verificar se CRM já existe (exceto o próprio)
                $existing_crm = $this->db->fetch("SELECT id FROM usuarios WHERE crm = ? AND id != ?", 
                                                 [$_POST['crm'], $id]);
                if ($existing_crm) {
                    $errors[] = 'Este CRM já está em uso.';
                }
            }
            
            if (empty($errors)) {
                $nome = $this->getPost('nome');
                $email = $this->getPost('email');
                $crm = $this->getPost('crm');
                $telefone = $this->getPost('telefone');
                $status = $this->getPost('status', 'ativo');
                
                // Processar upload de foto se enviada
                $foto_path = $anestesista['foto_path']; // Manter foto atual
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                    // Remover foto antiga se existir
                    if (!empty($anestesista['foto_path'])) {
                        $old_path = APP_PATH . '/../public/' . $anestesista['foto_path'];
                        if (file_exists($old_path)) {
                            unlink($old_path);
                        }
                    }
                    $foto_path = $this->uploadFoto($_FILES['foto'], 'anestesistas');
                }
                
                $sql = "UPDATE usuarios SET nome = ?, email = ?, crm = ?, telefone = ?, status = ?, foto_path = ? WHERE id = ?";
                $this->db->query($sql, [$nome, $email, $crm, $telefone, $status, $foto_path, $id]);
                
                // Se não tem QR Code, gerar um
                if (empty($anestesista['qr_code'])) {
                    $qr_code = $this->generateQRCode();
                    $this->db->query("UPDATE usuarios SET qr_code = ? WHERE id = ?", [$qr_code, $id]);
                    $this->generateQRCodeImage($id, $qr_code);
                }
                
                $_SESSION['flash_message'] = 'Anestesista atualizado com sucesso!';
                $this->redirect('/anestesistas');
            }
        }
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/anestesistas/edit.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    public function view($id) {
        $this->requiresAuth();
        
        // Verificar se é instituição (user_type = 'instituicao') ou admin/medico (perfil_id = 1 ou 2)
        $is_instituicao = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao';
        $is_admin_medico = isset($_SESSION['perfil_id']) && in_array($_SESSION['perfil_id'], [2, 6]);
        
        if (!$is_instituicao && !$is_admin_medico) {
            $_SESSION['flash_message'] = 'Acesso negado. Apenas administradores e instituições podem gerenciar anestesistas.';
            $this->redirect(BASE_URL . '/');
            return;
        }
        
        $anestesista = $this->db->fetch("SELECT u.*, i.nome as instituicao_nome 
                                        FROM usuarios u 
                                        LEFT JOIN instituicoes i ON u.instituicao_id = i.id
                                        WHERE u.id = ? AND u.instituicao_id = ? AND u.perfil_id = 3", 
                                       [$id, $this->getInstituicaoId()]);
        
        if (!$anestesista) {
            $_SESSION['flash_message'] = 'Anestesista não encontrado.';
            $this->redirect('/anestesistas');
        }
        
        // Buscar pacientes do anestesista
        $pacientes_sql = "SELECT p.*, m.nome as medico_nome, pr.nome as procedimento_nome
                          FROM pacientes p
                          LEFT JOIN usuarios m ON p.medico_id = m.id
                          LEFT JOIN procedimentos pr ON p.procedimento_id = pr.id
                          WHERE p.anestesista_id = ?
                          ORDER BY p.created_at DESC";
        
        $pacientes = $this->db->fetchAll($pacientes_sql, [$id]);
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/anestesistas/view.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    public function delete($id) {
        $this->requiresAuth();
        
        // Verificar se é instituição (user_type = 'instituicao') ou admin/medico (perfil_id = 1 ou 2)
        $is_instituicao = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao';
        $is_admin_medico = isset($_SESSION['perfil_id']) && in_array($_SESSION['perfil_id'], [2, 6]);
        
        if (!$is_instituicao && !$is_admin_medico) {
            $_SESSION['flash_message'] = 'Acesso negado. Apenas administradores e instituições podem gerenciar anestesistas.';
            $this->redirect(BASE_URL . '/');
            return;
        }
        
        $anestesista = $this->db->fetch("SELECT * FROM usuarios WHERE id = ? AND instituicao_id = ? AND perfil_id = 3", 
                                       [$id, $this->getInstituicaoId()]);
        
        if (!$anestesista) {
            $_SESSION['flash_message'] = 'Anestesista não encontrado.';
            $this->redirect('/anestesistas');
        }
        
        // Verificar se tem pacientes associados
        $pacientes_count = $this->db->fetch("SELECT COUNT(*) as count FROM pacientes WHERE anestesista_id = ?", [$id]);
        
        if ($pacientes_count['count'] > 0) {
            $_SESSION['flash_message'] = 'Não é possível excluir anestesista com pacientes associados.';
            $this->redirect('/anestesistas');
        }
        
        try {
            // Remover logs de atividade primeiro
            $this->db->query("DELETE FROM logs_ativade WHERE usuario_id = ?", [$id]);
            
            // Remover arquivos de QR Code e foto
            if (!empty($anestesista['qr_code_path'])) {
                $qr_path = APP_PATH . '/../public/' . $anestesista['qr_code_path'];
                if (file_exists($qr_path)) {
                    unlink($qr_path);
                }
            }
            
            if (!empty($anestesista['foto_path'])) {
                $foto_path = APP_PATH . '/../public/' . $anestesista['foto_path'];
                if (file_exists($foto_path)) {
                    unlink($foto_path);
                }
            }
            
            // Agora pode excluir o usuário
            $this->db->query("DELETE FROM usuarios WHERE id = ?", [$id]);
            
        } catch (Exception $e) {
            $_SESSION['flash_message'] = 'Erro ao excluir anestesista: ' . $e->getMessage();
            $this->redirect('/anestesistas');
            return;
        }
        
        $_SESSION['flash_message'] = 'Anestesista excluído com sucesso!';
        $this->redirect('/anestesistas');
    }
    
    public function regenerateQR($id) {
        $this->requiresAuth();
        
        // Verificar se é instituição (user_type = 'instituicao') ou admin/medico (perfil_id = 1 ou 2)
        $is_instituicao = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao';
        $is_admin_medico = isset($_SESSION['perfil_id']) && in_array($_SESSION['perfil_id'], [2, 6]);
        
        if (!$is_instituicao && !$is_admin_medico) {
            $_SESSION['flash_message'] = 'Acesso negado. Apenas administradores e instituições podem gerenciar anestesistas.';
            $this->redirect(BASE_URL . '/');
            return;
        }
        
        $anestesista = $this->db->fetch("SELECT * FROM usuarios WHERE id = ? AND instituicao_id = ? AND perfil_id = 3", 
                                       [$id, $this->getInstituicaoId()]);
        
        if (!$anestesista) {
            $_SESSION['flash_message'] = 'Anestesista não encontrado.';
            $this->redirect('/anestesistas');
        }
        
        // Remover QR Code antigo
        if (!empty($anestesista['qr_code_path'])) {
            $old_qr_path = APP_PATH . '/../public/' . $anestesista['qr_code_path'];
            if (file_exists($old_qr_path)) {
                unlink($old_qr_path);
            }
        }
        
        // Gerar novo QR Code
        $qr_code = $this->generateQRCode();
        $this->db->query("UPDATE usuarios SET qr_code = ?, qr_code_path = NULL WHERE id = ?", [$qr_code, $id]);
        $this->generateQRCodeImage($id, $qr_code);
        
        $_SESSION['flash_message'] = 'QR Code regenerado com sucesso!';
        $this->redirect('/anestesistas/view/' . $id);
    }
    
    private function generateQRCode() {
        return 'ANEST_' . uniqid() . '_' . time();
    }
    
    private function generateQRCodeImage($anestesista_id, $qr_code) {
        // URL que o QR Code vai direcionar
        $url = BASE_URL . '/pacientes/create?anestesista_id=' . $anestesista_id;
        
        // Usar uma biblioteca de QR Code (exemplo com Google Charts API)
        $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($url);
        
        // Baixar e salvar a imagem
        $qr_image = file_get_contents($qr_url);
        $filename = 'uploads/qr_codes/qr_' . $anestesista_id . '_' . time() . '.png';
        $filepath = APP_PATH . '/../public/' . $filename;
        
        // Criar diretório se não existir
        $dir = dirname($filepath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        file_put_contents($filepath, $qr_image);
        
        // Atualizar no banco
        $this->db->query("UPDATE usuarios SET qr_code_path = ? WHERE id = ?", [$filename, $anestesista_id]);
    }
    
    private function uploadFoto($file, $pasta = 'anestesistas') {
        // Verificar se é uma imagem válida
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowed_types)) {
            throw new Exception('Tipo de arquivo não permitido. Use JPG, PNG ou GIF.');
        }
        
        // Verificar tamanho (máximo 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            throw new Exception('Arquivo muito grande. Máximo 2MB.');
        }
        
        // Criar diretório se não existir
        $upload_dir = APP_PATH . '/../public/uploads/' . $pasta . '/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Gerar nome único para o arquivo
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = $upload_dir . $filename;
        
        // Mover arquivo
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'uploads/' . $pasta . '/' . $filename;
        } else {
            throw new Exception('Erro ao fazer upload da imagem.');
        }
    }
}
