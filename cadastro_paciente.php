<?php
/**
 * PÁGINA DE CADASTRO PÚBLICO VIA QR CODE
 * FUNCIONA EM QUALQUER SERVIDOR
 * NÃO DEPENDE DE ROUTER OU CONFIGURAÇÕES
 */

// Verificar se tem token
$token = $_GET['token'] ?? null;
if (!$token) {
    die('Token não informado');
}

// Configurações básicas
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

// Incluir apenas o necessário
require_once APP_PATH . '/config/constants.php';
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/database.php';

// Verificar token no banco
try {
    $db = Database::getInstance();
    $sql = "SELECT qc.*, i.nome as instituicao_nome, i.endereco as instituicao_endereco, 
                   i.telefone as instituicao_telefone, u.nome as anestesista_nome, u.crm as anestesista_crm
            FROM qr_codes qc
            JOIN instituicoes i ON qc.instituicao_id = i.id
            LEFT JOIN usuarios u ON qc.anestesista_id = u.id
            WHERE qc.codigo = ? AND qc.ativo = TRUE AND i.status = 'ativo'";
    
    $token_info = $db->fetch($sql, [$token]);
    
    if (!$token_info) {
        die('Token inválido ou inativo');
    }
    
    // Se é POST, processar cadastro
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $db->getConnection()->beginTransaction();
            
            // Validar dados
            $nome = trim($_POST['nome'] ?? '');
            $sobrenome = trim($_POST['sobrenome'] ?? '');
            $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? '');
            $data_nascimento = $_POST['data_nascimento'] ?? '';
            $sexo = $_POST['sexo'] ?? '';
            $telefone = preg_replace('/[^0-9]/', '', $_POST['telefone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $confirmar_email = trim($_POST['confirmar_email'] ?? '');
            $procedimento_id = $_POST['procedimento_id'] ?? null;
            $data_procedimento = $_POST['data_procedimento'] ?? null;
            
            // Se não foi informada a data do procedimento, definir data estimada (15 dias à frente)
            if (empty($data_procedimento)) {
                $data_procedimento = date('Y-m-d', strtotime('+15 days'));
            }
            
            // Validações básicas
            if (empty($nome) || empty($cpf) || empty($data_nascimento) || empty($sexo) || empty($telefone) || empty($email) || empty($confirmar_email) || empty($procedimento_id)) {
                throw new Exception('Campos obrigatórios não preenchidos');
            }
            
            // Validar emails
            if ($email !== $confirmar_email) {
                throw new Exception('Os emails não coincidem');
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email inválido');
            }
            
            if (strlen($cpf) !== 11) {
                throw new Exception('CPF deve ter 11 dígitos');
            }
            
            // Inserir paciente
            $anestesista_id = null;
            if ($token_info['tipo'] === 'anestesista') {
                $anestesista_id = $token_info['anestesista_id'];
            }
            
            $token_acesso = bin2hex(random_bytes(32));
            $link_acesso = '/paciente_video.php?token=' . $token_acesso;
            
            $sql = "INSERT INTO pacientes (
                instituicao_id, anestesista_id, nome, sobrenome, cpf, data_nascimento, sexo, 
                telefone, email, procedimento_id, data_procedimento, criado_via_qr, qr_code_id,
                link_acesso, token_acesso, status, necessita_orientacao_pre_anestesica, 
                paciente_alto_risco, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE, ?, ?, ?, 'cadastrado', 0, 0, NOW())";
            
            $db->query($sql, [
                $token_info['instituicao_id'],
                $anestesista_id,
                $nome,
                $sobrenome,
                $cpf,
                $data_nascimento,
                $sexo,
                $telefone,
                $email,
                $procedimento_id,
                $data_procedimento,
                $token_info['id'],
                $link_acesso,
                $token_acesso
            ]);
            
            $paciente_id = $db->lastInsertId();
            
            // Atribuir anestesista se necessário
            if ($anestesista_id) {
                $sql = "INSERT INTO paciente_anestesistas (paciente_id, anestesista_id, data_atribuicao, status) 
                        VALUES (?, ?, NOW(), 'ativo')";
                $db->query($sql, [$paciente_id, $anestesista_id]);
            }
            
            // Criar agendamento se data informada (apenas se a tabela existir)
            if ($data_procedimento) {
                $table_exists = $db->fetch("SHOW TABLES LIKE 'his_appointments'");
                if ($table_exists) {
                    $sql = "INSERT INTO his_appointments (paciente_id, anestesista_id, data_procedimento, status, created_at) 
                            VALUES (?, ?, ?, 'agendado', NOW())";
                    $db->query($sql, [$paciente_id, $anestesista_id, $data_procedimento]);
                }
            }
            
            // Iniciar jornada
            $sql = "INSERT INTO jornada_paciente (paciente_id, status, created_at) 
                    VALUES (?, 'nao_iniciada', NOW())";
            $db->query($sql, [$paciente_id]);
            
            $db->getConnection()->commit();
            
            // Enviar email de boas-vindas com link de acesso
            try {
                require_once APP_PATH . '/classes/EmailSender.php';
                
                $emailSender = new EmailSender();
                
                // Construir URL completa do link de acesso
                $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $link_acesso_completo = $protocol . "://" . $host . $link_acesso;
                
                // Preparar dados para o template
                $user_data = [
                    'nome' => $nome,
                    'email' => $email,
                    'telefone' => $telefone,
                    'link_acesso' => $link_acesso_completo
                ];
                
                // Gerar corpo do email
                $email_body = $emailSender->generateWelcomeEmailTemplate($user_data, 'paciente');
                
                // Assunto do email
                $subject = "Bem-vindo ao Anestesiocheck - " . $token_info['instituicao_nome'];
                
                // Enviar email
                $emailSender->sendEmail($email, $subject, $email_body);
                
            } catch (Exception $e) {
                // Log do erro mas não interrompe o fluxo
                error_log("Erro ao enviar email de boas-vindas: " . $e->getMessage());
            }
            
            // Mostrar sucesso
            $mensagem = $token_info['tipo'] === 'anestesista'
                ? "✅ Cadastro realizado com sucesso! Você foi atribuído ao Dr(a). {$token_info['anestesista_nome']}. Sua equipe de anestesia será notificada e entrará em contato ."
                : "✅ Cadastro realizado com sucesso! Sua equipe de anestesia será notificada e entrará em contato com você em breve.";
            
            echo "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Cadastro Realizado</title>
                <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
                <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>
                <style>
                    body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
                    .card { border: none; border-radius: 20px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); }
                    .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 20px 20px 0 0 !important; padding: 25px; }
                    .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 15px; padding: 15px 30px; font-weight: 600; }
                    .btn-primary:hover { opacity: 0.9; transform: translateY(-2px); transition: all 0.3s; }
                    .alert { border-radius: 15px; }
                </style>
            </head>
            <body>
                <div class='container py-5'>
                    <div class='row justify-content-center'>
                        <div class='col-md-6'>
                            <div class='card'>
                                <div class='card-header text-center'>
                                    <h3><i class='fas fa-check-circle'></i> Cadastro Realizado!</h3>
                                </div>
                                <div class='card-body text-center'>
                                    <p class='lead'>$mensagem</p>
                                    <div class='alert alert-info'>
                                        <h6>Próximos Passos:</h6>
                                        <ul class='text-start'>
                                            <li><strong>Email:</strong> Você receberá um e-mail com o link para realizar sua avaliação pré-anestésica online</li>
                                        </ul>
                                    </div>
                                    
                                    <div class='alert alert-warning'>
                                        <i class='fas fa-exclamation-triangle'></i>
                                        <strong>Importante:</strong> Verifique sua caixa de <strong>SPAM/Lixo Eletrônico</strong> caso não receba o e-mail na caixa de entrada.
                                    </div>
                                    
                                    <button class='btn btn-primary btn-lg' onclick='window.close()'>
                                        <i class='fas fa-check-circle'></i> Entendi, Vou Olhar Meu Email
                                    </button>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>";
            exit;
            
        } catch (Exception $e) {
            $db->getConnection()->rollback();
            die('Erro ao processar cadastro: ' . $e->getMessage());
        }
    }
    
    // Buscar procedimentos disponíveis
    $sql_procedimentos = "SELECT id, nome FROM procedimentos ORDER BY nome";
    $procedimentos = $db->fetchAll($sql_procedimentos);
    
    // Mostrar formulário
    $tipo_cadastro = $token_info['tipo'];
    $instituicao_nome = $token_info['instituicao_nome'];
    $instituicao_endereco = $token_info['instituicao_endereco'];
    $instituicao_telefone = $token_info['instituicao_telefone'];
    
    $anestesista_info = '';
    if ($tipo_cadastro === 'anestesista') {
        $anestesista_nome = $token_info['anestesista_nome'];
        $anestesista_crm = $token_info['anestesista_crm'];
        $anestesista_info = "<div class='anestesista-info'>
            <h6><i class='fas fa-user-md'></i> Anestesista Responsável</h6>
            <p><strong>$anestesista_nome</strong><br><small>CRM: $anestesista_crm</small></p>
        </div>";
    }
    
    echo "<!DOCTYPE html>
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
        <title>Cadastro de Paciente - $instituicao_nome</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>
        <style>
            body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
            .container { padding: 10px; max-width: 100%; }
            .card { border: none; border-radius: 20px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); margin: 0; }
            .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 20px 15px; text-align: center; }
            .card-header h3 { font-size: 1.4rem; font-weight: 700; margin: 0; }
            .institution-info { background: rgba(102, 126, 234, 0.1); border-radius: 15px; padding: 15px; margin-bottom: 20px; border: 1px solid rgba(102, 126, 234, 0.2); }
            .anestesista-info { background: rgba(118, 75, 162, 0.1); border-radius: 15px; padding: 15px; margin-bottom: 20px; border-left: 4px solid #764ba2; }
            .form-control, .form-select { border-radius: 12px; border: 2px solid #e9ecef; padding: 14px 16px; font-size: 16px; min-height: 50px; }
            .form-control:focus, .form-select:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
            .form-control.is-valid { border-color: #198754; }
            .form-control.is-invalid { border-color: #dc3545; }
            .btn:disabled { opacity: 0.6; cursor: not-allowed; }
            .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 15px; padding: 16px 30px; font-weight: 600; width: 100%; min-height: 55px; }
            .section-title { font-size: 1.1rem; font-weight: 600; color: #495057; margin: 25px 0 15px 0; padding-bottom: 8px; border-bottom: 2px solid #e9ecef; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='card'>
                <div class='card-header'>
                    <h3><i class='fas fa-user-plus'></i> Cadastro de Paciente</h3>
                    <p>Preencha seus dados para iniciar o processo de anestesia</p>
                </div>
                <div class='card-body'>
                    <div class='institution-info'>
                        <h5><i class='fas fa-hospital'></i> $instituicao_nome</h5>
                        <p><i class='fas fa-map-marker-alt'></i> $instituicao_endereco</p>
                        <p><i class='fas fa-phone'></i> $instituicao_telefone</p>
                    </div>
                    $anestesista_info
                    <form method='POST'>
                        <div class='section-title'><i class='fas fa-user'></i> Dados Pessoais</div>
                        <div class='mb-3'>
                            <label class='form-label'>Nome *</label>
                            <input type='text' class='form-control' name='nome' required>
                        </div>
                        <div class='mb-3'>
                            <label class='form-label'>Sobrenome</label>
                            <input type='text' class='form-control' name='sobrenome'>
                        </div>
                        <div class='mb-3'>
                            <label class='form-label'>Data de Nascimento *</label>
                            <input type='date' class='form-control' name='data_nascimento' required>
                        </div>
                        <div class='mb-3'>
                            <label class='form-label'>Telefone *</label>
                            <input type='tel' class='form-control' name='telefone' required maxlength='15' placeholder='(99) 99999-9999'>
                        </div>
                        <div class='mb-3'>
                            <label class='form-label'>CPF *</label>
                            <input type='text' class='form-control' name='cpf' required maxlength='14' placeholder='000.000.000-00'>
                        </div>
                        <div class='mb-3'>
                            <label class='form-label'>Sexo *</label>
                            <select class='form-select' name='sexo' required>
                                <option value=''>Selecione...</option>
                                <option value='M'>Masculino</option>
                                <option value='F'>Feminino</option>
                                <option value='O'>Outro</option>
                            </select>
                        </div>
                        <div class='mb-3'>
                            <label class='form-label'>Procedimento *</label>
                            <select class='form-select' name='procedimento_id' required>
                                <option value=''>Selecione o procedimento</option>";
                                foreach ($procedimentos as $proc) {
                                    echo "<option value='{$proc['id']}'>{$proc['nome']}</option>";
                                }
                        echo "</select>
                        </div>
                        <div class='mb-3'>
                            <label class='form-label'>Data do Procedimento</label>
                            <input type='date' class='form-control' name='data_procedimento' min='" . date('Y-m-d') . "'>
                            <small class='form-text'>Opcional. Se não informado, será definida uma data estimada (15 dias à frente)</small>
                        </div>
                        <div class='section-title'><i class='fas fa-envelope'></i> Contato</div>
                        <div class='mb-3'>
                            <label class='form-label'>Email *</label>
                            <input type='email' class='form-control' name='email' required>
                        </div>
                        <div class='mb-3'>
                            <label class='form-label'>Confirmar Email *</label>
                            <input type='email' class='form-control' name='confirmar_email' required>
                        </div>
                        <button type='submit' class='btn btn-primary'><i class='fas fa-check-circle'></i> Realizar Cadastro</button>
                    </form>
                </div>
            </div>
        </div>
        <script>
            // Máscaras
            document.querySelector('input[name=\"cpf\"]').addEventListener('input', function() {
                let value = this.value.replace(/\\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/^(\\d{3})(\\d)/, '$1.$2');
                    value = value.replace(/^(\\d{3})\\.(\\d{3})(\\d)/, '$1.$2.$3');
                    value = value.replace(/\\.(\\d{3})(\\d)/, '.$1-$2');
                    this.value = value;
                }
            });
            
            document.querySelector('input[name=\"telefone\"]').addEventListener('input', function() {
                let value = this.value.replace(/\\D/g, '');
                if (value.length <= 11) {
                    if (value.length <= 10) {
                        value = value.replace(/^(\\d{2})(\\d)/, '($1) $2');
                        value = value.replace(/(\\d{4})(\\d)/, '$1-$2');
                    } else {
                        value = value.replace(/^(\\d{2})(\\d)/, '($1) $2');
                        value = value.replace(/(\\d{5})(\\d)/, '$1-$2');
                    }
                    this.value = value;
                }
            });
            
            // Validação de emails em tempo real
            const emailInput = document.querySelector('input[name=\"email\"]');
            const confirmEmailInput = document.querySelector('input[name=\"confirmar_email\"]');
            const submitButton = document.querySelector('button[type=\"submit\"]');
            const form = document.querySelector('form');
            
            function validateEmails() {
                const email = emailInput.value.trim();
                const confirmEmail = confirmEmailInput.value.trim();
                
                // Remover classes anteriores
                emailInput.classList.remove('is-valid', 'is-invalid');
                confirmEmailInput.classList.remove('is-valid', 'is-invalid');
                
                if (email && confirmEmail) {
                    if (email === confirmEmail) {
                        // Emails são iguais
                        emailInput.classList.add('is-valid');
                        confirmEmailInput.classList.add('is-valid');
                        submitButton.disabled = false;
                        submitButton.innerHTML = '<i class=\"fas fa-check-circle\"></i> Realizar Cadastro';
                    } else {
                        // Emails são diferentes
                        emailInput.classList.add('is-invalid');
                        confirmEmailInput.classList.add('is-invalid');
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class=\"fas fa-exclamation-triangle\"></i> Emails não coincidem';
                    }
                } else {
                    // Campos vazios
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class=\"fas fa-check-circle\"></i> Realizar Cadastro';
                }
            }
            
            // Adicionar listeners
            emailInput.addEventListener('input', validateEmails);
            confirmEmailInput.addEventListener('input', validateEmails);
            
            // Validação no submit
            form.addEventListener('submit', function(e) {
                const email = emailInput.value.trim();
                const confirmEmail = confirmEmailInput.value.trim();
                
                if (email !== confirmEmail) {
                    e.preventDefault();
                    alert('Os emails não coincidem! Por favor, verifique os campos de email.');
                    confirmEmailInput.focus();
                    return false;
                }
            });
            
            // Validação inicial
            validateEmails();
        </script>
    </body>
    </html>";
    
} catch (Exception $e) {
    die('Erro: ' . $e->getMessage());
}
?>