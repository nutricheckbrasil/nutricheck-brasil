<?php
/**
 * Instalador Web - Sistema de Vídeo Interativo
 * 
 * Este arquivo automatiza a instalação e configuração da plataforma
 * através de uma interface web amigável.
 */

session_start();

// Verificar se já foi instalado
if (file_exists('.installed') && !isset($_GET['force'])) {
    die('
    <!DOCTYPE html>
    <html>
    <head><title>Já Instalado</title></head>
    <body style="font-family: Arial; text-align: center; padding: 50px;">
        <h2>🎉 Sistema já está instalado!</h2>
        <p><a href="index.html">Acessar Plataforma</a></p>
        <p><small><a href="?force=1">Forçar reinstalação</a></small></p>
    </body>
    </html>
    ');
}

// Função para verificar extensões PHP
function checkPHPExtensions() {
    $required = ['pdo', 'pdo_mysql', 'json', 'mbstring', 'curl'];
    $missing = [];
    
    foreach ($required as $ext) {
        if (!extension_loaded($ext)) {
            $missing[] = $ext;
        }
    }
    
    return $missing;
}

// Função para testar conexão com banco
function testDatabaseConnection($host, $dbname, $user, $pass) {
    try {
        $dsn = "mysql:host=$host;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Tentar criar o banco se não existir
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // Conectar ao banco específico
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return ['success' => true, 'pdo' => $pdo];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

// Função para criar tabelas
function createTables($pdo) {
    $sql = "
    -- Tabela de vídeos
    CREATE TABLE IF NOT EXISTS videos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        file_path VARCHAR(500) NOT NULL,
        duration INT DEFAULT 0,
        author VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- Tabela de perguntas
    CREATE TABLE IF NOT EXISTS questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        video_id INT NOT NULL,
        question_text TEXT NOT NULL,
        time_position INT NOT NULL,
        question_type ENUM('multiple_choice', 'true_false', 'text') DEFAULT 'multiple_choice',
        options JSON,
        correct_answer VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (video_id) REFERENCES videos(id) ON DELETE CASCADE
    );

    -- Tabela de sessões de visualização
    CREATE TABLE IF NOT EXISTS viewing_sessions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        video_id INT NOT NULL,
        session_token VARCHAR(100) UNIQUE,
        user_ip VARCHAR(45),
        user_agent TEXT,
        user_name VARCHAR(100),
        user_email VARCHAR(255),
        started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        completed_at TIMESTAMP NULL,
        completion_percentage DECIMAL(5,2) DEFAULT 0,
        total_time_watched INT DEFAULT 0,
        device_type VARCHAR(50),
        FOREIGN KEY (video_id) REFERENCES videos(id) ON DELETE CASCADE
    );

    -- Tabela de respostas
    CREATE TABLE IF NOT EXISTS responses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id INT NOT NULL,
        question_id INT NOT NULL,
        user_answer TEXT,
        is_correct BOOLEAN DEFAULT FALSE,
        response_time DECIMAL(10,3),
        answered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (session_id) REFERENCES viewing_sessions(id) ON DELETE CASCADE,
        FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
    );

    -- Tabela de logs de consentimento
    CREATE TABLE IF NOT EXISTS consent_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id INT NOT NULL,
        consent_given BOOLEAN DEFAULT FALSE,
        email_sent BOOLEAN DEFAULT FALSE,
        email_result JSON,
        ip_address VARCHAR(45),
        user_agent TEXT,
        consent_data JSON,
        logged_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (session_id) REFERENCES viewing_sessions(id) ON DELETE CASCADE
    );

    -- Tabela de notificações de email
    CREATE TABLE IF NOT EXISTS email_notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id INT,
        email_type VARCHAR(50) NOT NULL,
        recipient_email VARCHAR(255) NOT NULL,
        subject VARCHAR(255),
        status ENUM('sent', 'failed', 'pending') DEFAULT 'pending',
        error_message TEXT,
        email_data JSON,
        sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (session_id) REFERENCES viewing_sessions(id) ON DELETE SET NULL
    );
    ";
    
    try {
        $pdo->exec($sql);
        return ['success' => true];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

// Função para inserir dados de exemplo
function insertSampleData($pdo) {
    try {
        // Inserir vídeo de exemplo
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO videos (id, title, description, file_path, duration, author) 
            VALUES (1, 'Vídeo de Demonstração', 'Este é um vídeo de exemplo para testar a plataforma', 'uploads/demo_video.html', 180, 'Sistema Demo')
        ");
        $stmt->execute();
        
        // Inserir perguntas de exemplo
        $questions = [
            [1, 'Qual é a cor do céu?', 30, 'multiple_choice', '["Azul", "Verde", "Vermelho", "Amarelo"]', 'Azul'],
            [1, 'A Terra é redonda?', 90, 'true_false', '["Verdadeiro", "Falso"]', 'Verdadeiro'],
            [1, 'Como você avalia este conteúdo?', 150, 'multiple_choice', '["Excelente", "Bom", "Regular", "Ruim"]', 'Excelente']
        ];
        
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO questions (video_id, question_text, time_position, question_type, options, correct_answer) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        foreach ($questions as $q) {
            $stmt->execute($q);
        }
        
        return ['success' => true];
    } catch (PDOException $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

// Função para criar arquivo .env
function createEnvFile($config) {
    $envContent = "# Configurações do Banco de Dados
DB_HOST={$config['db_host']}
DB_NAME={$config['db_name']}
DB_USER={$config['db_user']}
DB_PASS={$config['db_pass']}

# Configurações de Email
SMTP_HOST={$config['smtp_host']}
SMTP_PORT={$config['smtp_port']}
SMTP_USER={$config['smtp_user']}
SMTP_PASS={$config['smtp_pass']}

# Configurações de Remetente
FROM_EMAIL={$config['from_email']}
FROM_NAME={$config['from_name']}

# Destinatários de Notificação
CONSENT_EMAIL={$config['consent_email']}
ADMIN_EMAIL={$config['admin_email']}

# Configurações Gerais
SITE_URL={$config['site_url']}
DEBUG_MODE=false
";
    
    return file_put_contents('.env', $envContent) !== false;
}

// Processar instalação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['step'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['step']) {
        case 'check_requirements':
            $missing = checkPHPExtensions();
            $writable = is_writable('.') && (is_dir('uploads') ? is_writable('uploads') : true);
            
            echo json_encode([
                'success' => empty($missing) && $writable,
                'missing_extensions' => $missing,
                'writable' => $writable,
                'php_version' => PHP_VERSION
            ]);
            break;
            
        case 'test_database':
            $result = testDatabaseConnection(
                $_POST['db_host'],
                $_POST['db_name'],
                $_POST['db_user'],
                $_POST['db_pass']
            );
            echo json_encode($result);
            break;
            
        case 'install':
            try {
                // Testar conexão
                $dbResult = testDatabaseConnection(
                    $_POST['db_host'],
                    $_POST['db_name'],
                    $_POST['db_user'],
                    $_POST['db_pass']
                );
                
                if (!$dbResult['success']) {
                    throw new Exception('Erro na conexão: ' . $dbResult['error']);
                }
                
                // Criar tabelas
                $tableResult = createTables($dbResult['pdo']);
                if (!$tableResult['success']) {
                    throw new Exception('Erro ao criar tabelas: ' . $tableResult['error']);
                }
                
                // Inserir dados de exemplo
                $sampleResult = insertSampleData($dbResult['pdo']);
                if (!$sampleResult['success']) {
                    throw new Exception('Erro ao inserir dados: ' . $sampleResult['error']);
                }
                
                // Criar arquivo .env
                $envResult = createEnvFile($_POST);
                if (!$envResult) {
                    throw new Exception('Erro ao criar arquivo .env');
                }
                
                // Criar diretório de uploads
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0755, true);
                }
                
                // Criar arquivo de vídeo demo
                if (!file_exists('uploads/demo_video.html')) {
                    $demoVideo = '<!DOCTYPE html>
<html><head><title>Vídeo Demo</title></head>
<body style="background: #000; color: #fff; text-align: center; padding: 50px;">
<h1>🎬 Vídeo de Demonstração</h1>
<p>Este é um vídeo simulado para testar a plataforma.</p>
<p>Duração: 3 minutos</p>
<p>Perguntas aparecem aos 30s, 1:30 e 2:30</p>
</body></html>';
                    file_put_contents('uploads/demo_video.html', $demoVideo);
                }
                
                // Marcar como instalado
                file_put_contents('.installed', date('Y-m-d H:i:s'));
                
                echo json_encode(['success' => true, 'message' => 'Instalação concluída com sucesso!']);
                
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador - Sistema de Vídeo Interativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .installer-container {
            max-width: 800px;
            margin: 50px auto;
        }
        .step-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .step-header {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .progress-step {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .progress-step .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #6c757d;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }
        .progress-step.active .step-number {
            background: #28a745;
        }
        .progress-step.completed .step-number {
            background: #007bff;
        }
        .alert-custom {
            border-radius: 10px;
            border: none;
        }
        .btn-custom {
            border-radius: 25px;
            padding: 10px 30px;
        }
        .form-control {
            border-radius: 10px;
        }
        .loading {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container installer-container">
        <div class="step-card">
            <div class="step-header text-center">
                <h1><i class="bi bi-gear-fill"></i> Instalador do Sistema</h1>
                <p class="mb-0">Configure sua plataforma de vídeo interativo em poucos passos</p>
            </div>
            
            <div class="card-body p-4">
                <!-- Progress Steps -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="progress-step" id="step1-progress">
                            <div class="step-number">1</div>
                            <span>Requisitos</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="progress-step" id="step2-progress">
                            <div class="step-number">2</div>
                            <span>Banco de Dados</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="progress-step" id="step3-progress">
                            <div class="step-number">3</div>
                            <span>Email</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="progress-step" id="step4-progress">
                            <div class="step-number">4</div>
                            <span>Finalizar</span>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Requirements Check -->
                <div id="step1" class="step-content">
                    <h4><i class="bi bi-check-circle"></i> Verificação de Requisitos</h4>
                    <p>Verificando se o servidor atende aos requisitos mínimos...</p>
                    
                    <div id="requirements-result"></div>
                    
                    <div class="text-center mt-4">
                        <button class="btn btn-primary btn-custom" onclick="checkRequirements()">
                            <i class="bi bi-arrow-clockwise"></i> Verificar Requisitos
                        </button>
                    </div>
                </div>

                <!-- Step 2: Database Configuration -->
                <div id="step2" class="step-content" style="display: none;">
                    <h4><i class="bi bi-database"></i> Configuração do Banco de Dados</h4>
                    <p>Configure a conexão com o banco de dados MySQL:</p>
                    
                    <form id="db-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Host do Banco</label>
                                    <input type="text" class="form-control" name="db_host" value="localhost" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nome do Banco</label>
                                    <input type="text" class="form-control" name="db_name" value="video_interativo" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Usuário</label>
                                    <input type="text" class="form-control" name="db_user" value="root" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Senha</label>
                                    <input type="password" class="form-control" name="db_pass">
                                </div>
                            </div>
                        </div>
                        
                        <div id="db-test-result"></div>
                        
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-outline-primary btn-custom me-2" onclick="testDatabase()">
                                <i class="bi bi-database-check"></i> Testar Conexão
                            </button>
                            <button type="button" class="btn btn-primary btn-custom" onclick="nextStep(3)" disabled id="db-next-btn">
                                <i class="bi bi-arrow-right"></i> Próximo
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 3: Email Configuration -->
                <div id="step3" class="step-content" style="display: none;">
                    <h4><i class="bi bi-envelope"></i> Configuração de Email</h4>
                    <p>Configure o sistema de notificações por email:</p>
                    
                    <form id="email-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Servidor SMTP</label>
                                    <input type="text" class="form-control" name="smtp_host" value="smtp.gmail.com" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Porta SMTP</label>
                                    <input type="number" class="form-control" name="smtp_port" value="587" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email de Envio</label>
                                    <input type="email" class="form-control" name="smtp_user" placeholder="seu@email.com" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Senha/Token</label>
                                    <input type="password" class="form-control" name="smtp_pass" placeholder="Senha de app" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nome do Remetente</label>
                                    <input type="text" class="form-control" name="from_name" value="Sistema de Vídeo Interativo" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email do Remetente</label>
                                    <input type="email" class="form-control" name="from_email" value="noreply@videointerativo.com" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email para Consentimentos</label>
                                    <input type="email" class="form-control" name="consent_email" placeholder="consentimento@empresa.com" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email do Administrador</label>
                                    <input type="email" class="form-control" name="admin_email" placeholder="admin@empresa.com" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">URL do Site</label>
                                    <input type="url" class="form-control" name="site_url" value="<?= 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info alert-custom">
                            <i class="bi bi-info-circle"></i>
                            <strong>Dica para Gmail:</strong> Use uma senha de app específica. 
                            <a href="https://myaccount.google.com/apppasswords" target="_blank">Gerar senha de app</a>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-outline-secondary btn-custom me-2" onclick="previousStep(2)">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </button>
                            <button type="button" class="btn btn-primary btn-custom" onclick="nextStep(4)">
                                <i class="bi bi-arrow-right"></i> Próximo
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 4: Installation -->
                <div id="step4" class="step-content" style="display: none;">
                    <h4><i class="bi bi-rocket"></i> Finalizar Instalação</h4>
                    <p>Tudo pronto! Clique no botão abaixo para instalar a plataforma:</p>
                    
                    <div class="alert alert-warning alert-custom">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Atenção:</strong> Este processo irá criar as tabelas no banco de dados e configurar o sistema.
                    </div>
                    
                    <div id="install-result"></div>
                    
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-outline-secondary btn-custom me-2" onclick="previousStep(3)">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </button>
                        <button type="button" class="btn btn-success btn-custom" onclick="runInstallation()" id="install-btn">
                            <i class="bi bi-download"></i> Instalar Agora
                        </button>
                    </div>
                </div>

                <!-- Loading Spinner -->
                <div class="text-center loading" id="loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-2">Processando...</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentStep = 1;
        
        function showLoading() {
            document.getElementById('loading').style.display = 'block';
            document.querySelectorAll('.step-content').forEach(el => el.style.display = 'none');
        }
        
        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
        }
        
        function showStep(step) {
            document.querySelectorAll('.step-content').forEach(el => el.style.display = 'none');
            document.getElementById('step' + step).style.display = 'block';
            
            // Update progress
            for (let i = 1; i <= 4; i++) {
                const progressEl = document.getElementById('step' + i + '-progress');
                if (i < step) {
                    progressEl.classList.add('completed');
                    progressEl.classList.remove('active');
                } else if (i === step) {
                    progressEl.classList.add('active');
                    progressEl.classList.remove('completed');
                } else {
                    progressEl.classList.remove('active', 'completed');
                }
            }
            
            currentStep = step;
        }
        
        function nextStep(step) {
            showStep(step);
        }
        
        function previousStep(step) {
            showStep(step);
        }
        
        async function checkRequirements() {
            showLoading();
            
            try {
                const response = await fetch('install.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'step=check_requirements'
                });
                
                const result = await response.json();
                hideLoading();
                showStep(1);
                
                let html = '<div class="alert alert-' + (result.success ? 'success' : 'danger') + ' alert-custom">';
                html += '<h6><i class="bi bi-' + (result.success ? 'check' : 'x') + '-circle"></i> Resultado da Verificação</h6>';
                
                html += '<p><strong>PHP:</strong> ' + result.php_version + '</p>';
                
                if (result.missing_extensions.length > 0) {
                    html += '<p><strong>Extensões em falta:</strong> ' + result.missing_extensions.join(', ') + '</p>';
                }
                
                html += '<p><strong>Permissões de escrita:</strong> ' + (result.writable ? 'OK' : 'Erro') + '</p>';
                
                if (result.success) {
                    html += '<div class="text-center mt-3">';
                    html += '<button class="btn btn-primary btn-custom" onclick="nextStep(2)">Continuar <i class="bi bi-arrow-right"></i></button>';
                    html += '</div>';
                }
                
                html += '</div>';
                
                document.getElementById('requirements-result').innerHTML = html;
                
            } catch (error) {
                hideLoading();
                showStep(1);
                document.getElementById('requirements-result').innerHTML = 
                    '<div class="alert alert-danger alert-custom">Erro: ' + error.message + '</div>';
            }
        }
        
        async function testDatabase() {
            const formData = new FormData(document.getElementById('db-form'));
            formData.append('step', 'test_database');
            
            showLoading();
            
            try {
                const response = await fetch('install.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                hideLoading();
                showStep(2);
                
                let html = '<div class="alert alert-' + (result.success ? 'success' : 'danger') + ' alert-custom">';
                html += '<i class="bi bi-' + (result.success ? 'check' : 'x') + '-circle"></i> ';
                html += result.success ? 'Conexão bem-sucedida!' : 'Erro: ' + result.error;
                html += '</div>';
                
                document.getElementById('db-test-result').innerHTML = html;
                document.getElementById('db-next-btn').disabled = !result.success;
                
            } catch (error) {
                hideLoading();
                showStep(2);
                document.getElementById('db-test-result').innerHTML = 
                    '<div class="alert alert-danger alert-custom">Erro: ' + error.message + '</div>';
            }
        }
        
        async function runInstallation() {
            const dbFormData = new FormData(document.getElementById('db-form'));
            const emailFormData = new FormData(document.getElementById('email-form'));
            
            const formData = new FormData();
            formData.append('step', 'install');
            
            // Adicionar dados do banco
            for (let [key, value] of dbFormData.entries()) {
                formData.append(key, value);
            }
            
            // Adicionar dados do email
            for (let [key, value] of emailFormData.entries()) {
                formData.append(key, value);
            }
            
            showLoading();
            
            try {
                const response = await fetch('install.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                hideLoading();
                showStep(4);
                
                let html = '<div class="alert alert-' + (result.success ? 'success' : 'danger') + ' alert-custom">';
                html += '<h6><i class="bi bi-' + (result.success ? 'check' : 'x') + '-circle"></i> ';
                html += result.success ? 'Instalação Concluída!' : 'Erro na Instalação';
                html += '</h6>';
                html += '<p>' + (result.message || result.error) + '</p>';
                
                if (result.success) {
                    html += '<div class="text-center mt-3">';
                    html += '<a href="index.html" class="btn btn-primary btn-custom me-2"><i class="bi bi-house"></i> Acessar Plataforma</a>';
                    html += '<a href="test_email.php" class="btn btn-outline-primary btn-custom"><i class="bi bi-envelope"></i> Testar Email</a>';
                    html += '</div>';
                }
                
                html += '</div>';
                
                document.getElementById('install-result').innerHTML = html;
                
                if (result.success) {
                    document.getElementById('install-btn').style.display = 'none';
                }
                
            } catch (error) {
                hideLoading();
                showStep(4);
                document.getElementById('install-result').innerHTML = 
                    '<div class="alert alert-danger alert-custom">Erro: ' + error.message + '</div>';
            }
        }
        
        // Iniciar verificação automática
        window.onload = function() {
            checkRequirements();
        };
    </script>
</body>
</html>

