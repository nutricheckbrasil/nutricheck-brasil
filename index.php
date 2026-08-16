<?php
// Verificar se é acesso direto ao cadastro_publico.php ou gerar-relatorio-pdf.php
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
$script_name = $_SERVER['SCRIPT_NAME'] ?? '';

// Verificar cadastro_publico.php
if (strpos($request_uri, '/cadastro_publico.php') !== false || 
    strpos($script_name, '/cadastro_publico.php') !== false ||
    basename($_SERVER['PHP_SELF'] ?? '') === 'cadastro_publico.php') {
    $file = __DIR__ . '/cadastro_publico.php';
    if (file_exists($file)) {
        require_once $file;
        exit;
    }
}

// Verificar gerar-relatorio-pdf.php
if (strpos($request_uri, '/gerar-relatorio-pdf.php') !== false || 
    strpos($script_name, '/gerar-relatorio-pdf.php') !== false ||
    basename($_SERVER['PHP_SELF'] ?? '') === 'gerar-relatorio-pdf.php') {
    $file = __DIR__ . '/gerar-relatorio-pdf.php';
    if (file_exists($file)) {
        require_once $file;
        exit;
    }
}

// Configurações de sessão (adicione isso antes de session_start())
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Mude para 1 em produção com HTTPS
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Lax');

session_start();

// Configurações básicas: suporta index na raiz (public_html) ou dentro de public/
$possibleBase = (is_dir(__DIR__ . '/app')) ? __DIR__ : dirname(__DIR__);
define('BASE_PATH', $possibleBase);
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', $possibleBase === __DIR__ ? __DIR__ : __DIR__);

// Produção: carregar config_production.php (define BASE_URL correta para o domínio)
$configProduction = APP_PATH . '/config/config_production.php';
if (file_exists($configProduction)) {
    require_once $configProduction;
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '/nutricheck/public');
}

// Carregar configurações
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/database.php';

// Autoloader simples
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/helpers/',
        APP_PATH . '/classes/',
        APP_PATH . '/config/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Configurações de erro
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar se é acesso direto ao cadastro_paciente.php
$request_uri = $_SERVER['REQUEST_URI'];
if (strpos($request_uri, '/cadastro_paciente.php') !== false) {
    require_once __DIR__ . '/cadastro_paciente.php';
    exit;
}

// Router simples
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = dirname($_SERVER['SCRIPT_NAME']);

// Novo: permite acessar via ?url=controller/acao
if (isset($_GET['url'])) {
    $path = trim($_GET['url'], '/');
} else {
    // Evitar que base_path '/' remova todas as barras (ex: /auth/logout vira authlogout)
    if ($base_path === '/' || $base_path === '') {
        $path = ltrim($request_uri, '/');
    } else {
        $path = str_replace($base_path, '', $request_uri);
    }
    $path = parse_url($path, PHP_URL_PATH);
    $path = trim($path, '/');
}

if (empty($path)) {
    // Sempre ir para a home (não redirecionar para dashboard)
    $path = 'home';
}

$segments = explode('/', $path);

// Redirecionar URLs antigas para as novas (NutriCheck)
if ($segments[0] === 'equipe-anestesistas' && ($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    $rest = isset($segments[1]) ? implode('/', array_slice($segments, 1)) : '';
    header('Location: ' . BASE_URL . '/equipe-nutricionistas' . ($rest ? '/' . $rest : ''));
    exit;
}
if ($segments[0] === 'anestesistas' && ($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    $rest = isset($segments[1]) ? implode('/', array_slice($segments, 1)) : '';
    header('Location: ' . BASE_URL . '/nutricionistas' . ($rest ? '/' . $rest : ''));
    exit;
}

// Rotas públicas (não precisam de autenticação)
$public_routes = ['p', 'auth', 'home', 'dashboard'];

// Verificar se é rota pública
$is_public_route = in_array($segments[0], $public_routes);

// Rotas especiais para cadastro público via QR
if ($segments[0] === 'p') {
    if (isset($segments[1]) && isset($segments[2])) {
        // Rota: /p/{slug}/{anestesista_id} - Cadastro via QR do anestesista
        $_GET['slug'] = $segments[1];
        $_GET['anestesista_id'] = $segments[2];
        $controller_name = 'CadastroPublicoController';
        $action = 'cadastroAnestesista';
    } elseif (isset($segments[1])) {
        // Rota: /p/{slug} - Cadastro via QR da instituição
        $_GET['slug'] = $segments[1];
        $controller_name = 'CadastroPublicoController';
        $action = 'cadastroInstituicao';
    } else {
        http_response_code(404);
        echo "Rota inválida";
        exit;
    }
} else {
    // Mapeamentos específicos para URLs com hífen
    $route_mappings = [
        'permissionamento-paginas' => 'PermissionamentoController',
        'equipe-nutricionistas' => 'EquipeNutricionistasController',
        'equipe-anestesistas' => 'EquipeNutricionistasController', // alias para NutriCheck
        'gestao-pacientes' => 'GestaoPacientesController',
        'cadastro-publico' => 'CadastroPublicoController',
        'classificacao-ia' => 'ClassificacaoIaController',
        'demonstracoes' => 'DemonstracoesController',
        'financeiro' => 'FinanceiroController',
        'nutricionistas' => 'NutricionistasController',
        'anestesistas' => 'NutricionistasController' // alias para NutriCheck
    ];
    
    // Verificar se há mapeamento específico
    if (isset($route_mappings[$segments[0]])) {
        $controller_name = $route_mappings[$segments[0]];
        
        // Mapeamentos específicos para ações (equipe-nutricionistas e equipe-anestesistas)
        $equipe_segment = ($segments[0] === 'equipe-nutricionistas' || $segments[0] === 'equipe-anestesistas');
        if ($equipe_segment && isset($segments[1])) {
            if ($segments[1] === 'desalocar') {
                $action = 'desalocarPaciente';
            } elseif ($segments[1] === 'alocar') {
                $action = 'alocarPaciente';
            } else {
                $action = str_replace('-', '_', $segments[1]);
            }
        } else {
            $action = isset($segments[1]) ? str_replace('-', '_', $segments[1]) : 'index';
        }
    } else {
        // Converter URL para camel case (ex: gestao-pacientes -> GestaoPacientes)
        $controller_name = str_replace('-', '', ucwords($segments[0], '-')) . 'Controller';
        $action = isset($segments[1]) ? str_replace('-', '_', $segments[1]) : 'index';
    }
}

// Middleware de autenticação para rotas protegidas
if (!$is_public_route && $segments[0] !== 'p') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . BASE_URL . "/auth/login");
        exit;
    }
}

// Verificar se o controller existe
$controller_file = APP_PATH . '/controllers/' . $controller_name . '.php';

if (file_exists($controller_file)) {
    require_once $controller_file;
    $controller = new $controller_name();
    
    if (method_exists($controller, $action)) {
        $params = array_slice($segments, 2); // Pega tudo depois de controller e action
        call_user_func_array([$controller, $action], $params);
    } else {
        http_response_code(404);
        echo "Ação não encontrada: $action";
    }
} else {
    http_response_code(404);
    echo "Controller não encontrado: $controller_name";
}
