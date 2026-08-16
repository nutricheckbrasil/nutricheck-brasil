<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/RBAC.php';
require_once __DIR__ . '/../classes/InstitutionContext.php';

class EquipeNutricionistasController extends BaseController {
    private $rbac;
    private $context;
    
    public function __construct() {
        parent::__construct();
        $this->rbac = new RBAC();
        $this->context = new InstitutionContext();
    }
    
    /**
     * Listar equipe de nutricionistas (Admin)
     */
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
            $hasPermission = $this->rbac->hasPermissionForInstitution('equipe_anestesistas');
        } else {
            // Se for usuário normal
            $hasPermission = $this->rbac->hasPermission($user_id, 'equipe_anestesistas');
        }
        
        if (!$hasPermission) {
            $this->redirect('/dashboard');
            return;
        }
        
        // Para instituições, o user_id é o próprio ID da instituição
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            $instituicao_id = $user_id;
        } else {
            $instituicao_id = $this->context->getCurrentInstitutionId($user_id);
            
            if (!$instituicao_id) {
                $this->redirect('/dashboard');
                return;
            }
        }
        
        // Obter anestesistas da instituição
        $anestesistas = $this->getAnestesistas($instituicao_id);
        
        // Obter pacientes não alocados
        $pacientes_nao_alocados = $this->getPacientesNaoAlocados($instituicao_id);
        
        // Calcular estatísticas
        $stats = [
            'total_anestesistas' => count($anestesistas),
            'total_pacientes' => $this->getTotalPacientes($instituicao_id),
            'pacientes_nao_alocados' => count($pacientes_nao_alocados),
            'pacientes_alocados' => $this->getTotalPacientesAlocados($instituicao_id)
        ];
        
        // Obter anestesistas com seus pacientes
        $anestesistas_com_pacientes = $this->getAnestesistasComPacientes($instituicao_id);
        
        // Definir variáveis para a view
        $title = 'Equipe de Nutricionistas';
        
        // Capturar o conteúdo da view
        ob_start();
        require_once APP_PATH . '/views/equipe-nutricionistas/index.php';
        $content = ob_get_clean();
        
        // Incluir o layout principal
        require_once APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Minha equipe (Nutricionista)
     */
    public function minhaEquipe() {
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            $this->redirect('/auth/login', 'Faça login para continuar', 'error');
            return;
        }
        
        // Verificar se é nutricionista (perfil anestesista no banco)
        $profile = $this->rbac->getUserProfile($user_id);
        if (!$profile || $profile['perfil'] !== 'anestesista') {
            $this->redirect('/dashboard', 'Acesso restrito a nutricionistas', 'error');
            return;
        }
        
        // Obter pacientes do nutricionista
        $pacientes = $this->getPacientesAnestesista($user_id);
        
        $data = [
            'title' => 'Minha Equipe & Pacientes',
            'pacientes' => $pacientes,
            'anestesista_id' => $user_id
        ];
        
        $this->view('equipe-nutricionistas/minha-equipe', $data);
    }
    
    /**
     * Alocar paciente para nutricionista
     */
    public function alocarPaciente() {
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            $this->jsonResponse(['success' => false, 'message' => 'Usuário não autenticado']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        try {
            // Ler dados JSON
            $input = json_decode(file_get_contents('php://input'), true);
            $paciente_id = $input['paciente_id'] ?? null;
            $anestesista_id = $input['anestesista_id'] ?? null;
            
            if (!$paciente_id || !$anestesista_id) {
                throw new Exception('Dados obrigatórios não informados');
            }
            
            // Verificar se o usuário pode alocar
            if (!$this->canAllocatePatient($user_id, $paciente_id, $anestesista_id)) {
                throw new Exception('Você não tem permissão para realizar esta alocação');
            }
            
            // Verificar se o paciente não está inativo
            $paciente = $this->db->fetch(
                "SELECT inativo FROM pacientes WHERE id = ?", 
                [$paciente_id]
            );
            
            if (!$paciente) {
                throw new Exception('Paciente não encontrado');
            }
            
            if ($paciente['inativo'] == 1) {
                throw new Exception('Não é possível alocar um paciente inativo');
            }
            
            // Verificar se já existe alocação ativa para este anestesista
            $existing = $this->db->fetch(
                "SELECT id FROM paciente_anestesistas WHERE paciente_id = ? AND anestesista_id = ? AND status = 'ativo'",
                [$paciente_id, $anestesista_id]
            );
            
            if ($existing) {
                throw new Exception('Paciente já está alocado para este nutricionista');
            }
            
            // Desalocar de outros anestesistas (se houver)
            $this->db->query(
                "UPDATE paciente_anestesistas SET status = 'inativo' WHERE paciente_id = ? AND status = 'ativo'",
                [$paciente_id]
            );
            
            // Verificar se já existe registro (mesmo inativo) e reativar ou criar novo
            $existing_record = $this->db->fetch(
                "SELECT id FROM paciente_anestesistas WHERE paciente_id = ? AND anestesista_id = ?",
                [$paciente_id, $anestesista_id]
            );
            
            if ($existing_record) {
                // Reativar registro existente
                $sql = "
                    UPDATE paciente_anestesistas 
                    SET status = 'ativo', data_atribuicao = NOW() 
                    WHERE paciente_id = ? AND anestesista_id = ?
                ";
                $this->db->query($sql, [$paciente_id, $anestesista_id]);
            } else {
                // Criar nova alocação
                $sql = "
                    INSERT INTO paciente_anestesistas (paciente_id, anestesista_id, data_atribuicao, status) 
                    VALUES (?, ?, NOW(), 'ativo')
                ";
                $this->db->query($sql, [$paciente_id, $anestesista_id]);
            }
            
            // ATUALIZAR o campo anestesista_id na tabela pacientes para manter consistência
            $this->db->query(
                "UPDATE pacientes SET anestesista_id = ? WHERE id = ?",
                [$anestesista_id, $paciente_id]
            );
            
            // Log da atividade
            $this->logActivity('PACIENTE_ALOCADO', "Paciente ID {$paciente_id} alocado para nutricionista ID {$anestesista_id}");
            
            $this->jsonResponse(['success' => true, 'message' => 'Paciente alocado com sucesso!']);
            
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Desalocar paciente
     */
    public function desalocarPaciente() {
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (!$user_id) {
            $this->jsonResponse(['success' => false, 'message' => 'Usuário não autenticado']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        try {
            // Ler dados JSON
            $input = json_decode(file_get_contents('php://input'), true);
            $paciente_id = $input['paciente_id'] ?? null;
            
            if (!$paciente_id) {
                throw new Exception('ID do paciente não informado');
            }
            
            // Verificar se o usuário pode desalocar
            if (!$this->canDeallocatePatient($user_id, $paciente_id)) {
                throw new Exception('Você não tem permissão para realizar esta desalocação');
            }
            
            // Verificar se existe alocação ativa
            $existing = $this->db->fetch(
                "SELECT id FROM paciente_anestesistas WHERE paciente_id = ? AND status = 'ativo'",
                [$paciente_id]
            );
            
            if (!$existing) {
                throw new Exception('Paciente não está alocado para nenhum nutricionista');
            }
            
            // Desalocar paciente (de todos os anestesistas)
            $sql = "
                UPDATE paciente_anestesistas 
                SET status = 'inativo' 
                WHERE paciente_id = ? AND status = 'ativo'
            ";
            
            $result = $this->db->query($sql, [$paciente_id]);
            
            if ($result->rowCount() === 0) {
                throw new Exception('Erro ao desalocar paciente');
            }
            
            // LIMPAR o campo anestesista_id na tabela pacientes para manter consistência
            $this->db->query(
                "UPDATE pacientes SET anestesista_id = NULL WHERE id = ?",
                [$paciente_id]
            );
            
            // Log da atividade
            $this->logActivity('PACIENTE_DESALOCADO', "Paciente ID {$paciente_id} desalocado");
            
            $this->jsonResponse(['success' => true, 'message' => 'Paciente desalocado com sucesso!']);
            
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * Obter nutricionistas da instituição
     */
    private function getAnestesistas($instituicao_id) {
        $sql = "
            SELECT 
                u.id,
                u.nome,
                u.email,
                u.telefone,
                u.crm,
                COUNT(pa.paciente_id) as total_pacientes,
                COUNT(CASE WHEN pa.status = 'ativo' THEN 1 END) as pacientes_ativos
            FROM usuarios u
            JOIN perfis p ON u.perfil_id = p.id
            LEFT JOIN paciente_anestesistas pa ON u.id = pa.anestesista_id
            WHERE u.instituicao_id = ? 
            AND p.nome = 'anestesista' 
            AND u.status = 'ativo'
            GROUP BY u.id, u.nome, u.email, u.telefone, u.crm
            ORDER BY u.nome
        ";
        
        return $this->db->fetchAll($sql, [$instituicao_id]);
    }
    
    /**
     * Obter pacientes não alocados
     */
    private function getPacientesNaoAlocados($instituicao_id) {
        $sql = "
            SELECT 
                p.id,
                p.nome,
                p.cpf,
                p.data_nascimento,
                p.procedimento,
                p.data_procedimento,
                p.status,
                p.created_at,
                p.inativo
            FROM pacientes p
            LEFT JOIN paciente_anestesistas pa ON p.id = pa.paciente_id AND pa.status = 'ativo'
            WHERE p.instituicao_id = ? 
            AND pa.id IS NULL
            AND p.inativo = 0
            ORDER BY p.created_at DESC
        ";
        
        return $this->db->fetchAll($sql, [$instituicao_id]);
    }
    
    /**
     * Obter pacientes de um nutricionista
     */
    private function getPacientesAnestesista($anestesista_id) {
        $sql = "
            SELECT 
                p.id,
                p.nome,
                p.cpf,
                p.data_nascimento,
                p.procedimento,
                p.data_procedimento,
                p.status,
                pa.data_atribuicao,
                pa.observacoes,
                p.inativo
            FROM pacientes p
            JOIN paciente_anestesistas pa ON p.id = pa.paciente_id
            WHERE pa.anestesista_id = ? 
            AND pa.status = 'ativo'
            AND p.inativo = 0
            ORDER BY pa.data_atribuicao DESC
        ";
        
        return $this->db->fetchAll($sql, [$anestesista_id]);
    }
    
    /**
     * Verificar se usuário pode alocar paciente
     */
    private function canAllocatePatient($user_id, $paciente_id, $anestesista_id) {
        // Se for instituição (user_type = 'instituicao')
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            return $this->validateInstitutionAccessForInstitution($user_id, $paciente_id, $anestesista_id);
        }
        
        // Para usuários normais, verificar perfil
        $profile = $this->rbac->getUserProfile($user_id);
        
        if (!$profile) {
            return false;
        }
        
        // Admin pode alocar qualquer paciente
        if ($profile['perfil'] === 'admin') {
            return true;
        }
        
        // Instituição admin pode alocar pacientes de sua instituição
        if ($profile['perfil'] === 'instituicao_admin') {
            return $this->validateInstitutionAccess($user_id, $paciente_id, $anestesista_id);
        }
        
        // Atendente pode alocar pacientes de sua instituição
        if ($profile['perfil'] === 'atendente') {
            return $this->validateInstitutionAccess($user_id, $paciente_id, $anestesista_id);
        }
        
        // Nutricionista pode alocar pacientes para qualquer nutricionista da mesma instituição
        if ($profile['perfil'] === 'anestesista') {
            return $this->validateInstitutionAccess($user_id, $paciente_id, $anestesista_id);
        }
        
        return false;
    }
    
    /**
     * Validar acesso para instituições
     */
    private function validateInstitutionAccessForInstitution($instituicao_id, $paciente_id, $anestesista_id) {
        // Se anestesista_id for null (para desalocação), verificar apenas paciente
        if ($anestesista_id === null) {
            $sql = "
                SELECT p.instituicao_id as paciente_instituicao
                FROM pacientes p
                WHERE p.id = ? AND p.instituicao_id = ?
            ";
            
            $result = $this->db->fetch($sql, [$paciente_id, $instituicao_id]);
            
            return $result && $result['paciente_instituicao'] == $instituicao_id;
        }
        
        // Verificar se paciente e anestesista são da mesma instituição
        $sql = "
            SELECT 
                p.instituicao_id as paciente_instituicao,
                u.instituicao_id as anestesista_instituicao
            FROM pacientes p
            JOIN usuarios u ON u.id = ?
            WHERE p.id = ? AND p.instituicao_id = ? AND u.instituicao_id = ?
        ";
        
        $result = $this->db->fetch($sql, [$anestesista_id, $paciente_id, $instituicao_id, $instituicao_id]);
        
        return $result && 
               $result['paciente_instituicao'] == $instituicao_id && 
               $result['anestesista_instituicao'] == $instituicao_id;
    }
    
    /**
     * Verificar se usuário pode desalocar paciente
     */
    private function canDeallocatePatient($user_id, $paciente_id) {
        // Se for instituição (user_type = 'instituicao')
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            return $this->validateInstitutionAccessForInstitution($user_id, $paciente_id, null);
        }
        
        // Para usuários normais, verificar perfil
        $profile = $this->rbac->getUserProfile($user_id);
        
        if (!$profile) {
            return false;
        }
        
        // Admin pode desalocar qualquer paciente
        if ($profile['perfil'] === 'admin') {
            return true;
        }
        
        // Instituição admin pode desalocar pacientes de sua instituição
        if ($profile['perfil'] === 'instituicao_admin') {
            return $this->validateInstitutionAccess($user_id, $paciente_id, null);
        }
        
        // Atendente pode desalocar pacientes de sua instituição
        if ($profile['perfil'] === 'atendente') {
            return $this->validateInstitutionAccess($user_id, $paciente_id, null);
        }
        
        // Nutricionista pode desalocar pacientes da mesma instituição
        if ($profile['perfil'] === 'anestesista') {
            return $this->validateInstitutionAccess($user_id, $paciente_id, null);
        }
        
        return false;
    }
    
    /**
     * Validar acesso à instituição
     */
    private function validateInstitutionAccess($user_id, $paciente_id, $anestesista_id) {
        // Se anestesista_id for null (para desalocação), verificar apenas paciente e usuário
        if ($anestesista_id === null) {
            $sql = "
                SELECT 
                    p.instituicao_id as paciente_instituicao,
                    u.instituicao_id as usuario_instituicao
                FROM pacientes p
                JOIN usuarios u ON u.id = ?
                WHERE p.id = ?
            ";
            
            $result = $this->db->fetch($sql, [$user_id, $paciente_id]);
            
            if (!$result) {
                return false;
            }
            
            // Verificar se paciente e usuário são da mesma instituição
            return $result['paciente_instituicao'] === $result['usuario_instituicao'];
        }
        
        // Verificar se paciente e anestesista são da mesma instituição do usuário
        $sql = "
            SELECT 
                p.instituicao_id as paciente_instituicao,
                u.instituicao_id as anestesista_instituicao,
                uu.instituicao_id as usuario_instituicao
            FROM pacientes p
            JOIN usuarios u ON u.id = ?
            JOIN usuarios uu ON uu.id = ?
            WHERE p.id = ?
        ";
        
        $result = $this->db->fetch($sql, [$anestesista_id, $user_id, $paciente_id]);
        
        if (!$result) {
            return false;
        }
        
        // Verificar se todos são da mesma instituição
        return ($result['paciente_instituicao'] === $result['anestesista_instituicao'] &&
                $result['anestesista_instituicao'] === $result['usuario_instituicao']);
    }
    
    /**
     * Obter total de pacientes da instituição
     */
    private function getTotalPacientes($instituicao_id) {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM pacientes WHERE instituicao_id = ?", 
            [$instituicao_id]
        );
        return $result['total'] ?? 0;
    }
    
    /**
     * Obter total de pacientes alocados
     */
    private function getTotalPacientesAlocados($instituicao_id) {
        $result = $this->db->fetch(
            "SELECT COUNT(DISTINCT pa.paciente_id) as total 
             FROM paciente_anestesistas pa 
             JOIN pacientes p ON pa.paciente_id = p.id 
             WHERE p.instituicao_id = ? AND pa.status = 'ativo'", 
            [$instituicao_id]
        );
        return $result['total'] ?? 0;
    }
    
    /**
     * Obter nutricionistas com seus pacientes
     */
    private function getAnestesistasComPacientes($instituicao_id) {
        $sql = "
            SELECT 
                u.id,
                u.nome,
                u.crm,
                u.foto_path,
                COUNT(pa.paciente_id) as total_pacientes
            FROM usuarios u
            LEFT JOIN paciente_anestesistas pa ON pa.anestesista_id = u.id AND pa.status = 'ativo'
            WHERE u.instituicao_id = ? 
            AND u.perfil_id = (SELECT id FROM perfis WHERE nome = 'anestesista')
            AND u.status = 'ativo'
            GROUP BY u.id, u.nome, u.crm, u.foto_path
            ORDER BY u.nome
        ";
        
        $anestesistas = $this->db->fetchAll($sql, [$instituicao_id]);
        
        // Para cada nutricionista, buscar seus pacientes
        foreach ($anestesistas as &$anestesista) {
            $pacientes_sql = "
                SELECT 
                    p.*,
                    pr.nome as procedimento_nome,
                    pa.data_atribuicao,
                    CASE 
                        WHEN a.id IS NOT NULL THEN 'Tem Agendamento'
                        ELSE 'Sem Agendamento'
                    END as status_agendamento
                FROM pacientes p
                JOIN paciente_anestesistas pa ON p.id = pa.paciente_id
                LEFT JOIN procedimentos pr ON p.procedimento_id = pr.id
                LEFT JOIN agendamentos a ON p.id = a.paciente_id AND a.status = 'agendado'
                WHERE pa.anestesista_id = ? 
                AND pa.status = 'ativo'
                AND p.instituicao_id = ?
                AND p.inativo = 0
                ORDER BY pa.data_atribuicao DESC
            ";
            
            $anestesista['pacientes'] = $this->db->fetchAll($pacientes_sql, [$anestesista['id'], $instituicao_id]);
        }
        
        return $anestesistas;
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
