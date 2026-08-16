<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../classes/RBAC.php';

class ClassificacaoIaController extends BaseController {
    private $rbac;
    
    public function __construct() {
        parent::__construct();
        $this->rbac = new RBAC();
    }
    
    /**
     * Página principal de classificação por IA
     */
    public function index() {
        $this->requiresAuth();
        
        // Verificar permissões
        $user_id = $_SESSION['user_id'] ?? null;
        $hasPermission = false;
        
        // Se for instituição
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            $hasPermission = $this->rbac->hasPermissionForInstitution('classificacao_ia');
        } else {
            // Se for usuário normal
            $hasPermission = $this->rbac->hasPermission($user_id, 'classificacao_ia');
        }
        
        if (!$hasPermission) {
            $_SESSION['flash_message'] = 'Acesso negado. Você não tem permissão para acessar esta página.';
            $this->redirect('/dashboard');
            return;
        }
        
        // Obter dados para a página
        $data = [
            'title' => 'Classificação por IA',
            'user_id' => $user_id,
            'user_type' => $_SESSION['user_type'] ?? 'usuario'
        ];
        
        // Obter estatísticas
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            $instituicao_id = $user_id;
            $data['instituicao_id'] = $instituicao_id;
            $data['stats'] = $this->getInstituicaoStats($instituicao_id);
            $data['pacientes'] = $this->getPacientesInstituicao($instituicao_id);
        } else {
            $data['stats'] = $this->getSystemStats();
            $data['pacientes'] = $this->getPacientesSistema();
        }
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/classificacao-ia/index.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * Processar classificação por IA
     */
    public function processar() {
        $this->requiresAuth();
        
        if (!$this->isPost()) {
            $this->jsonResponse(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        // Verificar permissões
        $user_id = $_SESSION['user_id'] ?? null;
        $hasPermission = false;
        
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao') {
            $hasPermission = $this->rbac->hasPermissionForInstitution('classificacao_ia');
        } else {
            $hasPermission = $this->rbac->hasPermission($user_id, 'classificacao_ia');
        }
        
        if (!$hasPermission) {
            $this->jsonResponse(['success' => false, 'message' => 'Acesso negado']);
            return;
        }
        
        $dados = $this->getPost('dados');
        
        if (empty($dados)) {
            $this->jsonResponse(['success' => false, 'message' => 'Dados não fornecidos']);
            return;
        }
        
        try {
            // Aqui seria implementada a lógica de IA
            // Por enquanto, retornamos uma resposta simulada
            $resultado = $this->simularClassificacaoIA($dados);
            
            $this->jsonResponse([
                'success' => true,
                'resultado' => $resultado,
                'message' => 'Classificação realizada com sucesso'
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Erro ao processar classificação: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Simular classificação por IA (placeholder)
     */
    private function simularClassificacaoIA($dados) {
        // Esta é uma simulação - em um sistema real, aqui seria integrada uma API de IA
        $classificacoes = [
            'baixo_risco' => 'Baixo Risco',
            'medio_risco' => 'Médio Risco', 
            'alto_risco' => 'Alto Risco'
        ];
        
        $risco = array_rand($classificacoes);
        
        return [
            'classificacao' => $risco,
            'descricao' => $classificacoes[$risco],
            'confianca' => rand(75, 95),
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Obter estatísticas da instituição
     */
    private function getInstituicaoStats($instituicao_id) {
        $sql = "
            SELECT 
                COUNT(DISTINCT p.id) as total_pacientes,
                COUNT(CASE WHEN p.classificacao_ia = 'baixo_risco' THEN 1 END) as classificados_ia,
                COUNT(CASE WHEN p.classificacao_ia IS NULL OR p.classificacao_ia = '' THEN 1 END) as nao_classificados,
                COUNT(CASE WHEN p.classificacao_ia = 'alto_risco' THEN 1 END) as alto_risco
            FROM pacientes p
            WHERE p.instituicao_id = ?
        ";
        
        return $this->db->fetch($sql, [$instituicao_id]);
    }
    
    /**
     * Obter estatísticas do sistema
     */
    private function getSystemStats() {
        $sql = "
            SELECT 
                COUNT(DISTINCT p.id) as total_pacientes,
                COUNT(CASE WHEN p.classificacao_ia = 'baixo_risco' THEN 1 END) as classificados_ia,
                COUNT(CASE WHEN p.classificacao_ia IS NULL OR p.classificacao_ia = '' THEN 1 END) as nao_classificados,
                COUNT(CASE WHEN p.classificacao_ia = 'alto_risco' THEN 1 END) as alto_risco
            FROM pacientes p
        ";
        
        return $this->db->fetch($sql);
    }
    
    /**
     * Obter pacientes da instituição
     */
    private function getPacientesInstituicao($instituicao_id) {
        $sql = "
            SELECT 
                p.*,
                u.nome as anestesista_nome,
                pr.nome as procedimento_nome
            FROM pacientes p
            LEFT JOIN usuarios u ON u.id = p.anestesista_id
            LEFT JOIN procedimentos pr ON pr.id = p.procedimento_id
            WHERE p.instituicao_id = ?
            ORDER BY p.nome
        ";
        
        return $this->db->fetchAll($sql, [$instituicao_id]);
    }
    
    /**
     * Obter pacientes do sistema
     */
    private function getPacientesSistema() {
        $sql = "
            SELECT 
                p.*,
                u.nome as anestesista_nome,
                pr.nome as procedimento_nome,
                i.nome as instituicao_nome
            FROM pacientes p
            LEFT JOIN usuarios u ON u.id = p.anestesista_id
            LEFT JOIN procedimentos pr ON pr.id = p.procedimento_id
            LEFT JOIN instituicoes i ON i.id = p.instituicao_id
            ORDER BY p.nome
        ";
        
        return $this->db->fetchAll($sql);
    }
}
