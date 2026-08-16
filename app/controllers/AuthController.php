<?php

class AuthController extends BaseController {
    
    protected function requiresAuth() {
        return false; // Controller de auth não requer autenticação
    }
    
    public function login() {
        if ($this->isPost()) {
            $email = $this->getPost('email');
            $senha = $this->getPost('senha');
            
            $errors = $this->validateRequired($_POST, ['email', 'senha']);
            
            if (empty($errors)) {
                // Tentar autenticar como instituição primeiro
                $user = $this->authenticateInstituicao($email, $senha);
                $user_type = 'instituicao';
                
                // Se não encontrou como instituição, tentar como usuário
                if (!$user) {
                    $user = $this->authenticateUsuario($email, $senha);
                    $user_type = 'usuario';
                }
                
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_type'] = $user_type;
                    $_SESSION['user_name'] = $user['nome'];
                    $_SESSION['instituicao_id'] = $user['instituicao_id'] ?? null;
                    $_SESSION['perfil_id'] = $user['perfil_id'] ?? null;

                    $this->logActivity('login', "Login realizado com sucesso");
                    
                    // Redirecionar baseado no perfil do usuário
                    if ($user_type === 'usuario' && isset($user['perfil_id'])) {
                        if ($user['perfil_id'] == 3) { // Anestesista
                            $this->redirect('/equipe-nutricionistas');
                        } else {
                            $this->redirect('/dashboard');
                        }
                    } else {
                        $this->redirect('/dashboard');
                    }
                } else {
                    $errors[] = 'Email ou senha inválidos';
                }
            }
        }
        
        $this->render('auth/login', [
            'title' => 'Login - NutriCheck',
            'errors' => $errors ?? []
        ]);
    }
    
    private function authenticateInstituicao($email, $senha) {
        $sql = "SELECT * FROM instituicoes WHERE email = ? AND status = 'ativo'";
        $instituicao = $this->db->fetch($sql, [$email]);
        
        if ($instituicao && password_verify($senha, $instituicao['senha_hash'])) {
            return [
                'id' => $instituicao['id'],
                'nome' => $instituicao['nome'],
                'instituicao_id' => $instituicao['id'],
                'perfil_id' => 1 // Perfil 1 = instituição
            ];
        }
        
        return false;
    }
    
    private function authenticateUsuario($email, $senha) {
        $sql = "SELECT u.*, p.nome as perfil_nome 
                FROM usuarios u 
                JOIN perfis p ON u.perfil_id = p.id 
                WHERE u.email = ? AND u.status = 'ativo'";
        $usuario = $this->db->fetch($sql, [$email]);
        
        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            return $usuario;
        }
        
        return false;
    }
    
    public function logout() {
        $this->logActivity('logout', "Logout realizado");
        session_destroy();
        $this->redirect('/'); // Redireciona para a home pública
    }
    
    public function register() {
        if ($this->isPost()) {
            $nome = $this->getPost('nome');
            $email = $this->getPost('email');
            $senha = $this->getPost('senha');
            $cnpj = $this->getPost('cnpj');
            $endereco = $this->getPost('endereco');
            $telefone = $this->getPost('telefone');
            
            $errors = $this->validateRequired($_POST, ['nome', 'email', 'senha', 'cnpj']);
            
            if (empty($errors)) {
                // Verificar se email já existe
                $existing = $this->db->fetch("SELECT id FROM instituicoes WHERE email = ?", [$email]);
                if ($existing) {
                    $errors[] = 'Email já cadastrado';
                }
                
                // Verificar se CNPJ já existe
                $existing = $this->db->fetch("SELECT id FROM instituicoes WHERE cnpj = ?", [$cnpj]);
                if ($existing) {
                    $errors[] = 'CNPJ já cadastrado';
                }
                
                if (empty($errors)) {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    
                    $sql = "INSERT INTO instituicoes (nome, email, senha_hash, cnpj, endereco, telefone) 
                            VALUES (?, ?, ?, ?, ?, ?)";
                    
                    $this->db->query($sql, [$nome, $email, $senha_hash, $cnpj, $endereco, $telefone]);
                    
                    $this->logActivity('register', "Nova instituição registrada: $nome");
                    $this->redirect('/login?success=1');
                }
            }
        }
        
        $this->render('auth/register', [
            'title' => 'Cadastro de Instituição - NutriCheck',
            'errors' => $errors ?? []
        ]);
    }
} 