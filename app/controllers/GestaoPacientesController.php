<?php

class GestaoPacientesController extends BaseController {
    
    protected function requiresAuth() {
        return true;
    }
    
    public function index() {
        // Verificar se é admin ou médico (perfil_id = 1 ou 2)
        if (!isset($_SESSION['perfil_id']) || !in_array($_SESSION['perfil_id'], [1, 2])) {
            $this->redirect('/dashboard');
            return;
        }
        
        $instituicao_id = $_SESSION['instituicao_id'];
        
        // Filtros
        $filtro = $_GET['filtro'] ?? '';
        $busca = $_GET['busca'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        // Construir query base para pacientes
        $where_conditions = ['p.instituicao_id = ?'];
        $params = [$instituicao_id];
        
        // Aplicar filtros baseados na classificação IA
        if ($filtro) {
            switch ($filtro) {
                case 'todos':
                    // Não adicionar filtro adicional
                    break;
                case 'classificados':
                    $where_conditions[] = 'p.classificacao_ia = "baixo_risco"';
                    break;
                case 'nao_classificados':
                    $where_conditions[] = 'p.classificacao_ia IS NULL';
                    break;
                case 'alto_risco':
                    $where_conditions[] = 'p.classificacao_ia = "alto_risco"';
                    break;
            }
        }
        
        if ($busca) {
            $where_conditions[] = '(p.nome LIKE ? OR p.sobrenome LIKE ? OR p.cpf LIKE ? OR p.email LIKE ?)';
            $busca_param = "%$busca%";
            $params[] = $busca_param;
            $params[] = $busca_param;
            $params[] = $busca_param;
            $params[] = $busca_param;
        }
        
        $where_clause = implode(' AND ', $where_conditions);
        
        // Buscar pacientes
        $sql = "SELECT p.*, u.nome as medico_nome, e.nome as anestesista_nome, pr.nome as procedimento_nome 
                FROM pacientes p 
                LEFT JOIN usuarios u ON p.medico_id = u.id 
                LEFT JOIN usuarios e ON p.anestesista_id = e.id 
                LEFT JOIN procedimentos pr ON p.procedimento_id = pr.id 
                WHERE $where_clause 
                ORDER BY p.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;
        
        $pacientes = $this->db->fetchAll($sql, $params);
        
        // Estatísticas baseadas na classificação IA
        $stats_sql = "SELECT 
                        COUNT(*) as total_pacientes,
                        COUNT(CASE WHEN classificacao_ia = 'baixo_risco' THEN 1 END) as classificados_ia,
                        COUNT(CASE WHEN classificacao_ia IS NULL THEN 1 END) as nao_classificados,
                        COUNT(CASE WHEN classificacao_ia = 'alto_risco' THEN 1 END) as alto_risco
                      FROM pacientes 
                      WHERE instituicao_id = ?";
        
        $stats = $this->db->fetch($stats_sql, [$instituicao_id]);
        
        // Se não há pacientes, criar estatísticas vazias
        if (!$stats) {
            $stats = [
                'total_pacientes' => 0,
                'classificados_ia' => 0,
                'nao_classificados' => 0,
                'alto_risco' => 0
            ];
        }
        
        // Contar total para paginação
        $count_sql = "SELECT COUNT(*) as total FROM pacientes p WHERE $where_clause";
        // Remover os parâmetros de LIMIT e OFFSET da contagem
        $count_params = array_slice($params, 0, -2);
        $total_result = $this->db->fetch($count_sql, $count_params);
        $total = $total_result['total'];
        $total_pages = ceil($total / $limit);
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/gestao-pacientes/index.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    public function view($id) {
        if (!isset($_SESSION['perfil_id']) || !in_array($_SESSION['perfil_id'], [1, 2])) {
            $this->redirect('/dashboard');
            return;
        }
        
        $anestesista_id = $id;
        
        // Buscar dados do anestesista
        $sql = "SELECT 
                    u.*,
                    i.nome as instituicao_nome,
                    COUNT(DISTINCT pe.paciente_id) as total_pacientes,
                    COUNT(CASE WHEN pe.status = 'ativo' THEN 1 END) as pacientes_ativos
                FROM usuarios u
                LEFT JOIN instituicoes i ON u.instituicao_id = i.id
                LEFT JOIN paciente_anestesistas pe ON u.id = pe.anestesista_id
                WHERE u.id = ? AND u.perfil_id = 3
                GROUP BY u.id";
        
        $anestesista = $this->db->fetch($sql, [$anestesista_id]);
        
        if (!$anestesista) {
            $this->redirect('/gestao-pacientes');
            return;
        }
        
        // Buscar pacientes atribuídos
        $pacientes_sql = "SELECT 
                            p.*,
                            pe.data_atribuicao,
                            pe.observacoes as observacoes_atribuicao,
                            pe.status as status_atribuicao
                          FROM pacientes p
                          INNER JOIN paciente_anestesistas pe ON p.id = pe.paciente_id
                          WHERE pe.anestesista_id = ?
                          ORDER BY pe.data_atribuicao DESC";
        
        $pacientes = $this->db->fetchAll($pacientes_sql, [$anestesista_id]);
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/gestao-pacientes/view.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    public function updateStatus($id) {
        if (!isset($_SESSION['perfil_id']) || !in_array($_SESSION['perfil_id'], [1, 2])) {
            $this->redirect('/dashboard');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/gestao-pacientes');
            return;
        }
        
        $instituicao_id = $_SESSION['instituicao_id'];
        $anestesista_id = $id;
        $novo_status = $_POST['status'] ?? '';
        
        if (!in_array($novo_status, ['ativo', 'ocupado', 'ausente'])) {
            $this->redirect('/gestao-pacientes');
            return;
        }
        
        // Validar se o anestesista pertence à instituição
        $anestesista = $this->db->fetch(
            "SELECT id FROM usuarios WHERE id = ? AND instituicao_id = ? AND perfil_id = 3",
            [$anestesista_id, $instituicao_id]
        );
        
        if (!$anestesista) {
            $_SESSION['flash_message'] = "Anestesista não encontrado.";
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('/gestao-pacientes');
        }
        
        // Atualizar status
        $this->db->query(
            "UPDATE usuarios SET status = ?, updated_at = NOW() WHERE id = ?",
            [$novo_status, $anestesista_id]
        );
        
        $_SESSION['flash_message'] = "Status do anestesista atualizado com sucesso!";
        $_SESSION['flash_type'] = 'success';
        $this->redirect('/gestao-pacientes');
    }
} 