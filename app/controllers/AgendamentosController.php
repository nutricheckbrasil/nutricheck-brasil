<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/RBAC.php';

class AgendamentosController extends BaseController {
    private $rbac;
    
    public function __construct() {
        parent::__construct();
        $this->rbac = new RBAC();
    }
    
    private function getInstituicaoId() {
        // Para instituições, usar o user_id como instituicao_id
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            return $_SESSION['user_id'];
        } else {
            return $_SESSION['instituicao_id'];
        }
    }
    
    public function index() {
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            $this->redirect('/auth/login');
            return;
        }
        
        // Verificar permissão
        $hasPermission = false;
        
        // Se for instituição
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            $hasPermission = $this->rbac->hasPermissionForInstitution('agendamentos');
        } else {
            // Se for usuário normal
            $hasPermission = $this->rbac->hasPermission($user_id, 'agendamentos');
        }
        
        if (!$hasPermission) {
            $_SESSION['flash_message'] = 'Acesso negado. Você não tem permissão para acessar esta página.';
            $this->redirect('/dashboard');
            return;
        }
        
        $instituicao_id = $this->getInstituicaoId();
        
        // Debug temporário - remover depois
        error_log("Instituição ID: " . $instituicao_id);
        error_log("User type: " . ($_SESSION['user_type'] ?? 'null'));
        error_log("User ID: " . ($_SESSION['user_id'] ?? 'null'));
        error_log("Instituicao ID session: " . ($_SESSION['instituicao_id'] ?? 'null'));

        // Filtros
        $data = $this->getGet('data', '');
        $status = $this->getGet('status', '');
        $anestesista_id = $this->getGet('anestesista_id', '');
        $instituicao_filtro = $this->getGet('instituicao_id', '');
        
        // Construir query com filtros
        $where_conditions = [];
        $params = [];
        
        // Se for admin, pode filtrar por instituição
        if (isset($_SESSION['perfil_id']) && $_SESSION['perfil_id'] == 6) {
            // Admin pode ver todas as instituições
            if ($instituicao_filtro) {
                $where_conditions[] = 'a.instituicao_id = ?';
                $params[] = $instituicao_filtro;
            }
        } else {
            // Outros usuários veem apenas sua instituição
            $where_conditions[] = 'a.instituicao_id = ?';
            $params[] = $instituicao_id;
        }

        if ($data && !empty($data)) {
            $where_conditions[] = 'a.data_agendamento = ?';
            $params[] = $data;
        }
        
        if ($status) {
            $where_conditions[] = 'a.status = ?';
            $params[] = $status;
        }
        
        if ($anestesista_id) {
            $where_conditions[] = 'a.anestesista_id = ?';
            $params[] = $anestesista_id;
        }
        
        $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
        
        // Buscar agendamentos
        $sql = "
            SELECT 
                a.*,
                p.nome as paciente_nome,
                p.data_nascimento,
                p.sexo,
                pr.nome as procedimento_nome,
                u.nome as anestesista_nome,
                i.nome as instituicao_nome
            FROM agendamentos a
            LEFT JOIN pacientes p ON a.paciente_id = p.id
            LEFT JOIN procedimentos pr ON a.procedimento_id = pr.id
            LEFT JOIN usuarios u ON a.anestesista_id = u.id
            LEFT JOIN instituicoes i ON a.instituicao_id = i.id
            {$where_clause}
            ORDER BY a.data_agendamento ASC, a.hora_agendamento ASC
        ";
        
        $agendamentos = $this->db->fetchAll($sql, $params);

        // Buscar pacientes para o formulário com dados completos
        $pacientes = $this->db->fetchAll("
            SELECT 
                p.id, 
                p.nome, 
                p.data_nascimento, 
                p.sexo,
                p.procedimento,
                p.data_procedimento,
                p.anestesista_id,
                u.nome as anestesista_nome,
                u.crm as anestesista_crm,
                pr.id as procedimento_id,
                pr.nome as procedimento_nome
            FROM pacientes p
            LEFT JOIN usuarios u ON p.anestesista_id = u.id
            LEFT JOIN procedimentos pr ON p.procedimento_id = pr.id
            WHERE p.instituicao_id = ? AND p.inativo = 0
            ORDER BY p.nome
        ", [$instituicao_id]);
        
        // Buscar anestesistas
        $anestesistas = $this->db->fetchAll("
            SELECT id, nome 
            FROM usuarios 
            WHERE instituicao_id = ? AND perfil_id = 3 AND status = 'ativo'
            ORDER BY nome
        ", [$instituicao_id]);
        
        // Debug temporário - remover depois
        error_log("Anestesistas encontrados: " . json_encode($anestesistas));
        error_log("Pacientes encontrados: " . json_encode(array_slice($pacientes, 0, 2))); // Primeiros 2 pacientes para debug
        
        // Buscar procedimentos
        $procedimentos = $this->db->fetchAll("
            SELECT id, nome 
            FROM procedimentos 
            WHERE status = 'ativo'
            ORDER BY nome
        ");
        
        // Buscar instituições (apenas para admin)
        $instituicoes = [];
        if (isset($_SESSION['perfil_id']) && $_SESSION['perfil_id'] == 6) {
            $instituicoes = $this->db->fetchAll("
                SELECT id, nome 
                FROM instituicoes 
                WHERE ativo = TRUE
                ORDER BY nome
            ");
        }
        
        // Estatísticas
        $stats = $this->getStats($instituicao_id);
        
        $data = [
            'title' => 'Agendamentos',
            'agendamentos' => $agendamentos,
            'pacientes' => $pacientes,
            'anestesistas' => $anestesistas,
            'procedimentos' => $procedimentos,
            'instituicoes' => $instituicoes,
            'stats' => $stats,
            'filtros' => [
                'data' => $data,
                'status' => $status,
                'anestesista_id' => $anestesista_id,
                'instituicao_id' => $instituicao_filtro
            ]
        ];
        
        ob_start();
        include APP_PATH . '/views/agendamentos/index-no-bootstrap.php';
        $content = ob_get_clean();
        
        include APP_PATH . '/views/layouts/main.php';
    }
    
    public function create() {
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            $this->redirect('/auth/login');
            return;
        }
        
        // Verificar permissão
        $hasPermission = false;
        
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            $hasPermission = $this->rbac->hasPermissionForInstitution('agendamentos');
        } else {
            $hasPermission = $this->rbac->hasPermission($user_id, 'agendamentos');
        }
        
        if (!$hasPermission) {
            $_SESSION['flash_message'] = 'Acesso negado.';
            $this->redirect('/dashboard');
            return;
        }
        
        $instituicao_id = $this->getInstituicaoId();
        
        $errors = [];
        $values = [
            'paciente_id' => '',
            'anestesista_id' => '',
            'procedimento_id' => '',
            'data_agendamento' => '',
            'hora_agendamento' => '',
            'observacoes' => ''
        ];
        
        if ($this->isPost()) {
            $values = [
                'paciente_id' => $this->getPost('paciente_id'),
                'anestesista_id' => $this->getPost('anestesista_id'),
                'procedimento_id' => $this->getPost('procedimento_id'),
                'data_agendamento' => $this->getPost('data_agendamento'),
                'hora_agendamento' => $this->getPost('hora_agendamento'),
                'observacoes' => $this->getPost('observacoes')
            ];
            
            // Validações
            if (empty($values['paciente_id'])) {
                $errors[] = 'Selecione um paciente.';
            }
            if (empty($values['anestesista_id'])) {
                $errors[] = 'Selecione um anestesista.';
            }
            if (empty($values['procedimento_id'])) {
                $errors[] = 'Selecione um procedimento.';
            }
            if (empty($values['data_agendamento'])) {
                $errors[] = 'Selecione uma data.';
            }
            if (empty($values['hora_agendamento'])) {
                $errors[] = 'Selecione um horário.';
            }
            
            // Verificar se já existe agendamento no mesmo horário
            if (empty($errors)) {
                $existing = $this->db->fetch("
                    SELECT id FROM agendamentos 
                    WHERE anestesista_id = ? AND data_agendamento = ? AND hora_agendamento = ?
                ", [$values['anestesista_id'], $values['data_agendamento'], $values['hora_agendamento']]);
                
                if ($existing) {
                    $errors[] = 'Já existe um agendamento para este anestesista no mesmo horário.';
                }
            }
            
            if (empty($errors)) {
                $sql = "
                    INSERT INTO agendamentos 
                    (paciente_id, anestesista_id, instituicao_id, procedimento_id, data_agendamento, hora_agendamento, observacoes, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'agendado')
                ";
                
                $this->db->query($sql, [
                    $values['paciente_id'],
                    $values['anestesista_id'],
                    $instituicao_id,
                    $values['procedimento_id'],
                    $values['data_agendamento'],
                    $values['hora_agendamento'],
                    $values['observacoes']
                ]);
                
                $_SESSION['flash_message'] = 'Agendamento criado com sucesso!';
                $_SESSION['flash_type'] = 'success';
                $this->redirect('/agendamentos');
                return;
            }
        }
        
        // Buscar dados para os selects
        $pacientes = $this->db->fetchAll("
            SELECT 
                p.id, 
                p.nome, 
                p.data_nascimento, 
                p.sexo,
                p.procedimento,
                p.data_procedimento,
                p.anestesista_id,
                u.nome as anestesista_nome,
                u.crm as anestesista_crm,
                pr.id as procedimento_id,
                pr.nome as procedimento_nome
            FROM pacientes p
            LEFT JOIN usuarios u ON p.anestesista_id = u.id
            LEFT JOIN procedimentos pr ON p.procedimento_id = pr.id
            WHERE p.instituicao_id = ? AND p.inativo = 0
            ORDER BY p.nome
        ", [$instituicao_id]);
        
        $anestesistas = $this->db->fetchAll("
            SELECT id, nome 
            FROM usuarios 
            WHERE instituicao_id = ? AND perfil_id = 3 AND status = 'ativo'
            ORDER BY nome
        ", [$instituicao_id]);
        
        $procedimentos = $this->db->fetchAll("
            SELECT id, nome 
            FROM procedimentos 
            WHERE status = 'ativo'
            ORDER BY nome
        ");
        
        $data = [
            'title' => 'Novo Agendamento',
            'pacientes' => $pacientes,
            'anestesistas' => $anestesistas,
            'procedimentos' => $procedimentos,
            'errors' => $errors,
            'values' => $values
        ];
        
        ob_start();
        include APP_PATH . '/views/agendamentos/create.php';
        $content = ob_get_clean();
        
        include APP_PATH . '/views/layouts/main.php';
    }

    public function edit($id) {
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            $this->redirect('/auth/login');
            return;
        }
        
        // Verificar permissão
        $hasPermission = false;
        
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            $hasPermission = $this->rbac->hasPermissionForInstitution('agendamentos');
        } else {
            $hasPermission = $this->rbac->hasPermission($user_id, 'agendamentos');
        }
        
        if (!$hasPermission) {
            $_SESSION['flash_message'] = 'Acesso negado.';
            $this->redirect('/dashboard');
            return;
        }
        
        $instituicao_id = $this->getInstituicaoId();
        
        // Buscar agendamento
        $agendamento = $this->db->fetch("
            SELECT * FROM agendamentos 
            WHERE id = ? AND instituicao_id = ?
        ", [$id, $instituicao_id]);
        
        if (!$agendamento) {
            $_SESSION['flash_message'] = 'Agendamento não encontrado.';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('/agendamentos');
            return;
        }

        $errors = [];
        $values = [
            'paciente_id' => $agendamento['paciente_id'],
            'anestesista_id' => $agendamento['anestesista_id'],
            'procedimento_id' => $agendamento['procedimento_id'],
            'data_agendamento' => $agendamento['data_agendamento'],
            'hora_agendamento' => $agendamento['hora_agendamento'],
            'observacoes' => $agendamento['observacoes'],
            'status' => $agendamento['status']
        ];
        
        if ($this->isPost()) {
            $values = [
                'paciente_id' => $this->getPost('paciente_id'),
                'anestesista_id' => $this->getPost('anestesista_id'),
                'procedimento_id' => $this->getPost('procedimento_id'),
                'data_agendamento' => $this->getPost('data_agendamento'),
                'hora_agendamento' => $this->getPost('hora_agendamento'),
                'observacoes' => $this->getPost('observacoes'),
                'status' => $this->getPost('status')
            ];
            
            // Validações
            if (empty($values['paciente_id'])) {
                $errors[] = 'Selecione um paciente.';
            }
            if (empty($values['anestesista_id'])) {
                $errors[] = 'Selecione um anestesista.';
            }
            if (empty($values['procedimento_id'])) {
                $errors[] = 'Selecione um procedimento.';
            }
            if (empty($values['data_agendamento'])) {
                $errors[] = 'Selecione uma data.';
            }
            if (empty($values['hora_agendamento'])) {
                $errors[] = 'Selecione um horário.';
            }
            
            // Verificar conflito de horário (exceto o próprio agendamento)
            if (empty($errors)) {
                $existing = $this->db->fetch("
                    SELECT id FROM agendamentos 
                    WHERE anestesista_id = ? AND data_agendamento = ? AND hora_agendamento = ? AND id != ?
                ", [$values['anestesista_id'], $values['data_agendamento'], $values['hora_agendamento'], $id]);
                
                if ($existing) {
                    $errors[] = 'Já existe um agendamento para este anestesista no mesmo horário.';
                }
            }

            if (empty($errors)) {
                $sql = "
                    UPDATE agendamentos SET 
                        paciente_id = ?, anestesista_id = ?, procedimento_id = ?, 
                        data_agendamento = ?, hora_agendamento = ?, observacoes = ?, status = ?
                    WHERE id = ? AND instituicao_id = ?
                ";
                
                $this->db->query($sql, [
                    $values['paciente_id'],
                    $values['anestesista_id'],
                    $values['procedimento_id'],
                    $values['data_agendamento'],
                    $values['hora_agendamento'],
                    $values['observacoes'],
                    $values['status'],
                    $id,
                    $instituicao_id
                ]);
                
                $_SESSION['flash_message'] = 'Agendamento atualizado com sucesso!';
                $_SESSION['flash_type'] = 'success';
                $this->redirect('/agendamentos');
                return;
            }
        }
        
        // Buscar dados para os selects
        $pacientes = $this->db->fetchAll("
            SELECT 
                p.id, 
                p.nome, 
                p.data_nascimento, 
                p.sexo,
                p.procedimento,
                p.data_procedimento,
                p.anestesista_id,
                u.nome as anestesista_nome,
                u.crm as anestesista_crm,
                pr.id as procedimento_id,
                pr.nome as procedimento_nome
            FROM pacientes p
            LEFT JOIN usuarios u ON p.anestesista_id = u.id
            LEFT JOIN procedimentos pr ON p.procedimento_id = pr.id
            WHERE p.instituicao_id = ? AND p.inativo = 0
            ORDER BY p.nome
        ", [$instituicao_id]);
        
        $anestesistas = $this->db->fetchAll("
            SELECT id, nome 
            FROM usuarios 
            WHERE instituicao_id = ? AND perfil_id = 3 AND status = 'ativo'
            ORDER BY nome
        ", [$instituicao_id]);
        
        $procedimentos = $this->db->fetchAll("
            SELECT id, nome 
            FROM procedimentos 
            WHERE status = 'ativo'
            ORDER BY nome
        ");
        
        $data = [
            'title' => 'Editar Agendamento',
            'agendamento' => $agendamento,
            'pacientes' => $pacientes,
            'anestesistas' => $anestesistas,
            'procedimentos' => $procedimentos,
            'errors' => $errors,
            'values' => $values
        ];
        
        ob_start();
        include APP_PATH . '/views/agendamentos/edit.php';
        $content = ob_get_clean();
        
        include APP_PATH . '/views/layouts/main.php';
    }

    public function delete($id) {
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            $this->redirect('/auth/login');
            return;
        }
        
        // Verificar permissão
        $hasPermission = false;
        
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            $hasPermission = $this->rbac->hasPermissionForInstitution('agendamentos');
        } else {
            $hasPermission = $this->rbac->hasPermission($user_id, 'agendamentos');
        }
        
        if (!$hasPermission) {
            $_SESSION['flash_message'] = 'Acesso negado.';
            $this->redirect('/dashboard');
            return;
        }
        
        $instituicao_id = $this->getInstituicaoId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $agendamento = $this->db->fetch("
                SELECT * FROM agendamentos 
                WHERE id = ? AND instituicao_id = ?
            ", [$id, $instituicao_id]);
            
            if ($agendamento) {
                $this->db->query("DELETE FROM agendamentos WHERE id = ?", [$id]);
                $_SESSION['flash_message'] = "Agendamento excluído com sucesso!";
                $_SESSION['flash_type'] = "success";
            } else {
                $_SESSION['flash_message'] = "Agendamento não encontrado!";
                $_SESSION['flash_type'] = "danger";
            }
            
            $this->redirect('/agendamentos');
        }
    }

    public function getPacienteDados() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['paciente_id'])) {
            $paciente_id = intval($_GET['paciente_id']);
            $instituicao_id = $this->getInstituicaoId();
            
            // Buscar dados completos do paciente com procedimento e anestesista associados
            $sql = "SELECT 
                        p.*,
                        pr.id as procedimento_id,
                        pr.nome as procedimento_nome,
                        u.id as anestesista_id,
                        u.nome as anestesista_nome,
                        CASE 
                            WHEN EXISTS (SELECT 1 FROM agendamentos a WHERE a.paciente_id = p.id AND a.status = 'realizado') THEN 'realizado'
                            WHEN EXISTS (SELECT 1 FROM agendamentos a WHERE a.paciente_id = p.id AND a.status = 'agendado') THEN 'agendado'
                            ELSE 'nunca_fez'
                        END as status_procedimento
                    FROM pacientes p
                    LEFT JOIN procedimentos pr ON p.procedimento_id = pr.id
                    LEFT JOIN paciente_anestesistas pa ON p.id = pa.paciente_id AND pa.status = 'ativo'
                    LEFT JOIN usuarios u ON pa.anestesista_id = u.id
                    WHERE p.id = ? AND p.instituicao_id = ?";
            
            $paciente = $this->db->fetch($sql, [$paciente_id, $instituicao_id]);
            
            if ($paciente) {
                // Calcular idade
                $idade = '';
                if ($paciente['data_nascimento']) {
                    $nasc = new DateTime($paciente['data_nascimento']);
                    $hoje = new DateTime();
                    $idade = $hoje->diff($nasc)->y;
                }
                
                $response = [
                    'success' => true,
                    'paciente' => [
                        'id' => $paciente['id'],
                        'nome' => $paciente['nome'],
                        'idade' => $idade,
                        'sexo' => $paciente['sexo'],
                        'telefone' => $paciente['telefone'],
                        'email' => $paciente['email']
                    ],
                    'procedimento' => [
                        'id' => $paciente['procedimento_id'],
                        'nome' => $paciente['procedimento_nome']
                    ],
                    'anestesista' => [
                        'id' => $paciente['anestesista_id'],
                        'nome' => $paciente['anestesista_nome']
                    ],
                    'status_procedimento' => $paciente['status_procedimento']
                ];
                
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Paciente não encontrado']);
                exit;
            }
        }
    }

    private function getStats($instituicao_id) {
        $hoje = date('Y-m-d');
        $inicio_semana = date('Y-m-d', strtotime('monday this week'));
        $fim_semana = date('Y-m-d', strtotime('sunday this week'));
        
        return [
            'hoje' => $this->db->fetch("
                SELECT COUNT(*) as total FROM agendamentos 
                WHERE instituicao_id = ? AND data_agendamento = ?
            ", [$instituicao_id, $hoje])['total'],
            
            'semana' => $this->db->fetch("
                SELECT COUNT(*) as total FROM agendamentos 
                WHERE instituicao_id = ? AND data_agendamento BETWEEN ? AND ?
            ", [$instituicao_id, $inicio_semana, $fim_semana])['total'],
            
            'concluidos' => $this->db->fetch("
                SELECT COUNT(*) as total FROM agendamentos 
                WHERE instituicao_id = ? AND status = 'concluido'
            ", [$instituicao_id])['total'],
            
            'cancelados' => $this->db->fetch("
                SELECT COUNT(*) as total FROM agendamentos 
                WHERE instituicao_id = ? AND status = 'cancelado'
            ", [$instituicao_id])['total']
        ];
    }
} 