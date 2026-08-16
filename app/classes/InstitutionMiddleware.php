<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/RBAC.php';
require_once __DIR__ . '/InstitutionContext.php';

class InstitutionMiddleware {
    private $db;
    private $rbac;
    private $context;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->rbac = new RBAC();
        $this->context = new InstitutionContext();
    }
    
    /**
     * Middleware principal para validar acesso por instituição
     */
    public function validateAccess($user_id, $target_entity_id = null, $entity_type = 'generic') {
        if (!$user_id) {
            throw new Exception('Usuário não autenticado');
        }
        
        $user_profile = $this->rbac->getUserProfile($user_id);
        if (!$user_profile) {
            throw new Exception('Perfil de usuário não encontrado');
        }
        
        // Admin tem acesso a todas as instituições (baseado no contexto)
        if ($user_profile['perfil'] === 'admin') {
            return $this->validateAdminContext($user_id);
        }
        
        // Outros usuários só têm acesso à sua própria instituição
        return $this->validateUserInstitution($user_id, $target_entity_id, $entity_type);
    }
    
    /**
     * Validar contexto de instituição para admin
     */
    private function validateAdminContext($user_id) {
        $current_institution_id = $this->context->getCurrentInstitutionId($user_id);
        
        if (!$current_institution_id) {
            throw new Exception('Contexto de instituição não definido para admin');
        }
        
        return $current_institution_id;
    }
    
    /**
     * Validar instituição do usuário
     */
    private function validateUserInstitution($user_id, $target_entity_id, $entity_type) {
        $user_institution_id = $this->getUserInstitutionId($user_id);
        
        if (!$user_institution_id) {
            throw new Exception('Usuário não pertence a nenhuma instituição');
        }
        
        // Se não há entidade específica, retornar instituição do usuário
        if (!$target_entity_id) {
            return $user_institution_id;
        }
        
        // Validar se a entidade pertence à mesma instituição
        $entity_institution_id = $this->getEntityInstitutionId($target_entity_id, $entity_type);
        
        if (!$entity_institution_id) {
            throw new Exception('Entidade não encontrada');
        }
        
        if ($entity_institution_id !== $user_institution_id) {
            throw new Exception('Acesso negado: entidade não pertence à sua instituição');
        }
        
        return $user_institution_id;
    }
    
    /**
     * Obter ID da instituição do usuário
     */
    private function getUserInstitutionId($user_id) {
        $sql = "SELECT instituicao_id FROM usuarios WHERE id = ? AND status = 'ativo'";
        $result = $this->db->fetch($sql, [$user_id]);
        return $result ? $result['instituicao_id'] : null;
    }
    
    /**
     * Obter ID da instituição de uma entidade
     */
    private function getEntityInstitutionId($entity_id, $entity_type) {
        $sql = "";
        
        switch ($entity_type) {
            case 'paciente':
                $sql = "SELECT instituicao_id FROM pacientes WHERE id = ?";
                break;
            case 'usuario':
                $sql = "SELECT instituicao_id FROM usuarios WHERE id = ?";
                break;
            case 'anestesista':
                $sql = "SELECT u.instituicao_id FROM usuarios u JOIN perfis p ON u.perfil_id = p.id WHERE u.id = ? AND p.nome = 'anestesista'";
                break;
            case 'qr_code':
                $sql = "SELECT instituicao_id FROM qr_codes WHERE id = ?";
                break;
            case 'procedimento':
                // Procedimentos são globais, não pertencem a instituições
                return null;
            default:
                throw new Exception('Tipo de entidade não suportado: ' . $entity_type);
        }
        
        $result = $this->db->fetch($sql, [$entity_id]);
        return $result ? $result['instituicao_id'] : null;
    }
    
    /**
     * Adicionar filtro de instituição a uma query SQL
     */
    public function addInstitutionFilter($user_id, $base_query, $institution_field = 'instituicao_id') {
        $user_profile = $this->rbac->getUserProfile($user_id);
        
        if (!$user_profile) {
            return $base_query . " AND 1=0"; // Retorna query vazia
        }
        
        // Admin pode ver dados baseado no contexto
        if ($user_profile['perfil'] === 'admin') {
            $current_institution_id = $this->context->getCurrentInstitutionId($user_id);
            if ($current_institution_id) {
                return $base_query . " AND {$institution_field} = {$current_institution_id}";
            }
        }
        
        // Outros usuários veem apenas dados de sua instituição
        $user_institution_id = $this->getUserInstitutionId($user_id);
        if ($user_institution_id) {
            return $base_query . " AND {$institution_field} = {$user_institution_id}";
        }
        
        // Se não conseguiu determinar a instituição, retorna query vazia
        return $base_query . " AND 1=0";
    }
    
    /**
     * Validar se usuário pode acessar uma rota específica
     */
    public function validateRouteAccess($user_id, $route_name) {
        if (!$this->rbac->hasPermission($user_id, $route_name)) {
            throw new Exception('Acesso negado: você não tem permissão para acessar esta página');
        }
        
        return true;
    }
    
    /**
     * Validar se usuário pode executar uma ação em uma entidade
     */
    public function validateEntityAction($user_id, $entity_id, $entity_type, $action = 'view') {
        $user_profile = $this->rbac->getUserProfile($user_id);
        
        if (!$user_profile) {
            throw new Exception('Perfil de usuário não encontrado');
        }
        
        // Admin pode fazer tudo na instituição do contexto
        if ($user_profile['perfil'] === 'admin') {
            $this->validateAccess($user_id, $entity_id, $entity_type);
            return true;
        }
        
        // Verificar permissões específicas por ação e perfil
        switch ($action) {
            case 'view':
                return $this->validateAccess($user_id, $entity_id, $entity_type);
                
            case 'edit':
            case 'delete':
                if (in_array($user_profile['perfil'], ['instituicao_admin', 'atendente'])) {
                    return $this->validateAccess($user_id, $entity_id, $entity_type);
                }
                throw new Exception('Acesso negado: você não tem permissão para editar/deletar esta entidade');
                
            case 'create':
                if (in_array($user_profile['perfil'], ['admin', 'instituicao_admin', 'atendente'])) {
                    return $this->validateAccess($user_id);
                }
                throw new Exception('Acesso negado: você não tem permissão para criar esta entidade');
                
            default:
                throw new Exception('Ação não reconhecida: ' . $action);
        }
    }
    
    /**
     * Obter dados filtrados por instituição
     */
    public function getFilteredData($user_id, $table, $conditions = [], $institution_field = 'instituicao_id') {
        $sql = "SELECT * FROM {$table} WHERE 1=1";
        $params = [];
        
        // Adicionar condições específicas
        foreach ($conditions as $field => $value) {
            $sql .= " AND {$field} = ?";
            $params[] = $value;
        }
        
        // Adicionar filtro de instituição
        $sql = $this->addInstitutionFilter($user_id, $sql, $institution_field);
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Log de tentativa de acesso
     */
    public function logAccessAttempt($user_id, $entity_id, $entity_type, $action, $success, $error_message = null) {
        $sql = "
            INSERT INTO logs_ativade (usuario_id, acao, detalhes, ip_address, user_agent, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ";
        
        $details = sprintf(
            'Tentativa de %s em %s ID %d: %s',
            $action,
            $entity_type,
            $entity_id,
            $success ? 'SUCESSO' : 'FALHA - ' . $error_message
        );
        
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        $this->db->query($sql, [$user_id, 'ENTITY_ACCESS', $details, $ip_address, $user_agent]);
    }
    
    /**
     * Validar acesso a múltiplas entidades
     */
    public function validateMultipleEntities($user_id, $entities) {
        $results = [];
        
        foreach ($entities as $entity) {
            try {
                $this->validateAccess($user_id, $entity['id'], $entity['type']);
                $results[] = [
                    'entity_id' => $entity['id'],
                    'entity_type' => $entity['type'],
                    'access' => true
                ];
            } catch (Exception $e) {
                $results[] = [
                    'entity_id' => $entity['id'],
                    'entity_type' => $entity['type'],
                    'access' => false,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $results;
    }
}
