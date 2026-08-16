<div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
            <h4 class="mb-0">Meus Pacientes</h4>
            <a href="pacientes/create" class="btn btn-primary mt-3 mt-md-0">
                <i class="bi bi-person-plus"></i> Novo Paciente
            </a>
        </div>
        
        <!-- Estatísticas Rápidas -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 mb-3">
            <div class="col">
                <div class="card bg-primary text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0"><?= $stats['total_ativos'] ?? 0 ?></h6>
                                <small>Pacientes Ativos</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-person-check fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card bg-danger text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0"><?= $stats['total_inativos'] ?? 0 ?></h6>
                                <small>Pacientes Inativos</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-person-x fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card bg-success text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0"><?= $stats['total_questionario_concluido'] ?? 0 ?></h6>
                                <small>Questionários Concluídos</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-clipboard-check fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card bg-warning text-dark">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0"><?= $stats['total_questionario_incompleto'] ?? 0 ?></h6>
                                <small>Questionários Incompletos</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-exclamation-triangle fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card bg-secondary text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0"><?= $stats['total_questionario_nao_iniciado'] ?? 0 ?></h6>
                                <small>Questionários Não Iniciados</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-hourglass-split fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form class="row g-2 mb-3" method="get" action="">
    <div class="col-12 col-md-4 col-lg-3">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por nome, sobrenome, CPF, email ou procedimento" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
    </div>
    <div class="col-12 col-md-3 col-lg-2">
        <select name="status" class="form-select">
            <option value="">Todos os Pacientes</option>
            <option value="ativo" <?= ($_GET['status'] ?? '') === 'ativo' ? 'selected' : '' ?>>Apenas Ativos</option>
            <option value="inativo" <?= ($_GET['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Apenas Inativos</option>
        </select>
    </div>
    <?php if (!empty($anestesistasFiltro)): ?>
    <div class="col-12 col-md-3 col-lg-2">
        <select name="anestesista_id" class="form-select">
            <option value="">Todos os Nutricionistas</option>
            <option value="none" <?= ($anestesistaSelecionado ?? '') === 'none' ? 'selected' : '' ?>>Sem nutricionista</option>
            <?php foreach ($anestesistasFiltro as $anestesista): ?>
                <option value="<?= $anestesista['id'] ?>"
                    <?= ($anestesistaSelecionado ?? '') == $anestesista['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($anestesista['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-primary">
            <i class="bi bi-search"></i> Buscar
        </button>
        <a href="pacientes" class="btn btn-outline-secondary ms-2">
            <i class="bi bi-x-circle"></i> Limpar
        </a>
    </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Idade</th>
                        <th>Sexo</th>
                        <th>Nutricionista</th>
                        <th>Procedimento</th>
                        <th>Data Procedimento</th>
                        <th class="text-center">Gerar Questionário</th>
                        <th>Status Questionario</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pacientes)): ?>
                        <?php foreach ($pacientes as $paciente): ?>
                            <tr class="<?= $paciente['inativo'] == 1 ? 'table-secondary' : '' ?>">
                                <td data-label="Nome">
                                    <?php if ($paciente['inativo'] == 1): ?>
                                        <i class="bi bi-person-x-fill text-muted me-2" title="Paciente Inativo"></i>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($paciente['nome']) ?> <?= htmlspecialchars($paciente['sobrenome'] ?? '') ?>
                                </td>
                                <td data-label="Idade">
                                    <?php
                                    if (!empty($paciente['data_nascimento'])) {
                                        $nasc = new DateTime($paciente['data_nascimento']);
                                        $hoje = new DateTime();
                                        $idade = $hoje->diff($nasc)->y;
                                        echo $idade . ' anos';
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td data-label="Sexo"><?= htmlspecialchars($paciente['sexo'] ?? '-') ?></td>
                                <td data-label="Nutricionista"><?= htmlspecialchars($paciente['anestesista_nome'] ?? '-') ?></td>
                                <td data-label="Procedimento"><?= htmlspecialchars($paciente['procedimento_nome'] ?? '-') ?></td>
                                <td data-label="Data do Procedimento">
                                    <?php if (!empty($paciente['data_procedimento'])): ?>
                                        <?= htmlspecialchars(date('d/m/Y', strtotime($paciente['data_procedimento']))) ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td data-label="Questionário IA" class="text-center">
                                    <?php
                                    if (!defined('PROJECT_ROOT_URL')) {
                                        if (defined('BASE_URL') && !empty(BASE_URL)) {
                                            $baseUrl = rtrim(BASE_URL, '/');
                                            if (strpos($baseUrl, '/public') !== false) {
                                                $projectRoot = str_replace('/public', '', $baseUrl);
                                            } else {
                                                $projectRoot = dirname($baseUrl);
                                                if ($projectRoot === '/' || $projectRoot === '.') {
                                                    $projectRoot = '';
                                                }
                                            }
                                        } else {
                                            $scriptPath = $_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? '';
                                            if (!empty($scriptPath) && strpos($scriptPath, '/public/') !== false) {
                                                $projectRoot = substr($scriptPath, 0, strpos($scriptPath, '/public/'));
                                            } else {
                                                $projectRoot = '';
                                            }
                                        }
                                        define('PROJECT_ROOT_URL', $projectRoot);
                                    }
                                    $relatorioUrl = BASE_URL . '/gerar-relatorio-pdf.php?paciente_id=' . urlencode($paciente['id']);
                                    
                                    $questionarioStatus = $paciente['questionario_status'] ?? null;
                                    if (!$questionarioStatus) {
                                        $status = $paciente['status'] ?? 'cadastrado';
                                        $questionarioStatus = ($status === 'finalizado') ? 'completo' : 'nao_iniciado';
                                    }
                                    
                                    $roboHabilitado = $questionarioStatus === 'completo' || $questionarioStatus === 'incompleto';
                                    $btnClass = $roboHabilitado ? 'btn-success text-white' : 'btn-outline-secondary';
                                    $btnStyle = $roboHabilitado ? '' : 'opacity: 0.6;';
                                    $title = $roboHabilitado
                                        ? ($questionarioStatus === 'completo' ? 'Visualizar relatório do questionário' : 'Gerar relatório do questionário incompleto')
                                        : 'Questionário ainda não iniciado';
                                    ?>
                                    <a href="<?= $roboHabilitado ? $relatorioUrl : 'javascript:void(0);' ?>"
                                       class="btn btn-sm <?= $btnClass ?> <?= $roboHabilitado ? '' : 'disabled' ?>"
                                       style="<?= $btnStyle ?>"
                                       target="<?= $roboHabilitado ? '_blank' : '_self' ?>"
                                       rel="noopener"
                                       title="<?= $title ?>">
                                        <i class="bi bi-robot"></i>
                                    </a>
                                </td>
                                <td data-label="Status do Questionário IA">
                                    <?php
                                    $questionarioStatus = $paciente['questionario_status'] ?? null;
                                    $questionarioPercentual = $paciente['questionario_percentual'] ?? null;
                                    $questionarioRespondidos = $paciente['questionario_videos_respondidos'] ?? null;
                                    $questionarioTotal = $paciente['questionario_total_videos'] ?? null;
                                    
                                    if (!$questionarioStatus) {
                                        $status = $paciente['status'] ?? 'cadastrado';
                                        $questionarioStatus = ($status === 'finalizado') ? 'completo' : 'nao_iniciado';
                                    }
                                    
                                    $badge_class = 'bg-secondary';
                                    $status_text = 'Não iniciado';
                                    
                                    switch ($questionarioStatus) {
                                        case 'completo':
                                            $badge_class = 'bg-success';
                                            $status_text = 'Concluído';
                                            break;
                                        case 'incompleto':
                                            $badge_class = 'bg-warning text-dark';
                                            $status_text = 'Incompleto';
                                            break;
                                        case 'nao_iniciado':
                                        default:
                                            $badge_class = 'bg-secondary';
                                            $status_text = 'Não iniciado';
                                            break;
                                    }
                                    
                                    $detalhe = '';
                                    if ($questionarioStatus !== 'nao_iniciado') {
                                        if (is_numeric($questionarioPercentual)) {
                                            $detalhe = $questionarioPercentual . '%';
                                        } elseif (is_numeric($questionarioRespondidos) && is_numeric($questionarioTotal) && $questionarioTotal > 0) {
                                            $detalhe = "{$questionarioRespondidos}/{$questionarioTotal}";
                                        }
                                    }
                                    ?>
                                    <span class="badge <?= $badge_class ?>">
                                        <?= $status_text ?>
                                        <?php if (!empty($detalhe)): ?>
                                            <small>(<?= $detalhe ?>)</small>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                
                                <td data-label="Ações" class="text-center">
                                    <a href="pacientes/edit/<?= $paciente['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Editar Paciente"><i class="bi bi-pencil"></i></a>
                                    <?php if ($paciente['inativo'] == 1): ?>
                                        <a href="pacientes/reativar/<?= $paciente['id'] ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Tem certeza que deseja reativar este paciente?');" title="Reativar Paciente"><i class="bi bi-person-check"></i></a>
                                    <?php else: ?>
                                        <a href="pacientes/delete/<?= $paciente['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja inativar este paciente?');" title="Inativar Paciente"><i class="bi bi-person-x"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="11" class="text-center text-muted">Nenhum paciente cadastrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Paginação -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Paginação de pacientes" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&busca=<?= urlencode($busca ?? '') ?>">&laquo; Anterior</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&busca=<?= urlencode($busca ?? '') ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&busca=<?= urlencode($busca ?? '') ?>">Próximo &raquo;</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="text-center text-muted small mt-2">
                Mostrando <?= count($pacientes) ?> de <?= $total ?> pacientes
            </div>
        <?php endif; ?>
</div>
