<?php

// Constantes de Caminhos (com verificação para evitar redefinição)
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2));
}
if (!defined('APP_PATH')) {
    define('APP_PATH', BASE_PATH . '/app');
}
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', BASE_PATH . '/public');
}
if (!defined('VIEWS_PATH')) {
    define('VIEWS_PATH', APP_PATH . '/views');
}
if (!defined('CONTROLLERS_PATH')) {
    define('CONTROLLERS_PATH', APP_PATH . '/controllers');
}
if (!defined('MODELS_PATH')) {
    define('MODELS_PATH', APP_PATH . '/models');
}
if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', APP_PATH . '/config');
}
if (!defined('CLASSES_PATH')) {
    define('CLASSES_PATH', APP_PATH . '/classes');
}

// Constantes de URLs (com verificação para evitar redefinição)
if (!defined('BASE_URL')) {
    define('BASE_URL', '/nutricheck/public');
}
if (!defined('APP_URL')) {
    define('APP_URL', '/nutricheck/public');
}
if (!defined('PROJECT_ROOT_URL')) {
    // Detectar o caminho base do projeto automaticamente
    // Primeiro tenta usar BASE_URL se estiver definido
    if (defined('BASE_URL') && !empty(BASE_URL)) {
        $baseUrl = rtrim(BASE_URL, '/');
        // Se BASE_URL contém '/public', remove para obter o root
        if (strpos($baseUrl, '/public') !== false) {
            $baseDir = str_replace('/public', '', $baseUrl);
        } else {
            $baseDir = dirname($baseUrl);
            // Corrige se dirname retornar '/' ou '.'
            if ($baseDir === '/' || $baseDir === '.') {
                $baseDir = '';
            }
        }
    } else {
        $baseDir = '';
    }
    
    // Fallback: detecta pelo caminho do arquivo se ainda estiver vazio
    if (empty($baseDir)) {
        $scriptPath = $_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? '';
        if (!empty($scriptPath) && strpos($scriptPath, '/public/') !== false) {
            $baseDir = substr($scriptPath, 0, strpos($scriptPath, '/public/'));
        } else {
            $baseDir = '';
        }
    }
    
    define('PROJECT_ROOT_URL', $baseDir);
}

// Constantes de Perfis de Usuário
define('PERFIL_INSTITUICAO', 1);
define('PERFIL_MEDICO', 2);
define('PERFIL_ANESTESISTA', 3);
define('PERFIL_PACIENTE', 4);

// Constantes de Status de Pacientes
define('STATUS_CADASTRADO', 'cadastrado');
define('STATUS_TERMO_ACEITO', 'termo_aceito');
define('STATUS_SELFIE_TIRADA', 'selfie_tirada');
define('STATUS_VIDEO_ASSISTIDO', 'video_assistido');
define('STATUS_QUESTIONARIO_RESPONDIDO', 'questionario_respondido');
define('STATUS_QUESTIONARIO_INCOMPLETO', 'questionario_incompleto');
define('STATUS_AUTORIZADO', 'autorizado');
define('STATUS_FINALIZADO', 'finalizado');
define('STATUS_AGUARDANDO', 'aguardando');
define('STATUS_EM_ANDAMENTO', 'em_andamento');
define('STATUS_CANCELADO', 'cancelado');

// Constantes de Status de Chamados
define('STATUS_CHAMADO_ABERTO', 'aberto');
define('STATUS_CHAMADO_EM_ANDAMENTO', 'em_andamento');
define('STATUS_CHAMADO_AGUARDANDO_RESPOSTA', 'aguardando_resposta');
define('STATUS_CHAMADO_RESOLVIDO', 'resolvido');
define('STATUS_CHAMADO_FECHADO', 'fechado');
define('STATUS_CHAMADO_EM_ANALISE', 'em_analise');

// Constantes de Urgência
define('URGENCIA_BAIXA', 'baixa');
define('URGENCIA_NORMAL', 'normal');
define('URGENCIA_ALTA', 'alta');
define('URGENCIA_URGENTE', 'urgente');
define('URGENCIA_MEDIA', 'media');

// Constantes de Categorias de Chamados
define('CATEGORIA_PROBLEMA_TECNICO', 'problema_tecnico');
define('CATEGORIA_DUVIDA_CLINICA', 'duvida_clinica');
define('CATEGORIA_DUVIDA_SISTEMA', 'duvida_sistema');
define('CATEGORIA_SUGESTAO', 'sugestao');
define('CATEGORIA_OUTROS', 'outros');

// Constantes de Status de Usuários
define('STATUS_USUARIO_ATIVO', 'ativo');
define('STATUS_USUARIO_INATIVO', 'inativo');

// Constantes de Status de Instituições
define('STATUS_INSTITUICAO_ATIVO', 'ativo');
define('STATUS_INSTITUICAO_INATIVO', 'inativo');

// Constantes de Tipos de Resposta
define('TIPO_RESPOSTA_USUARIO', 'usuario');
define('TIPO_RESPOSTA_SUPORTE', 'suporte');

// Constantes de Jornada do Paciente
define('JORNADA_PENDENTE', 'pendente');
define('JORNADA_EM_ANDAMENTO', 'em_andamento');
define('JORNADA_CONCLUIDO', 'concluido');
define('JORNADA_FALHOU', 'falhou');
define('JORNADA_NAO_INICIADA', 'nao_iniciada');

// Constantes de Autorização
define('AUTORIZACAO_AUTORIZADO', true);
define('AUTORIZACAO_NEGADO', false);
define('AUTORIZACAO_PENDENTE', 'pendente');
define('AUTORIZACAO_NAO_AUTORIZADO', 'nao_autorizado');

// Constantes de Configuração
define('ITEMS_PER_PAGE', 20);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT_MINUTES', 15);
define('TOKEN_EXPIRY_HOURS', 24);

// Constantes de Upload
define('UPLOAD_MAX_SIZE', 10 * 1024 * 1024); // 10MB
define('UPLOAD_ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Constantes de Tempo de Resposta (em horas)
define('TEMPO_RESPOSTA_URGENTE', 2);
define('TEMPO_RESPOSTA_ALTA', 4);
define('TEMPO_RESPOSTA_NORMAL', 24);
define('TEMPO_RESPOSTA_BAIXA', 48);

// Funções utilitárias para perfis
function isInstituicao($perfilId) {
    return $perfilId == PERFIL_INSTITUICAO;
}

function isMedico($perfilId) {
    return $perfilId == PERFIL_MEDICO;
}

function isAnestesista($perfilId) {
    return $perfilId == PERFIL_ANESTESISTA;
}

function isPaciente($perfilId) {
    return $perfilId == PERFIL_PACIENTE;
}

function isProfissional($perfilId) {
    return in_array($perfilId, [PERFIL_MEDICO, PERFIL_ANESTESISTA]);
}

function isAdmin($perfilId) {
    return in_array($perfilId, [PERFIL_INSTITUICAO, PERFIL_MEDICO]);
}

// Funções utilitárias para status
function isStatusFinalizado($status) {
    return in_array($status, [STATUS_AUTORIZADO, STATUS_FINALIZADO]);
}

function isStatusChamadoFinalizado($status) {
    return in_array($status, [STATUS_CHAMADO_RESOLVIDO, STATUS_CHAMADO_FECHADO]);
}

// Funções para obter nomes dos perfis
function getPerfilNome($perfilId) {
    $perfis = [
        PERFIL_INSTITUICAO => 'Instituição',
        PERFIL_MEDICO => 'Médico',
        PERFIL_ANESTESISTA => 'Nutricionista',
        PERFIL_PACIENTE => 'Paciente'
    ];
    return $perfis[$perfilId] ?? 'Desconhecido';
}

// Funções para obter tempo de resposta
function getTempoResposta($urgencia) {
    $tempos = [
        URGENCIA_URGENTE => TEMPO_RESPOSTA_URGENTE,
        URGENCIA_ALTA => TEMPO_RESPOSTA_ALTA,
        URGENCIA_NORMAL => TEMPO_RESPOSTA_NORMAL,
        URGENCIA_BAIXA => TEMPO_RESPOSTA_BAIXA
    ];
    return $tempos[$urgencia] ?? TEMPO_RESPOSTA_NORMAL;
}

// Mapeamento de perfis para nomes
$PERFIL_NAMES = [
    PERFIL_MEDICO => 'Médico',
    PERFIL_ANESTESISTA => 'Nutricionista',
    PERFIL_INSTITUICAO => 'Instituição'
]; 