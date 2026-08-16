<?php

class PacientesController extends BaseController {
    
    public function index() {
        $this->requiresAuth();
        
        $instituicao_id = $_SESSION['instituicao_id'] ?? null;
        $perfil_id = $_SESSION['perfil_id'] ?? null;
        $usuario_id = $_SESSION['user_id'] ?? null;
        
        // Filtros
        $status = $this->getGet('status');
        $busca = $this->getGet('busca');
        $anestesistaSelecionado = $this->getGet('anestesista_id');
        $page = max(1, (int)$this->getGet('page', 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Construir query
        $where_conditions = ['p.instituicao_id = ?'];
        $params = [$instituicao_id];
        
        // Por padrão, mostrar todos os pacientes (ativos e inativos)
        if ($status) {
            if ($status === 'inativo') {
                $where_conditions[] = 'p.inativo = 1';
            } elseif ($status === 'ativo') {
                $where_conditions[] = 'p.inativo = 0';
            } else {
                $where_conditions[] = 'p.status = ? AND p.inativo = 0';
                $params[] = $status;
            }
        }
        // Se não há filtro de status, mostrar todos os pacientes

        // Filtrar por anestesista (padrão: anestesista logado)
        if (empty($anestesistaSelecionado) && $perfil_id == PERFIL_ANESTESISTA && $usuario_id) {
            $anestesistaSelecionado = $usuario_id;
            $_GET['anestesista_id'] = $usuario_id;
        }

        if ($anestesistaSelecionado === 'none') {
            $where_conditions[] = 'p.anestesista_id IS NULL';
        } elseif (!empty($anestesistaSelecionado)) {
            $where_conditions[] = 'p.anestesista_id = ?';
            $params[] = $anestesistaSelecionado;
        }
        
        if ($busca) {
            $where_conditions[] = '(p.nome LIKE ? OR p.sobrenome LIKE ? OR p.cpf LIKE ? OR p.email LIKE ? OR pr.nome LIKE ?)';
            $busca_param = "%$busca%";
            $params[] = $busca_param;
            $params[] = $busca_param;
            $params[] = $busca_param;
            $params[] = $busca_param;
            $params[] = $busca_param;
        }
        
        $where_clause = implode(' AND ', $where_conditions);
        
        // Contar total
        if ($busca) {
            // Se há busca, precisa do JOIN para buscar no nome do procedimento
            $count_sql = "SELECT COUNT(*) as total 
                         FROM pacientes p 
                         LEFT JOIN procedimentos pr ON p.procedimento_id = pr.id 
                         WHERE $where_clause";
        } else {
            // Se não há busca, pode contar direto da tabela pacientes
            $count_sql = "SELECT COUNT(*) as total FROM pacientes p WHERE $where_clause";
        }
        $total_result = $this->db->fetch($count_sql, $params);
        $total = $total_result['total'];
        
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
        
        // Paginação
        $total_pages = ceil($total / $limit);
        
        // Buscar estatísticas para o dashboard
        $stats_where = ['instituicao_id = ?'];
        $stats_params = [$instituicao_id];
        if ($anestesistaSelecionado === 'none') {
            $stats_where[] = 'anestesista_id IS NULL';
        } elseif (!empty($anestesistaSelecionado)) {
            $stats_where[] = 'anestesista_id = ?';
            $stats_params[] = $anestesistaSelecionado;
        }
        $stats_where_clause = implode(' AND ', $stats_where);
        
        $stats_sql = "SELECT 
                        COUNT(CASE WHEN inativo = 0 THEN 1 END) as total_ativos,
                        COUNT(CASE WHEN inativo = 1 THEN 1 END) as total_inativos,
                        COUNT(CASE WHEN COALESCE(questionario_status, 'nao_iniciado') = 'completo' THEN 1 END) as total_questionario_concluido,
                        COUNT(CASE WHEN COALESCE(questionario_status, 'nao_iniciado') = 'incompleto' THEN 1 END) as total_questionario_incompleto,
                        COUNT(CASE WHEN COALESCE(questionario_status, 'nao_iniciado') = 'nao_iniciado' THEN 1 END) as total_questionario_nao_iniciado
                      FROM pacientes 
                      WHERE $stats_where_clause";
        
        $stats = $this->db->fetch($stats_sql, $stats_params);

        // Lista de anestesistas para filtro (apenas para perfis que podem escolher)
        $anestesistasFiltro = $this->db->fetchAll("
            SELECT id, nome 
            FROM usuarios 
            WHERE instituicao_id = ? AND perfil_id = ? AND status = 'ativo'
            ORDER BY nome
        ", [$instituicao_id, PERFIL_ANESTESISTA]);
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/pacientes/index.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    public function create() {
        // Verificar se veio via QR Code
        $anestesista_id_qr = $this->getGet('anestesista_id');
        $anestesista_qr = null;
        $via_qr = false;
        
        if ($anestesista_id_qr) {
            // Buscar anestesista pelo ID
            $anestesista_qr = $this->db->fetch("SELECT u.*, i.nome as instituicao_nome 
                                              FROM usuarios u 
                                              LEFT JOIN instituicoes i ON u.instituicao_id = i.id
                                              WHERE u.id = ? AND u.perfil_id = 3 AND u.status = 'ativo'", 
                                             [$anestesista_id_qr]);
            if ($anestesista_qr) {
                $via_qr = true;
            }
        }
        
        // Buscar procedimentos ativos
        $procedimentos = $this->db->fetchAll("SELECT id, nome FROM procedimentos WHERE status = 'ativo' ORDER BY nome");
        
        // Buscar anestesistas ativos da instituição
        $anestesistas = $this->db->fetchAll("
            SELECT id, nome 
            FROM usuarios 
            WHERE instituicao_id = ? AND perfil_id = 3 AND status = 'ativo' 
            ORDER BY nome", 
            [$_SESSION['instituicao_id']]
        );

        if ($this->isPost()) {
            $nome = $this->getPost('nome');
            $sobrenome = $this->getPost('sobrenome');
            $cpf = $this->getPost('cpf');
            $data_nascimento = $this->getPost('data_nascimento');
            $sexo = $this->getPost('sexo');
            $telefone = $this->getPost('telefone');
            $email = $this->getPost('email');
            $endereco = $this->getPost('endereco');
            $procedimento_id = $this->getPost('procedimento_id'); // Changed from procedimento to procedimento_id
            $data_procedimento = $this->getPost('data_procedimento');
            $hora_procedimento = $this->getPost('hora_procedimento');
            
            // Se não foi informada a data do procedimento, definir data estimada (15 dias à frente)
            if (empty($data_procedimento)) {
                $data_procedimento = date('Y-m-d', strtotime('+15 days'));
            }
            
            // Novos campos de flag
            $necessita_orientacao_pre_anestesica = $this->getPost('necessita_orientacao_pre_anestesica') ? 1 : 0;
            $paciente_alto_risco = $this->getPost('paciente_alto_risco') ? 1 : 0;
            
            $anestesista_id = $this->getPost('anestesista_id');
            
            $errors = $this->validateRequired($_POST, ['nome', 'cpf', 'data_nascimento', 'sexo']);
            
            if (empty($errors)) {
                // Verificar se CPF já existe
                $existing = $this->db->fetch("SELECT id FROM pacientes WHERE cpf = ?", [$cpf]);
                if ($existing) {
                    $errors[] = 'Este CPF já está cadastrado.';
                }
            }
            
            if (empty($errors)) {
                // Verificar se o usuário atual existe e é válido
                $medico = $this->db->fetch("SELECT id FROM usuarios WHERE id = ? AND instituicao_id = ?", [$_SESSION['user_id'], $_SESSION['instituicao_id']]);
                if (!$medico) {
                    // Se o usuário não for encontrado, definir medico_id como null temporariamente
                    $medico_id = null;
                } else {
                    $medico_id = $_SESSION['user_id'];
                }
            }
            
            if (empty($errors)) {
                // Verificar limite de pacientes (10 pacientes gratuitos)
                $instituicao_id = $_SESSION['instituicao_id'];
                
                // Buscar assinatura ativa
                $assinatura = $this->db->fetch("
                    SELECT a.*, p.pacientes_incluidos
                    FROM assinaturas a
                    JOIN planos p ON a.plano_id = p.id
                    WHERE a.instituicao_id = ? AND a.status = 'ativa'
                    ORDER BY a.created_at DESC
                    LIMIT 1
                ", [$instituicao_id]);
                
                // Buscar contadores da instituição
                $instituicao = $this->db->fetch("
                    SELECT pacientes_gratis_usados 
                    FROM instituicoes 
                    WHERE id = ?
                ", [$instituicao_id]);
                
                $pacientes_gratis_usados = $instituicao['pacientes_gratis_usados'] ?? 0;
                
                // Verificar se pode cadastrar
                if (!$assinatura) {
                    // Sem assinatura - verificar se ainda tem pacientes gratuitos
                    if ($pacientes_gratis_usados >= 10) {
                        $errors[] = 'Você atingiu o limite de 10 pacientes gratuitos. Por favor, escolha um plano para continuar cadastrando pacientes.';
                        $_SESSION['flash_message'] = 'Limite de pacientes gratuitos atingido. Escolha um plano para continuar.';
                        $_SESSION['flash_type'] = 'warning';
                    }
                } else {
                    // Com assinatura - verificar se não excedeu o limite do plano
                    if ($assinatura['pacientes_usados'] >= $assinatura['pacientes_incluidos']) {
                        $errors[] = 'Você atingiu o limite de pacientes do seu plano. Por favor, renove ou faça upgrade do plano.';
                        $_SESSION['flash_message'] = 'Limite de pacientes do plano atingido. Renove ou faça upgrade.';
                        $_SESSION['flash_type'] = 'warning';
                    }
                }
            }
            
            if (empty($errors)) {
                // Gerar token único
                $token = bin2hex(random_bytes(32));
                $link_acesso = '/paciente/acesso/' . $token;
                
                $sql = "INSERT INTO pacientes (instituicao_id, medico_id, anestesista_id, nome, sobrenome, cpf, data_nascimento, sexo, telefone, email, procedimento_id, data_procedimento, necessita_orientacao_pre_anestesica, paciente_alto_risco, link_acesso, token_acesso) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $this->db->query($sql, [
                    $_SESSION['instituicao_id'],
                    $medico_id, // Usar a variável validada
                    $anestesista_id ?: null, // Tornar opcional
                    $nome,
                    $sobrenome,
                    $cpf,
                    $data_nascimento,
                    $sexo,
                    $telefone,
                    $email,
                    $procedimento_id,
                    $data_procedimento,
                    $necessita_orientacao_pre_anestesica,
                    $paciente_alto_risco,
                    $link_acesso,
                    $token
                ]);
                
                $paciente_id = $this->db->lastInsertId();
                
                // Atualizar contadores de pacientes
                $instituicao_id = $_SESSION['instituicao_id'];
                
                // Verificar se tem assinatura ativa
                $assinatura = $this->db->fetch("
                    SELECT id, pacientes_usados
                    FROM assinaturas
                    WHERE instituicao_id = ? AND status = 'ativa'
                    ORDER BY created_at DESC
                    LIMIT 1
                ", [$instituicao_id]);
                
                if ($assinatura) {
                    // Incrementar contador da assinatura
                    $this->db->query("
                        UPDATE assinaturas 
                        SET pacientes_usados = pacientes_usados + 1
                        WHERE id = ?
                    ", [$assinatura['id']]);
                } else {
                    // Incrementar contador de pacientes gratuitos
                    $this->db->query("
                        UPDATE instituicoes 
                        SET pacientes_gratis_usados = COALESCE(pacientes_gratis_usados, 0) + 1
                        WHERE id = ?
                    ", [$instituicao_id]);
                }
                
                // Registrar atividade
                $this->logActivity('cadastro_paciente', "Paciente cadastrado: $nome");
                
                // Inserir na jornada
                $this->iniciarJornada($paciente_id);
                
                // Enviar email de boas-vindas com link de acesso
                try {
                    require_once APP_PATH . '/classes/EmailSender.php';
                    
                    $emailSender = new EmailSender();
                    
                    // Construir URL completa do link de acesso
                    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
                    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                    $link_acesso_completo = $protocol . "://" . $host . $link_acesso;
                    
                    // Buscar nome da instituição
                    $instituicao = $this->db->fetch("SELECT nome FROM instituicoes WHERE id = ?", [$_SESSION['instituicao_id']]);
                    $instituicao_nome = $instituicao['nome'] ?? 'NutriCheck';
                    
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
                    $subject = "Bem-vindo ao NutriCheck - " . $instituicao_nome;
                    
                    // Enviar email
                    $emailSender->sendEmail($email, $subject, $email_body);
                    
                } catch (Exception $e) {
                    // Log do erro mas não interrompe o fluxo
                    error_log("Erro ao enviar email de boas-vindas: " . $e->getMessage());
                }
                
                $_SESSION['flash_message'] = "Paciente cadastrado com sucesso!";
                $_SESSION['flash_type'] = 'success';
                $this->redirect(BASE_URL . '/pacientes');
            }
        }
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/pacientes/create.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    public function view($id) {
        $instituicao_id = $_SESSION['instituicao_id'];
        
        $sql = "SELECT p.*, u.nome as medico_nome, e.nome as anestesista_nome 
                FROM pacientes p 
                LEFT JOIN usuarios u ON p.medico_id = u.id 
                LEFT JOIN usuarios e ON p.anestesista_id = e.id 
                WHERE p.id = ? AND p.instituicao_id = ?";
        
        $paciente = $this->db->fetch($sql, [$id, $instituicao_id]);
        
        if (!$paciente) {
            http_response_code(404);
            echo "Paciente não encontrado";
            return;
        }
        
        // Buscar jornada do paciente
        $jornada_sql = "SELECT * FROM jornada_paciente WHERE paciente_id = ? ORDER BY created_at";
        $jornada = $this->db->fetchAll($jornada_sql, [$id]);
        
        // Buscar selfie
        $selfie_sql = "SELECT * FROM selfies_pacientes WHERE paciente_id = ? ORDER BY created_at DESC LIMIT 1";
        $selfie = $this->db->fetch($selfie_sql, [$id]);
        
        // Buscar autorização
        $auth_sql = "SELECT aa.*, u.nome as anestesista_nome 
                     FROM autorizacoes_anestesia aa 
                     LEFT JOIN usuarios u ON aa.anestesista_id = u.id 
                     WHERE aa.paciente_id = ?";
        $autorizacao = $this->db->fetch($auth_sql, [$id]);
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/pacientes/view.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    public function edit($id) {
        $instituicao_id = $_SESSION['instituicao_id'];
        // Buscar procedimentos ativos
        $procedimentos = $this->db->fetchAll("SELECT id, nome FROM procedimentos WHERE status = 'ativo' ORDER BY nome");
        
        // Buscar anestesistas ativos da instituição
        $anestesistas = $this->db->fetchAll("
            SELECT id, nome 
            FROM usuarios 
            WHERE instituicao_id = ? AND perfil_id = 3 AND status = 'ativo' 
            ORDER BY nome", 
            [$instituicao_id]
        );
        
        $sql = "SELECT p.*, pr.nome as procedimento_nome 
                FROM pacientes p 
                LEFT JOIN procedimentos pr ON p.procedimento_id = pr.id 
                WHERE p.id = ? AND p.instituicao_id = ?";
        $paciente = $this->db->fetch($sql, [$id, $instituicao_id]);
        
        if (!$paciente) {
            http_response_code(404);
            echo "Paciente não encontrado";
            return;
        }
        
        if ($this->isPost()) {
            $nome = $this->getPost('nome');
            $sobrenome = $this->getPost('sobrenome');
            $cpf = $this->getPost('cpf');
            $data_nascimento = $this->getPost('data_nascimento');
            $sexo = $this->getPost('sexo');
            $telefone = $this->getPost('telefone');
            $email = $this->getPost('email');
            $procedimento_id = $this->getPost('procedimento_id');
            $data_procedimento = $this->getPost('data_procedimento');
            $anestesista_id = $this->getPost('anestesista_id');
            
            // Se não foi informada a data do procedimento, definir data estimada (15 dias à frente)
            if (empty($data_procedimento)) {
                $data_procedimento = date('Y-m-d', strtotime('+15 days'));
            }
            
            // Novos campos de flag
            $necessita_orientacao_pre_anestesica = $this->getPost('necessita_orientacao_pre_anestesica') ? 1 : 0;
            $paciente_alto_risco = $this->getPost('paciente_alto_risco') ? 1 : 0;

            $errors = $this->validateRequired($_POST, ['nome', 'cpf', 'data_nascimento', 'sexo', 'procedimento_id']);

            if (empty($errors)) {
                // Verificar se CPF já existe (exceto para o próprio paciente)
                $existing = $this->db->fetch("SELECT id FROM pacientes WHERE cpf = ? AND id != ?", [$cpf, $id]);
                if ($existing) {
                    $errors[] = 'Este CPF já está cadastrado para outro paciente.';
                }
            }

            if (empty($errors)) {
                $sql = "UPDATE pacientes SET nome = ?, sobrenome = ?, cpf = ?, data_nascimento = ?, sexo = ?, telefone = ?, email = ?, procedimento_id = ?, data_procedimento = ?, anestesista_id = ?, necessita_orientacao_pre_anestesica = ?, paciente_alto_risco = ? WHERE id = ?";
                $this->db->query($sql, [
                    $nome, $sobrenome, $cpf, $data_nascimento, $sexo, $telefone, $email, $procedimento_id, $data_procedimento, $anestesista_id ?: null, $necessita_orientacao_pre_anestesica, $paciente_alto_risco, $id
                ]);

                $_SESSION['flash_message'] = "Paciente atualizado com sucesso!";
                $_SESSION['flash_type'] = 'success';
                $this->redirect("/pacientes");
            }
        }
        
        // Definir variáveis para a view
        $errors = $errors ?? [];
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/pacientes/edit.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    public function delete($id) {
        $instituicao_id = $_SESSION['instituicao_id'];
        
        $sql = "SELECT nome FROM pacientes WHERE id = ? AND instituicao_id = ?";
        $paciente = $this->db->fetch($sql, [$id, $instituicao_id]);
        
        if (!$paciente) {
            http_response_code(404);
            echo "Paciente não encontrado";
            return;
        }
        
        // Inativar paciente (soft delete)
        $sql = "UPDATE pacientes SET inativo = 1 WHERE id = ?";
        $this->db->query($sql, [$id]);
        
        $this->logActivity('inativacao_paciente', "Paciente inativado: " . $paciente['nome']);
        
        $_SESSION['flash_message'] = "Paciente inativado com sucesso!";
        $_SESSION['flash_type'] = 'success';
        $this->redirect('/pacientes');
    }
    
    public function reativar($id) {
        $instituicao_id = $_SESSION['instituicao_id'];
        
        $sql = "SELECT nome FROM pacientes WHERE id = ? AND instituicao_id = ?";
        $paciente = $this->db->fetch($sql, [$id, $instituicao_id]);
        
        if (!$paciente) {
            http_response_code(404);
            echo "Paciente não encontrado";
            return;
        }
        
        // Reativar paciente
        $sql = "UPDATE pacientes SET inativo = 0 WHERE id = ?";
        $this->db->query($sql, [$id]);
        
        $this->logActivity('reativacao_paciente', "Paciente reativado: " . $paciente['nome']);
        
        $_SESSION['flash_message'] = "Paciente reativado com sucesso!";
        $_SESSION['flash_type'] = 'success';
        $this->redirect('/pacientes');
    }
    
    public function autorizar($id) {
        $instituicao_id = $_SESSION['instituicao_id'];
        $anestesista_id = $_SESSION['user_id'];
        
        // Verificar se é anestesista
        if ($_SESSION['perfil_id'] != 3) { // 3 = anestesista
            http_response_code(403);
            echo "Acesso negado";
            return;
        }
        
        $sql = "SELECT * FROM pacientes WHERE id = ? AND instituicao_id = ?";
        $paciente = $this->db->fetch($sql, [$id, $instituicao_id]);
        
        if (!$paciente) {
            http_response_code(404);
            echo "Paciente não encontrado";
            return;
        }
        
        if ($this->isPost()) {
            $autorizado = $this->getPost('autorizado') === '1';
            $observacoes = $this->getPost('observacoes');
            
            // Verificar se todas as etapas foram concluídas
            $jornada_sql = "SELECT COUNT(*) as total FROM jornada_paciente 
                           WHERE paciente_id = ? AND status = 'concluido'";
            $jornada_result = $this->db->fetch($jornada_sql, [$id]);
            
            if ($jornada_result['total'] < 5) { // 5 etapas obrigatórias
                $_SESSION['flash_message'] = "Paciente ainda não completou todas as etapas!";
                $_SESSION['flash_type'] = 'warning';
                $this->redirect("/pacientes/view/$id");
                return;
            }
            
            // Inserir autorização
            $sql = "INSERT INTO autorizacoes_anestesia (paciente_id, anestesista_id, autorizado, observacoes, data_autorizacao) 
                    VALUES (?, ?, ?, ?, NOW())";
            $this->db->query($sql, [$id, $anestesista_id, $autorizado, $observacoes]);
            
            // Atualizar status do paciente
            $status = $autorizado ? 'autorizado' : 'finalizado';
            $sql = "UPDATE pacientes SET status = ?, anestesista_id = ? WHERE id = ?";
            $this->db->query($sql, [$status, $anestesista_id, $id]);
            
            $acao = $autorizado ? 'autorizacao_anestesia' : 'negacao_anestesia';
            $this->logActivity($acao, "Liberação " . ($autorizado ? 'concedida' : 'negada') . " para: " . $paciente['nome']);
            
            $_SESSION['flash_message'] = "Autorização processada com sucesso!";
            $_SESSION['flash_type'] = 'success';
            $this->redirect("/pacientes/view/$id");
        }
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/pacientes/autorizar.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
    
    private function iniciarJornada($paciente_id) {
        $etapas = [
            'termo_lgpd' => 'Aceite do Termo LGPD',
            'selfie' => 'Captura de Selfie',
            'video' => 'Visualização de Vídeo',
            'questionario' => 'Preenchimento de Questionário',
            'autorizacao' => 'Liberação / Avaliação Nutricional'
        ];
        
        foreach ($etapas as $etapa => $descricao) {
            $sql = "INSERT INTO jornada_paciente (paciente_id, etapa, status) 
                    VALUES (?, ?, 'pendente')";
            $this->db->query($sql, [$paciente_id, $etapa]);
        }
    }
    
    public function cadastro($qr_code) {
        // Buscar anestesista pelo QR Code
        $anestesista = $this->db->fetch("SELECT u.*, i.nome as instituicao_nome 
                                        FROM usuarios u 
                                        LEFT JOIN instituicoes i ON u.instituicao_id = i.id
                                        WHERE u.qr_code = ? AND u.perfil_id = 3 AND u.status = 'ativo'", 
                                       [$qr_code]);
        
        if (!$anestesista) {
            $_SESSION['flash_message'] = 'QR Code inválido ou nutricionista não encontrado.';
            $this->redirect(BASE_URL . '/');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateRequired($_POST, ['nome', 'cpf', 'data_nascimento', 'sexo', 'telefone', 'email']);
            
            if (empty($errors)) {
                // Verificar se CPF já existe
                $existing = $this->db->fetch("SELECT id FROM pacientes WHERE cpf = ?", [$_POST['cpf']]);
                if ($existing) {
                    $errors[] = 'Este CPF já está cadastrado.';
                }
                
                // Verificar se email já existe
                $existing_email = $this->db->fetch("SELECT id FROM pacientes WHERE email = ?", [$_POST['email']]);
                if ($existing_email) {
                    $errors[] = 'Este email já está cadastrado.';
                }
            }
            
            if (empty($errors)) {
                $nome = $this->getPost('nome');
                $sobrenome = $this->getPost('sobrenome');
                $cpf = $this->getPost('cpf');
                $data_nascimento = $this->getPost('data_nascimento');
                $sexo = $this->getPost('sexo');
                $telefone = $this->getPost('telefone');
                $email = $this->getPost('email');
                $endereco = $this->getPost('endereco');
                $procedimento_id = $this->getPost('procedimento_id');
                $data_procedimento = $this->getPost('data_procedimento');
                
                // Se não foi informada a data do procedimento, definir data estimada (15 dias à frente)
                if (empty($data_procedimento)) {
                    $data_procedimento = date('Y-m-d', strtotime('+15 days'));
                }
                
                // Gerar token único para o paciente
                $token_acesso = bin2hex(random_bytes(32));
                $link_acesso = BASE_URL . '/paciente/acesso/' . $token_acesso;
                
                $sql = "INSERT INTO pacientes (instituicao_id, medico_id, anestesista_id, nome, sobrenome, cpf, data_nascimento, sexo, telefone, email, endereco, procedimento_id, data_procedimento, link_acesso, token_acesso, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'cadastrado')";
                
                $this->db->query($sql, [
                    $anestesista['instituicao_id'],
                    null, // médico_id será definido posteriormente
                    $anestesista['id'],
                    $nome,
                    $sobrenome,
                    $cpf,
                    $data_nascimento,
                    $sexo,
                    $telefone,
                    $email,
                    $endereco,
                    $procedimento_id,
                    $data_procedimento,
                    $link_acesso,
                    $token_acesso
                ]);
                
                $paciente_id = $this->db->lastInsertId();
                
                // Iniciar jornada do paciente
                $this->iniciarJornada($paciente_id);
                
                // Log da atividade
                $this->logActivity('cadastro_paciente_qr', "Paciente cadastrado via QR Code do nutricionista {$anestesista['nome']}", $paciente_id);
                
                $_SESSION['flash_message'] = 'Paciente cadastrado com sucesso! Link de acesso: ' . $link_acesso;
                $this->redirect(BASE_URL . '/pacientes');
                return;
            }
        }
        
        // Buscar procedimentos para o dropdown
        $procedimentos = $this->db->fetchAll("SELECT * FROM procedimentos WHERE status = 'ativo' ORDER BY nome");
        
        // Capturar conteúdo da view
        ob_start();
        include APP_PATH . '/views/pacientes/create.php';
        $content = ob_get_clean();
        
        // Incluir layout principal
        include APP_PATH . '/views/layouts/main.php';
    }
} 