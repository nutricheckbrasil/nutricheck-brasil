<?php
/**
 * PÁGINA DE ACESSO DO PACIENTE - CONSENTIMENTO DE PROCEDIMENTO
 * URL: https://dev.anestesiocheck.com.br/paciente/acesso/{token}
 * 
 * Esta página permite que o paciente acesse sua jornada de consentimento
 * usando o token único enviado por email.
 */

// Configurações básicas
define('BASE_PATH', dirname(dirname(__DIR__)));
define('APP_PATH', BASE_PATH . '/app');

// Incluir configurações
require_once APP_PATH . '/config/constants.php';
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/config/database.php';

// Pegar token da URL
// Suporta: /paciente/acesso/{token} ou /paciente/acesso/index.php?token={token}
$token = null;

// Tentar pegar do query string primeiro
if (isset($_GET['token'])) {
    $token = $_GET['token'];
}

// Se não tem no query string, tentar pegar da URL
if (!$token && isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
    // Remover query string
    $uri = strtok($uri, '?');
    // Pegar partes da URL
    $parts = explode('/', trim($uri, '/'));
    // O token deve ser a última parte após 'acesso'
    $acesso_index = array_search('acesso', $parts);
    if ($acesso_index !== false && isset($parts[$acesso_index + 1])) {
        $token = $parts[$acesso_index + 1];
    }
}

if (!$token) {
    die('Token de acesso não informado. Por favor, use o link enviado por email.');
}

try {
    $db = Database::getInstance();
    
    // Buscar paciente pelo token
    $sql = "SELECT p.*, i.nome as instituicao_nome, i.endereco as instituicao_endereco,
                   i.telefone as instituicao_telefone, pr.nome as procedimento_nome,
                   u.nome as anestesista_nome, u.crm as anestesista_crm
            FROM pacientes p
            JOIN instituicoes i ON p.instituicao_id = i.id
            LEFT JOIN procedimentos pr ON p.procedimento_id = pr.id
            LEFT JOIN usuarios u ON p.anestesista_id = u.id
            WHERE p.token_acesso = ? AND i.status = 'ativo'";
    
    $paciente = $db->fetch($sql, [$token]);
    
    if (!$paciente) {
        die('Token inválido ou expirado. Por favor, entre em contato com a instituição.');
    }
    
    // Verificar se já aceitou o consentimento
    $sql_consentimento = "SELECT * FROM consentimentos WHERE paciente_id = ? ORDER BY created_at DESC LIMIT 1";
    $consentimento_existente = $db->fetch($sql_consentimento, [$paciente['id']]);
    
    // Processar formulário de consentimento
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $db->getConnection()->beginTransaction();
            
            // Validar aceite
            $aceite_termos = isset($_POST['aceite_termos']) ? 1 : 0;
            $aceite_procedimento = isset($_POST['aceite_procedimento']) ? 1 : 0;
            $aceite_anestesia = isset($_POST['aceite_anestesia']) ? 1 : 0;
            $aceite_riscos = isset($_POST['aceite_riscos']) ? 1 : 0;
            
            if (!$aceite_termos || !$aceite_procedimento || !$aceite_anestesia || !$aceite_riscos) {
                throw new Exception('Você precisa aceitar todos os termos para continuar');
            }
            
            // Dados adicionais
            $observacoes = trim($_POST['observacoes'] ?? '');
            
            // Inserir consentimento
            $sql = "INSERT INTO consentimentos (
                paciente_id, instituicao_id, procedimento_id, anestesista_id,
                aceite_termos, aceite_procedimento, aceite_anestesia, aceite_riscos,
                observacoes, ip_address, user_agent, data_aceite, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
            
            $db->query($sql, [
                $paciente['id'],
                $paciente['instituicao_id'],
                $paciente['procedimento_id'],
                $paciente['anestesista_id'],
                $aceite_termos,
                $aceite_procedimento,
                $aceite_anestesia,
                $aceite_riscos,
                $observacoes,
                $ip_address,
                $user_agent
            ]);
            
            // Atualizar status do paciente
            $sql_update = "UPDATE pacientes SET status = 'consentimento_aceito', updated_at = NOW() WHERE id = ?";
            $db->query($sql_update, [$paciente['id']]);
            
            // Atualizar jornada do paciente se a tabela existir
            $table_exists = $db->fetch("SHOW TABLES LIKE 'jornada_paciente'");
            if ($table_exists) {
                // Verificar quais colunas existem na tabela
                $columns = $db->query("SHOW COLUMNS FROM jornada_paciente");
                $has_completed_at = false;
                foreach ($columns as $col) {
                    if ($col['Field'] === 'completed_at') {
                        $has_completed_at = true;
                        break;
                    }
                }
                
                // Atualizar com ou sem completed_at dependendo da estrutura
                if ($has_completed_at) {
                    $sql_jornada = "UPDATE jornada_paciente SET status = 'concluida', completed_at = NOW() 
                                   WHERE paciente_id = ? AND etapa = 'autorizacao'";
                } else {
                    $sql_jornada = "UPDATE jornada_paciente SET status = 'concluida' 
                                   WHERE paciente_id = ? AND etapa = 'autorizacao'";
                }
                $db->query($sql_jornada, [$paciente['id']]);
            }
            
            $db->getConnection()->commit();
            
            // Redirecionar para página de sucesso
            header('Location: ?token=' . urlencode($token) . '&sucesso=1');
            exit;
            
        } catch (Exception $e) {
            $db->getConnection()->rollback();
            $erro = $e->getMessage();
        }
    }
    
} catch (Exception $e) {
    die('Erro ao processar solicitação: ' . htmlspecialchars($e->getMessage()));
}

// Se já aceitou e está acessando novamente
$ja_aceitou = !empty($consentimento_existente);
$mostrar_sucesso = isset($_GET['sucesso']) && $_GET['sucesso'] == '1';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentimento de Procedimento - NutriCheck</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 20px 10px;
        }
        .container {
            max-width: 800px;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 25px;
            border-radius: 20px 20px 0 0 !important;
        }
        .card-header h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }
        .info-box {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .info-box h5 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .termo-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border: 2px solid #e9ecef;
            max-height: 300px;
            overflow-y: auto;
        }
        .checkbox-custom {
            padding: 15px;
            background: white;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .checkbox-custom:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }
        .checkbox-custom input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }
        .checkbox-custom label {
            margin: 0;
            cursor: pointer;
            font-weight: 500;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .alert-success {
            border-radius: 15px;
            border: none;
        }
        .badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($mostrar_sucesso || $ja_aceitou): ?>
            <!-- Mensagem de Sucesso -->
            <div class="card">
                <div class="card-header text-center">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h3>Consentimento Registrado com Sucesso!</h3>
                </div>
                <div class="card-body text-center p-5">
                    <div class="alert alert-success">
                        <h4><i class="fas fa-check-circle"></i> Tudo Certo!</h4>
                        <p class="mb-0">Seu consentimento para o procedimento foi registrado com sucesso.</p>
                    </div>
                    
                    <div class="info-box text-start">
                        <h5><i class="fas fa-user"></i> Dados do Paciente</h5>
                        <p><strong>Nome:</strong> <?php echo htmlspecialchars($paciente['nome'] . ' ' . ($paciente['sobrenome'] ?? '')); ?></p>
                        <p><strong>CPF:</strong> <?php echo htmlspecialchars($paciente['cpf']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($paciente['email']); ?></p>
                    </div>
                    
                    <div class="info-box text-start">
                        <h5><i class="fas fa-hospital"></i> Procedimento</h5>
                        <p><strong>Procedimento:</strong> <?php echo htmlspecialchars($paciente['procedimento_nome'] ?? 'A definir'); ?></p>
                        <p><strong>Data Prevista:</strong> <?php echo $paciente['data_procedimento'] ? date('d/m/Y', strtotime($paciente['data_procedimento'])) : 'A definir'; ?></p>
                        <?php if ($paciente['anestesista_nome']): ?>
                            <p><strong>Nutricionista:</strong> Dr(a). <?php echo htmlspecialchars($paciente['anestesista_nome']); ?> - CRN: <?php echo htmlspecialchars($paciente['anestesista_crm']); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($consentimento_existente): ?>
                    <div class="info-box text-start">
                        <h5><i class="fas fa-calendar-check"></i> Consentimento</h5>
                        <p><strong>Data do Aceite:</strong> <?php echo date('d/m/Y H:i:s', strtotime($consentimento_existente['data_aceite'])); ?></p>
                        <p><strong>Status:</strong> <span class="badge bg-success">Consentimento Aceito</span></p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info mt-4">
                        <h6><strong>Próximos Passos:</strong></h6>
                        <ul class="text-start mb-0">
                            <li>Aguarde contato da equipe médica</li>
                            <li>Siga as orientações pré-operatórias que serão enviadas</li>
                            <li>Em caso de dúvidas, entre em contato com a instituição</li>
                        </ul>
                    </div>
                    
                    <p class="text-muted mt-4">
                        <small>Você pode fechar esta página. Um email de confirmação foi enviado para <?php echo htmlspecialchars($paciente['email']); ?></small>
                    </p>
                </div>
            </div>
        <?php else: ?>
            <!-- Formulário de Consentimento -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-file-signature"></i> Termo de Consentimento</h3>
                    <p class="mb-0">Leia atentamente e aceite os termos abaixo</p>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($erro)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($erro); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Informações do Paciente -->
                    <div class="info-box">
                        <h5><i class="fas fa-user"></i> Dados do Paciente</h5>
                        <p><strong>Nome:</strong> <?php echo htmlspecialchars($paciente['nome'] . ' ' . ($paciente['sobrenome'] ?? '')); ?></p>
                        <p><strong>CPF:</strong> <?php echo htmlspecialchars($paciente['cpf']); ?></p>
                        <p><strong>Data de Nascimento:</strong> <?php echo date('d/m/Y', strtotime($paciente['data_nascimento'])); ?></p>
                        <p class="mb-0"><strong>Email:</strong> <?php echo htmlspecialchars($paciente['email']); ?></p>
                    </div>
                    
                    <!-- Informações do Procedimento -->
                    <div class="info-box">
                        <h5><i class="fas fa-hospital"></i> Informações do Procedimento</h5>
                        <p><strong>Instituição:</strong> <?php echo htmlspecialchars($paciente['instituicao_nome']); ?></p>
                        <p><strong>Procedimento:</strong> <?php echo htmlspecialchars($paciente['procedimento_nome'] ?? 'A definir'); ?></p>
                        <p><strong>Data Prevista:</strong> <?php echo $paciente['data_procedimento'] ? date('d/m/Y', strtotime($paciente['data_procedimento'])) : 'A definir'; ?></p>
                        <?php if ($paciente['anestesista_nome']): ?>
                            <p class="mb-0"><strong>Nutricionista Responsável:</strong> Dr(a). <?php echo htmlspecialchars($paciente['anestesista_nome']); ?> - CRN: <?php echo htmlspecialchars($paciente['anestesista_crm']); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Termo de Consentimento -->
                    <h5 class="mt-4 mb-3"><i class="fas fa-file-contract"></i> Termo de Consentimento Livre e Esclarecido</h5>
                    
                    <div class="termo-box">
                        <h6>1. CONSENTIMENTO PARA PROCEDIMENTO ANESTÉSICO</h6>
                        <p>Declaro que fui devidamente informado(a) sobre o procedimento anestésico a que serei submetido(a), incluindo:</p>
                        <ul>
                            <li>A natureza e o objetivo do procedimento anestésico</li>
                            <li>Os riscos e benefícios envolvidos</li>
                            <li>As alternativas disponíveis</li>
                            <li>As possíveis complicações e efeitos colaterais</li>
                        </ul>
                        
                        <h6>2. INFORMAÇÕES PRESTADAS</h6>
                        <p>Declaro que:</p>
                        <ul>
                            <li>Tive a oportunidade de fazer perguntas e todas foram respondidas satisfatoriamente</li>
                            <li>Forneci informações verdadeiras sobre meu histórico médico</li>
                            <li>Informei sobre alergias, medicamentos em uso e condições de saúde relevantes</li>
                            <li>Compreendi as orientações pré e pós-operatórias</li>
                        </ul>
                        
                        <h6>3. RISCOS E COMPLICAÇÕES</h6>
                        <p>Estou ciente de que todo procedimento anestésico envolve riscos, incluindo mas não limitado a:</p>
                        <ul>
                            <li>Reações alérgicas aos medicamentos</li>
                            <li>Náuseas e vômitos</li>
                            <li>Alterações da pressão arterial e frequência cardíaca</li>
                            <li>Complicações respiratórias</li>
                            <li>Em casos raros, complicações graves que podem levar ao óbito</li>
                        </ul>
                        
                        <h6>4. CONSENTIMENTO</h6>
                        <p>Após ter sido informado(a) e ter compreendido todas as informações acima, consinto livremente em ser submetido(a) ao procedimento anestésico proposto.</p>
                        
                        <h6>5. PROTEÇÃO DE DADOS (LGPD)</h6>
                        <p>Autorizo o tratamento dos meus dados pessoais e de saúde para fins de realização do procedimento, conforme a Lei Geral de Proteção de Dados (LGPD - Lei nº 13.709/2018).</p>
                    </div>
                    
                    <!-- Formulário de Aceite -->
                    <form method="POST" id="formConsentimento">
                        <h5 class="mt-4 mb-3"><i class="fas fa-check-square"></i> Declarações de Aceite</h5>
                        
                        <div class="checkbox-custom">
                            <input type="checkbox" id="aceite_termos" name="aceite_termos" required>
                            <label for="aceite_termos">
                                <strong>Li e compreendi</strong> todas as informações contidas neste termo de consentimento
                            </label>
                        </div>
                        
                        <div class="checkbox-custom">
                            <input type="checkbox" id="aceite_procedimento" name="aceite_procedimento" required>
                            <label for="aceite_procedimento">
                                <strong>Autorizo</strong> a realização do procedimento anestésico descrito acima
                            </label>
                        </div>
                        
                        <div class="checkbox-custom">
                            <input type="checkbox" id="aceite_anestesia" name="aceite_anestesia" required>
                            <label for="aceite_anestesia">
                                <strong>Estou ciente</strong> dos riscos e orientações do preparo nutricional pré-operatório
                            </label>
                        </div>
                        
                        <div class="checkbox-custom">
                            <input type="checkbox" id="aceite_riscos" name="aceite_riscos" required>
                            <label for="aceite_riscos">
                                <strong>Concordo</strong> com o tratamento dos meus dados pessoais conforme a LGPD
                            </label>
                        </div>
                        
                        <div class="mb-3 mt-4">
                            <label for="observacoes" class="form-label"><strong>Observações Adicionais (Opcional)</strong></label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="3" placeholder="Caso tenha alguma observação, dúvida ou informação adicional, escreva aqui..."></textarea>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i> <strong>Importante:</strong> Ao clicar em "Confirmar Consentimento", você está assinando digitalmente este termo. Esta ação será registrada com data, hora e endereço IP.
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle"></i> Confirmar Consentimento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center text-white">
                <small>
                    <i class="fas fa-lock"></i> Conexão segura e protegida por criptografia<br>
                    Seus dados estão protegidos conforme a LGPD
                </small>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Validação do formulário
        document.getElementById('formConsentimento')?.addEventListener('submit', function(e) {
            const checkboxes = this.querySelectorAll('input[type="checkbox"][required]');
            let allChecked = true;
            
            checkboxes.forEach(checkbox => {
                if (!checkbox.checked) {
                    allChecked = false;
                }
            });
            
            if (!allChecked) {
                e.preventDefault();
                alert('Por favor, marque todas as declarações de aceite para continuar.');
                return false;
            }
            
            // Confirmação final
            if (!confirm('Você confirma que leu e compreendeu todos os termos e deseja prosseguir com o consentimento?')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>

