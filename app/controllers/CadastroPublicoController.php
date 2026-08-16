<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/QRCodeGenerator.php';

class CadastroPublicoController extends BaseController {
    private $qrGenerator;
    
    public function __construct() {
        parent::__construct();
        $this->qrGenerator = new QRCodeGenerator();
    }
    
    /**
     * Cadastro público via QR Code com token
     * Rota: /cadastro-paciente?token=xxx
     */
    public function cadastroViaToken() {
        $token = $_GET['token'] ?? null;
        
        if (!$token) {
            $this->showError('Token não informado');
            return;
        }
        
        // Validar e buscar informações do token
        $token_info = $this->validarToken($token);
        
        if (!$token_info) {
            $this->showError('Token inválido ou inativo');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processarCadastroToken($token_info);
        } else {
            $this->showFormToken($token_info);
        }
    }
    
    /**
     * Validar token e retornar informações
     */
    private function validarToken($token) {
        $sql = "SELECT qc.*, i.nome as instituicao_nome, i.endereco as instituicao_endereco, 
                       i.telefone as instituicao_telefone, u.nome as anestesista_nome, u.crm as anestesista_crm
                FROM qr_codes qc
                JOIN instituicoes i ON qc.instituicao_id = i.id
                LEFT JOIN usuarios u ON qc.anestesista_id = u.id
                WHERE qc.codigo = ? AND qc.ativo = TRUE AND i.status = 'ativo'";
        
        return $this->db->fetch($sql, [$token]);
    }
    
    /**
     * Cadastro público via QR da instituição (LEGACY - mantido para compatibilidade)
     * Rota: /p/{slug}
     */
    public function cadastroInstituicao() {
        $slug = $_GET['slug'] ?? null;
        
        if (!$slug) {
            $this->showError('Slug da instituição não informado');
            return;
        }
        
        // Buscar instituição pelo slug
        $instituicao = $this->db->fetch(
            "SELECT * FROM instituicoes WHERE slug = ? AND ativo = TRUE",
            [$slug]
        );
        
        if (!$instituicao) {
            $this->showError('Instituição não encontrada ou inativa');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processarCadastroInstituicao($instituicao);
        } else {
            $this->showFormInstituicao($instituicao);
        }
    }
    
    /**
     * Cadastro público via QR do anestesista (LEGACY - mantido para compatibilidade)
     * Rota: /p/{slug}/{anestesista_id}
     */
    public function cadastroAnestesista() {
        $slug = $_GET['slug'] ?? null;
        $anestesista_id = $_GET['anestesista_id'] ?? null;
        
        if (!$slug || !$anestesista_id) {
            $this->showError('Parâmetros inválidos');
            return;
        }
        
        // Buscar instituição pelo slug
        $instituicao = $this->db->fetch(
            "SELECT * FROM instituicoes WHERE slug = ? AND ativo = TRUE",
            [$slug]
        );
        
        if (!$instituicao) {
            $this->showError('Instituição não encontrada ou inativa');
            return;
        }
        
        // Verificar se anestesista pertence à instituição
        $anestesista = $this->db->fetch(
            "SELECT u.* FROM usuarios u 
             JOIN perfis p ON u.perfil_id = p.id 
             WHERE u.id = ? AND u.instituicao_id = ? AND p.nome = 'anestesista' AND u.status = 'ativo'",
            [$anestesista_id, $instituicao['id']]
        );
        
        if (!$anestesista) {
            $this->showError('Anestesista não encontrado ou não pertence à instituição');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processarCadastroAnestesista($instituicao, $anestesista);
        } else {
            $this->showFormAnestesista($instituicao, $anestesista);
        }
    }
    
    /**
     * Processar cadastro via token
     */
    private function processarCadastroToken($token_info) {
        try {
            $this->db->getConnection()->beginTransaction();
            
            $dados = $this->validarDadosPaciente();
            $data_procedimento = $_POST['data_procedimento'] ?? null;
            
            // Determinar se é cadastro de instituição ou anestesista
            $anestesista_id = null;
            if ($token_info['tipo'] === 'anestesista') {
                $anestesista_id = $token_info['anestesista_id'];
            }
            
            // Gerar token de acesso único para o paciente
            $token_acesso = bin2hex(random_bytes(32));
            $link_acesso = '/paciente/acesso/' . $token_acesso;
            
            // Criar paciente
            $sql = "
                INSERT INTO pacientes (
                    instituicao_id, anestesista_id, nome, sobrenome, cpf, data_nascimento, sexo, 
                    telefone, email, endereco, data_procedimento, criado_via_qr, qr_code_id,
                    link_acesso, token_acesso, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE, ?, ?, ?, 'cadastrado', NOW())
            ";
            
            $this->db->query($sql, [
                $token_info['instituicao_id'],
                $anestesista_id,
                $dados['nome'],
                $dados['sobrenome'],
                $dados['cpf'],
                $dados['data_nascimento'],
                $dados['sexo'],
                $dados['telefone'],
                $dados['email'],
                $dados['endereco'],
                $data_procedimento,
                $token_info['id'],
                $link_acesso,
                $token_acesso
            ]);
            
            $paciente_id = $this->db->lastInsertId();
            
            // Se cadastro via anestesista, criar vínculo na tabela paciente_anestesistas
            if ($anestesista_id) {
                $sql = "
                    INSERT INTO paciente_anestesistas (paciente_id, anestesista_id, data_atribuicao, status) 
                    VALUES (?, ?, NOW(), 'ativo')
                ";
                $this->db->query($sql, [$paciente_id, $anestesista_id]);
            }
            
            // Se data do procedimento foi informada, criar agendamento
            if ($data_procedimento) {
                $this->criarAgendamento($paciente_id, $anestesista_id, $data_procedimento);
            }
            
            // Iniciar jornada do paciente
            $this->iniciarJornada($paciente_id);
            
            // Log da atividade
            $tipo_log = $token_info['tipo'] === 'anestesista' ? 'PACIENTE_CADASTRADO_VIA_QR_ANESTESISTA' : 'PACIENTE_CADASTRADO_VIA_QR_INSTITUICAO';
            $detalhes = $token_info['tipo'] === 'anestesista' 
                ? "Paciente cadastrado via QR do anestesista {$token_info['anestesista_nome']}"
                : "Paciente cadastrado via QR da instituição {$token_info['instituicao_nome']}";
            
            $this->logActivity($paciente_id, $tipo_log, $detalhes);
            
            $this->db->getConnection()->commit();
            
            // Enviar email de boas-vindas com link de acesso
            $this->enviarEmailBoasVindas($dados, $token_info, $link_acesso, $token_acesso);
            
            // Mensagem de sucesso
            $mensagem = $token_info['tipo'] === 'anestesista'
                ? "Cadastro realizado com sucesso! Você foi atribuído ao Dr(a). {$token_info['anestesista_nome']}. Sua equipe de anestesia será notificada e entrará em contato com você em breve."
                : "Cadastro realizado com sucesso! Sua equipe de anestesia será notificada e entrará em contato com você em breve.";
            
            $this->showSuccess($token_info['instituicao_nome'], $mensagem);
            
        } catch (Exception $e) {
            $this->db->getConnection()->rollback();
            $this->showError('Erro ao processar cadastro: ' . $e->getMessage());
        }
    }
    
    /**
     * Criar agendamento quando data do procedimento é informada
     */
    private function criarAgendamento($paciente_id, $anestesista_id, $data_procedimento) {
        // Verificar se já existe tabela de agendamentos
        $table_exists = $this->db->fetch("SHOW TABLES LIKE 'his_appointments'");
        
        if ($table_exists) {
            $sql = "
                INSERT INTO his_appointments (
                    paciente_id, anestesista_id, data_procedimento, 
                    status, created_at
                ) VALUES (?, ?, ?, 'agendado', NOW())
            ";
            
            $this->db->query($sql, [$paciente_id, $anestesista_id, $data_procedimento]);
        }
    }
    
    /**
     * Iniciar jornada do paciente
     */
    private function iniciarJornada($paciente_id) {
        $etapas = [
            'termo_lgpd' => 'Aceite do Termo LGPD',
            'selfie' => 'Captura de Selfie',
            'video' => 'Visualização de Vídeo',
            'questionario' => 'Preenchimento de Questionário',
            'autorizacao' => 'Autorização da Anestesia'
        ];
        
        foreach ($etapas as $etapa => $descricao) {
            $sql = "INSERT INTO jornada_paciente (paciente_id, etapa, status) 
                    VALUES (?, ?, 'pendente')";
            $this->db->query($sql, [$paciente_id, $etapa]);
        }
    }
    
    /**
     * Processar cadastro via QR da instituição (LEGACY)
     */
    private function processarCadastroInstituicao($instituicao) {
        try {
            $this->db->getConnection()->beginTransaction();
            
            $dados = $this->validarDadosPaciente();
            
            // Criar paciente sem anestesista
            $sql = "
                INSERT INTO pacientes (
                    instituicao_id, nome, cpf, data_nascimento, sexo, 
                    telefone, email, endereco, criado_via_qr, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, TRUE, NOW())
            ";
            
            $this->db->query($sql, [
                $instituicao['id'],
                $dados['nome'],
                $dados['cpf'],
                $dados['data_nascimento'],
                $dados['sexo'],
                $dados['telefone'],
                $dados['email'],
                $dados['endereco']
            ]);
            
            $paciente_id = $this->db->lastInsertId();
            
            // Log da atividade
            $this->logActivity($paciente_id, 'PACIENTE_CADASTRADO_VIA_QR_INSTITUICAO', 
                "Paciente cadastrado via QR da instituição {$instituicao['nome']}");
            
            $this->db->getConnection()->commit();
            
            $this->showSuccess($instituicao['nome'], 'Cadastro realizado com sucesso! Você será contactado em breve.');
            
        } catch (Exception $e) {
            $this->db->getConnection()->rollback();
            $this->showError('Erro ao processar cadastro: ' . $e->getMessage());
        }
    }
    
    /**
     * Processar cadastro via QR do anestesista
     */
    private function processarCadastroAnestesista($instituicao, $anestesista) {
        try {
            $this->db->getConnection()->beginTransaction();
            
            $dados = $this->validarDadosPaciente();
            
            // Criar paciente já vinculado ao anestesista
            $sql = "
                INSERT INTO pacientes (
                    instituicao_id, anestesista_id, nome, cpf, data_nascimento, sexo, 
                    telefone, email, endereco, criado_via_qr, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE, NOW())
            ";
            
            $this->db->query($sql, [
                $instituicao['id'],
                $anestesista['id'],
                $dados['nome'],
                $dados['cpf'],
                $dados['data_nascimento'],
                $dados['sexo'],
                $dados['telefone'],
                $dados['email'],
                $dados['endereco']
            ]);
            
            $paciente_id = $this->db->lastInsertId();
            
            // Criar alocação na tabela paciente_anestesistas
            $sql = "
                INSERT INTO paciente_anestesistas (paciente_id, anestesista_id, data_atribuicao, status) 
                VALUES (?, ?, NOW(), 'ativo')
            ";
            
            $this->db->query($sql, [$paciente_id, $anestesista['id']]);
            
            // Log da atividade
            $this->logActivity($paciente_id, 'PACIENTE_CADASTRADO_VIA_QR_ANESTESISTA', 
                "Paciente cadastrado via QR do anestesista {$anestesista['nome']}");
            
            $this->db->getConnection()->commit();
            
            $this->showSuccess($instituicao['nome'], 
                "Cadastro realizado com sucesso! Você foi atribuído ao Dr(a). {$anestesista['nome']}.");
            
        } catch (Exception $e) {
            $this->db->getConnection()->rollback();
            $this->showError('Erro ao processar cadastro: ' . $e->getMessage());
        }
    }
    
    /**
     * Validar dados do paciente
     */
    private function validarDadosPaciente() {
        $nome = trim($_POST['nome'] ?? '');
        $sobrenome = trim($_POST['sobrenome'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $data_nascimento = $_POST['data_nascimento'] ?? '';
        $sexo = $_POST['sexo'] ?? '';
        $telefone = trim($_POST['telefone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $endereco = trim($_POST['endereco'] ?? '');
        
        // Validações obrigatórias
        if (empty($nome)) {
            throw new Exception('Nome é obrigatório');
        }
        
        if (empty($cpf)) {
            throw new Exception('CPF é obrigatório');
        }
        
        if (empty($data_nascimento)) {
            throw new Exception('Data de nascimento é obrigatória');
        }
        
        if (empty($sexo)) {
            throw new Exception('Sexo é obrigatório');
        }
        
        if (empty($telefone)) {
            throw new Exception('Telefone é obrigatório');
        }
        
        // Validar CPF
        if (!$this->validarCPF($cpf)) {
            throw new Exception('CPF inválido');
        }
        
        // Verificar se CPF já existe
        $existing = $this->db->fetch("SELECT id FROM pacientes WHERE cpf = ?", [$cpf]);
        if ($existing) {
            throw new Exception('CPF já cadastrado no sistema');
        }
        
        // Validar email se fornecido
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email inválido');
        }
        
        return [
            'nome' => $nome,
            'sobrenome' => $sobrenome,
            'cpf' => $cpf,
            'data_nascimento' => $data_nascimento,
            'sexo' => $sexo,
            'telefone' => $telefone,
            'email' => $email,
            'endereco' => $endereco
        ];
    }
    
    /**
     * Validar CPF
     */
    private function validarCPF($cpf) {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        if (strlen($cpf) != 11) {
            return false;
        }
        
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Mostrar formulário de cadastro via token
     */
    private function showFormToken($token_info) {
        $data = [
            'title' => 'Cadastro de Paciente - ' . $token_info['instituicao_nome'],
            'instituicao' => [
                'nome' => $token_info['instituicao_nome'],
                'endereco' => $token_info['instituicao_endereco'],
                'telefone' => $token_info['instituicao_telefone']
            ],
            'tipo_cadastro' => $token_info['tipo'],
            'token' => $token_info['codigo'],
            'mostrar_data_procedimento' => true
        ];
        
        if ($token_info['tipo'] === 'anestesista') {
            $data['anestesista'] = [
                'nome' => $token_info['anestesista_nome'],
                'crm' => $token_info['anestesista_crm']
            ];
        }
        
        $this->view('cadastro-publico/form-token', $data);
    }
    
    /**
     * Mostrar formulário de cadastro da instituição (LEGACY)
     */
    private function showFormInstituicao($instituicao) {
        $data = [
            'title' => 'Cadastro de Paciente - ' . $instituicao['nome'],
            'instituicao' => $instituicao,
            'tipo_cadastro' => 'instituicao'
        ];
        
        $this->view('cadastro-publico/form', $data);
    }
    
    /**
     * Mostrar formulário de cadastro do anestesista (LEGACY)
     */
    private function showFormAnestesista($instituicao, $anestesista) {
        $data = [
            'title' => 'Cadastro de Paciente - ' . $instituicao['nome'],
            'instituicao' => $instituicao,
            'anestesista' => $anestesista,
            'tipo_cadastro' => 'anestesista'
        ];
        
        $this->view('cadastro-publico/form', $data);
    }
    
    /**
     * Mostrar mensagem de sucesso
     */
    /**
     * Enviar email de boas-vindas ao paciente
     */
    private function enviarEmailBoasVindas($dados_paciente, $token_info, $link_acesso, $token_acesso) {
        try {
            require_once BASE_PATH . '/app/classes/EmailSender.php';
            
            $emailSender = new EmailSender();
            
            // Construir URL completa do link de acesso
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $link_acesso_completo = $protocol . "://" . $host . $link_acesso;
            
            // Preparar dados para o template com link real
            $user_data = [
                'nome' => $dados_paciente['nome'],
                'email' => $dados_paciente['email'],
                'telefone' => $dados_paciente['telefone'],
                'link_acesso' => $link_acesso_completo,
                'token_acesso' => $token_acesso
            ];
            
            // Gerar corpo do email
            $email_body = $emailSender->generateWelcomeEmailTemplate($user_data, 'paciente');
            
            // Assunto do email
            $subject = "Bem-vindo ao NutriCheck - " . $token_info['instituicao_nome'];
            
            // Enviar email
            $result = $emailSender->sendEmail(
                $dados_paciente['email'],
                $subject,
                $email_body
            );
            
            // Log do envio
            if ($result) {
                if (function_exists('logError')) {
                    logError("Email de boas-vindas enviado com sucesso", [
                        'email' => $dados_paciente['email'],
                        'nome' => $dados_paciente['nome']
                    ]);
                }
            } else {
                if (function_exists('logError')) {
                    logError("Falha ao enviar email de boas-vindas", [
                        'email' => $dados_paciente['email'],
                        'nome' => $dados_paciente['nome']
                    ]);
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            if (function_exists('logError')) {
                logError("Erro ao enviar email de boas-vindas", [
                    'error' => $e->getMessage(),
                    'email' => $dados_paciente['email']
                ]);
            }
            return false;
        }
    }
    
    private function showSuccess($instituicao_nome, $mensagem) {
        $data = [
            'title' => 'Cadastro Realizado',
            'instituicao_nome' => $instituicao_nome,
            'mensagem' => $mensagem,
            'tipo' => 'success'
        ];
        
        $this->view('cadastro-publico/resultado', $data);
    }
    
    /**
     * Mostrar mensagem de erro
     */
    private function showError($mensagem) {
        $data = [
            'title' => 'Erro no Cadastro',
            'mensagem' => $mensagem,
            'tipo' => 'error'
        ];
        
        $this->view('cadastro-publico/resultado', $data);
    }
    
    /**
     * Log de atividade
     */
    protected function logActivity($action, $details = '') {
        $sql = "
            INSERT INTO logs_ativade (usuario_id, paciente_id, acao, detalhes, ip_address, user_agent, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ";
        
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        $this->db->query($sql, [$_SESSION['user_id'] ?? null, $_SESSION['paciente_id'] ?? null, $action, $details, $ip_address, $user_agent]);
    }
}
