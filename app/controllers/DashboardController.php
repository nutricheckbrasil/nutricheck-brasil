<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/RBAC.php';
require_once __DIR__ . '/../classes/InstitutionContext.php';

class DashboardController extends BaseController {
    private $rbac;
    private $context;
    
    public function __construct() {
        parent::__construct();
        $this->rbac = new RBAC();
        $this->context = new InstitutionContext();
    }
    
    /**
     * Dashboard principal - redireciona baseado no perfil
     */
    public function index() {
        $user_id = $this->getCurrentUserId();
        
        if (!$user_id) {
            $this->redirect('/auth/login');
            return;
        }
        
        // Verificar se é uma instituição (não tem perfil na tabela usuarios)
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            $this->instituicaoAdminDashboard();
            return;
        }
        
        $user_profile = $this->rbac->getUserProfile($user_id);
        
        if (!$user_profile) {
            $this->redirect('/auth/login');
            return;
        }
        
        // Redirecionar baseado no perfil
        switch ($user_profile['perfil']) {
            case 'admin':
                $this->adminDashboard();
                break;
            case 'medico':
                $this->medicoDashboard();
                break;
            case 'anestesista':
                $this->anestesistaDashboard();
                break;
            case 'paciente':
                $this->pacienteDashboard();
                break;
            default:
                $this->redirect('/dashboard');
                break;
        }
    }
    
    /**
     * Dashboard específico para Admin
     */
    public function adminDashboard() {
        $user_id = $this->getCurrentUserId();
        
        if (!$this->rbac->isAdmin($user_id)) {
            $this->redirect('/dashboard');
            return;
        }
        
        // Estatísticas gerais do sistema
        $stats = $this->getSystemStats();
        
        // Instituições
        $instituicoes = $this->getInstituicoesStats();
        
        // Últimas atividades
        $ultimas_atividades = $this->getUltimasAtividades();
        
        // Gráfico de pacientes por mês (últimos 6 meses)
        $pacientes_por_mes = $this->getPacientesPorMes();
        
        $data = [
            'title' => 'Dashboard Administrativo',
            'stats' => $stats,
            'instituicoes' => $instituicoes,
            'ultimas_atividades' => $ultimas_atividades,
            'pacientes_por_mes' => $pacientes_por_mes,
            'user_profile' => $this->rbac->getUserProfile($user_id)
        ];
        
        // Definir variáveis para a view
        $title = $data['title'];
        
        // Capturar o conteúdo da view
        ob_start();
        require_once APP_PATH . '/views/dashboard/admin.php';
        $content = ob_get_clean();
        
        // Incluir o layout principal
        require_once APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Dashboard para Instituição Admin
     */
    public function instituicaoAdminDashboard() {
        $user_id = $this->getCurrentUserId();
        
        // Para instituições, o user_id é o próprio ID da instituição
        $instituicao_id = $user_id;
        
        if (!$instituicao_id) {
            $this->redirect('/dashboard');
            return;
        }
        
        $stats = $this->getInstituicaoStats($instituicao_id);
        $agendamentos_semana = $this->getAgendamentosSemana($instituicao_id);
        
        $data = [
            'title' => 'Dashboard da Instituição',
            'stats' => $stats,
            'agendamentos_semana' => $agendamentos_semana,
            'instituicao_id' => $instituicao_id
        ];
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/dashboard/instituicao_admin.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Dashboard para Anestesista
     */
    public function anestesistaDashboard() {
        $user_id = $this->getCurrentUserId();
        
        $stats = $this->getAnestesistaStats($user_id);
        
        $data = [
            'title' => 'Meu Dashboard',
            'stats' => $stats,
            'anestesista_id' => $user_id
        ];
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/dashboard/anestesista.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Dashboard para Atendente
     */
    public function atendenteDashboard() {
        $user_id = $this->getCurrentUserId();
        $instituicao_id = $this->context->getCurrentInstitutionId($user_id);
        
        $stats = $this->getAtendenteStats($instituicao_id);
        
        $data = [
            'title' => 'Dashboard do Atendente',
            'stats' => $stats,
            'instituicao_id' => $instituicao_id
        ];
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/dashboard/atendente.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Dashboard para Paciente
     */
    public function pacienteDashboard() {
        $user_id = $this->getCurrentUserId();
        
        $stats = $this->getPacienteStats($user_id);
        
        $data = [
            'title' => 'Meu Perfil',
            'stats' => $stats,
            'paciente_id' => $user_id
        ];
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/dashboard/paciente.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Obter estatísticas gerais do sistema
     */
    private function getSystemStats() {
        $stats = [
            'total_instituicoes' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM instituicoes 
                WHERE ativo = TRUE 
                AND nome != 'ADMIN' 
                AND nome != 'admin' 
                AND nome != 'Sistema Administrativo'
                AND nome != 'Sistema Admin'
                AND id != 1
            ")['count'],
            'total_usuarios' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM usuarios u 
                JOIN instituicoes i ON u.instituicao_id = i.id
                WHERE u.status = 'ativo' 
                AND i.nome != 'ADMIN' 
                AND i.nome != 'admin' 
                AND i.nome != 'Sistema Administrativo'
                AND i.nome != 'Sistema Admin'
                AND i.id != 1
            ")['count'],
            'total_pacientes' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM pacientes p 
                JOIN instituicoes i ON p.instituicao_id = i.id
                WHERE i.nome != 'ADMIN' 
                AND i.nome != 'admin' 
                AND i.nome != 'Sistema Administrativo'
                AND i.nome != 'Sistema Admin'
                AND i.id != 1
            ")['count'],
            'total_anestesistas' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM usuarios u 
                JOIN perfis p ON u.perfil_id = p.id 
                JOIN instituicoes i ON u.instituicao_id = i.id
                WHERE p.nome = 'anestesista' 
                AND u.status = 'ativo'
                AND i.nome != 'ADMIN' 
                AND i.nome != 'admin' 
                AND i.nome != 'Sistema Administrativo'
                AND i.nome != 'Sistema Admin'
                AND i.id != 1
            ")['count'],
            'pacientes_hoje' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM pacientes p 
                JOIN instituicoes i ON p.instituicao_id = i.id
                WHERE DATE(p.created_at) = CURDATE()
                AND i.nome != 'ADMIN' 
                AND i.nome != 'admin' 
                AND i.nome != 'Sistema Administrativo'
                AND i.nome != 'Sistema Admin'
                AND i.id != 1
            ")['count'],
            'pacientes_autorizados' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM pacientes p 
                JOIN instituicoes i ON p.instituicao_id = i.id
                WHERE p.status = 'autorizado'
                AND i.nome != 'ADMIN' 
                AND i.nome != 'admin' 
                AND i.nome != 'Sistema Administrativo'
                AND i.nome != 'Sistema Admin'
                AND i.id != 1
            ")['count']
        ];
        
        return $stats;
    }
    
    /**
     * Obter estatísticas por instituição
     */
    private function getInstituicoesStats() {
        $sql = "
            SELECT 
                i.id,
                i.nome,
                i.cnpj,
                COALESCE(i.status, 'ativo') as ativo,
                COUNT(DISTINCT u.id) as total_usuarios,
                COUNT(DISTINCT p.id) as total_pacientes,
                COUNT(DISTINCT CASE WHEN u2.perfil_id = (SELECT id FROM perfis WHERE nome = 'anestesista') THEN u2.id END) as total_anestesistas,
                COUNT(DISTINCT CASE WHEN DATE(p.created_at) = CURDATE() THEN p.id END) as pacientes_hoje
            FROM instituicoes i
            LEFT JOIN usuarios u ON u.instituicao_id = i.id AND u.status = 'ativo'
            LEFT JOIN pacientes p ON p.instituicao_id = i.id
            LEFT JOIN usuarios u2 ON u2.instituicao_id = i.id AND u2.status = 'ativo'
            WHERE i.nome != 'ADMIN' 
            AND i.nome != 'admin' 
            AND i.nome != 'Sistema Administrativo'
            AND i.nome != 'Sistema Admin'
            AND i.id != 1
            GROUP BY i.id, i.nome, i.cnpj, i.status
            ORDER BY total_pacientes DESC
        ";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obter últimas atividades do sistema
     */
    private function getUltimasAtividades() {
        $sql = "
            SELECT 
                la.*,
                u.nome as usuario_nome,
                p.nome as paciente_nome
            FROM logs_ativade la
            LEFT JOIN usuarios u ON la.usuario_id = u.id
            LEFT JOIN pacientes p ON la.paciente_id = p.id
            LEFT JOIN instituicoes i ON (u.instituicao_id = i.id OR p.instituicao_id = i.id)
            WHERE (i.nome IS NULL OR (
                i.nome != 'ADMIN' 
                AND i.nome != 'admin' 
                AND i.nome != 'Sistema Administrativo'
                AND i.nome != 'Sistema Admin'
                AND i.id != 1
            ))
            ORDER BY la.created_at DESC
            LIMIT 10
        ";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obter pacientes por mês (últimos 6 meses)
     */
    private function getPacientesPorMes() {
        $sql = "
            SELECT 
                DATE_FORMAT(p.created_at, '%Y-%m') as mes,
                COUNT(*) as total
            FROM pacientes p
            JOIN instituicoes i ON p.instituicao_id = i.id
            WHERE p.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            AND i.nome != 'ADMIN' 
            AND i.nome != 'admin' 
            AND i.nome != 'Sistema Administrativo'
            AND i.nome != 'Sistema Admin'
            AND i.id != 1
            GROUP BY DATE_FORMAT(p.created_at, '%Y-%m')
            ORDER BY mes ASC
        ";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obter estatísticas da instituição
     */
    private function getInstituicaoStats($instituicao_id) {
        $hoje = date('Y-m-d');
        $inicio_semana = date('Y-m-d', strtotime('monday this week'));
        $fim_semana = date('Y-m-d', strtotime('sunday this week'));
        
        $stats = [
            'total_usuarios' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM usuarios 
                WHERE instituicao_id = ? AND status = 'ativo'
            ", [$instituicao_id])['count'],
            'total_pacientes' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM pacientes 
                WHERE instituicao_id = ? AND inativo = 0
            ", [$instituicao_id])['count'],
            'total_anestesistas' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM usuarios u 
                JOIN perfis p ON u.perfil_id = p.id 
                WHERE u.instituicao_id = ? AND p.nome = 'anestesista' AND u.status = 'ativo'
            ", [$instituicao_id])['count'],
            'pacientes_hoje' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM pacientes 
                WHERE instituicao_id = ? AND DATE(created_at) = CURDATE()
            ", [$instituicao_id])['count'],
            'agendamentos_hoje' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM agendamentos a
                LEFT JOIN usuarios u ON a.anestesista_id = u.id
                WHERE (a.instituicao_id = ? OR u.instituicao_id = ?) AND a.data_agendamento = ?
            ", [$instituicao_id, $instituicao_id, $hoje])['count'],
            'agendamentos_semana' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM agendamentos a
                LEFT JOIN usuarios u ON a.anestesista_id = u.id
                WHERE (a.instituicao_id = ? OR u.instituicao_id = ?) AND a.data_agendamento BETWEEN ? AND ?
            ", [$instituicao_id, $instituicao_id, $inicio_semana, $fim_semana])['count'],
            'agendamentos_concluidos' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM agendamentos a
                LEFT JOIN usuarios u ON a.anestesista_id = u.id
                WHERE (a.instituicao_id = ? OR u.instituicao_id = ?) AND a.status = 'concluido'
            ", [$instituicao_id, $instituicao_id])['count'],
            'agendamentos_cancelados' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM agendamentos a
                LEFT JOIN usuarios u ON a.anestesista_id = u.id
                WHERE (a.instituicao_id = ? OR u.instituicao_id = ?) AND a.status = 'cancelado'
            ", [$instituicao_id, $instituicao_id])['count'],
            'total_agendamentos' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM agendamentos a
                LEFT JOIN usuarios u ON a.anestesista_id = u.id
                WHERE (a.instituicao_id = ? OR u.instituicao_id = ?)
            ", [$instituicao_id, $instituicao_id])['count']
        ];
        
        return $stats;
    }
    
    /**
     * Obter agendamentos da semana atual
     */
    private function getAgendamentosSemana($instituicao_id) {
        $inicio_semana = date('Y-m-d', strtotime('monday this week'));
        $fim_semana = date('Y-m-d', strtotime('sunday this week'));
        
        return $this->db->fetchAll("
            SELECT 
                a.*,
                p.nome as paciente_nome,
                p.data_nascimento,
                p.sexo,
                pr.nome as procedimento_nome,
                u.nome as anestesista_nome
            FROM agendamentos a
            LEFT JOIN pacientes p ON a.paciente_id = p.id
            LEFT JOIN procedimentos pr ON a.procedimento_id = pr.id
            LEFT JOIN usuarios u ON a.anestesista_id = u.id
            WHERE a.instituicao_id = ? 
            AND a.data_agendamento BETWEEN ? AND ?
            ORDER BY a.data_agendamento ASC, a.hora_agendamento ASC
        ", [$instituicao_id, $inicio_semana, $fim_semana]);
    }
    
    /**
     * Obter estatísticas do anestesista
     */
    private function getAnestesistaStats($anestesista_id) {
        $stats = [
            'total_pacientes' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM paciente_anestesistas 
                WHERE anestesista_id = ? AND status = 'ativo'
            ", [$anestesista_id])['count'],
            'pacientes_hoje' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM paciente_anestesistas pa
                JOIN pacientes p ON pa.paciente_id = p.id
                WHERE pa.anestesista_id = ? AND pa.status = 'ativo' AND DATE(p.data_procedimento) = CURDATE()
            ", [$anestesista_id])['count'],
            'pacientes_autorizados' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM paciente_anestesistas pa
                JOIN pacientes p ON pa.paciente_id = p.id
                WHERE pa.anestesista_id = ? AND pa.status = 'ativo' AND p.status = 'autorizado'
            ", [$anestesista_id])['count']
        ];
        
        return $stats;
    }
    
    /**
     * Obter estatísticas do atendente
     */
    private function getAtendenteStats($instituicao_id) {
        $stats = [
            'total_pacientes' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM pacientes 
                WHERE instituicao_id = ?
            ", [$instituicao_id])['count'],
            'pacientes_hoje' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM pacientes 
                WHERE instituicao_id = ? AND DATE(created_at) = CURDATE()
            ", [$instituicao_id])['count'],
            'pacientes_sem_anestesista' => $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM pacientes p
                LEFT JOIN paciente_anestesistas pa ON p.id = pa.paciente_id AND pa.status = 'ativo'
                WHERE p.instituicao_id = ? AND pa.id IS NULL
            ", [$instituicao_id])['count']
        ];
        
        return $stats;
    }
    
    /**
     * Obter estatísticas do paciente
     */
    private function getPacienteStats($paciente_id) {
        $stats = [
            'anestesista_nome' => 'Não atribuído',
            'proximo_agendamento' => 'Nenhum',
            'status' => 'Ativo'
        ];
        
        // Buscar anestesista atribuído
        $anestesista = $this->db->fetch("
            SELECT u.nome 
            FROM paciente_anestesistas pa
            JOIN usuarios u ON u.id = pa.anestesista_id
            WHERE pa.paciente_id = ? AND pa.status = 'ativo'
            LIMIT 1
        ", [$paciente_id]);
        
        if ($anestesista) {
            $stats['anestesista_nome'] = $anestesista['nome'];
        }
        
        // Buscar próximo agendamento
        $agendamento = $this->db->fetch("
            SELECT data_agendamento 
            FROM agendamentos 
            WHERE paciente_id = ? AND data_agendamento >= CURDATE()
            ORDER BY data_agendamento ASC
            LIMIT 1
        ", [$paciente_id]);
        
        if ($agendamento) {
            $stats['proximo_agendamento'] = date('d/m/Y', strtotime($agendamento['data_agendamento']));
        }
        
        return $stats;
    }
    
    /**
     * Obter ID do usuário atual
     */
    private function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
}