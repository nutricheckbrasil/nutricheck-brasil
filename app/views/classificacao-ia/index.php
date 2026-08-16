<div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
            <h4 class="mb-0">
                <i class="bi bi-robot text-primary"></i> Classificação Paciente IA
            </h4>
        </div>

        <!-- Informação sobre Classificação IA -->
        <div class="bg-info bg-opacity-10 p-3 rounded border border-info mb-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-robot text-info me-3 fs-4"></i>
                <div>
                    <p class="text-muted mb-0">
                        A classificação de risco é gerada automaticamente por meio do questionário respondidos e histórico para determinar o nível de risco anestésico. 
                        Os valores possíveis são: <strong>Baixo Risco</strong> (Aguarda Liberação Anestesica), 
                        <strong>Risco Moderado:</strong> (Aguarda Liberação do Nutricionista) ou <strong>Alto Risco:</strong> (Encaminhado para consulta pré-anestésica). 
                    </p>
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="row mb-3">
            <div class="col-md-3 mb-2">
                <div class="card bg-primary text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0"><?= $stats['total_pacientes'] ?? 0 ?></h6>
                                <small>Total de Pacientes</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card bg-success text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0"><?= $stats['classificados_ia'] ?? 0 ?></h6>
                                <small>Liberados para Procedimento</small>  
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-robot fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card bg-warning text-dark">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0"><?= $stats['nao_classificados'] ?? 0 ?></h6>
                                <small>Pendentes de Classificação IA</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-clock fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card bg-danger text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0"><?= $stats['alto_risco'] ?? 0 ?></h6>
                                <small>Classificação IA - Alto Risco</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <form class="row g-2 mb-3" method="get" action="">
            <div class="col-12 col-md-4">
                <input type="text" name="busca" class="form-control" placeholder="Buscar por nome, CPF ou email" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
            </div>
            <div class="col-12 col-md-3">
                <select name="filtro" class="form-select">
                    <option value="">Todos os Pacientes</option>
                    <option value="todos" <?= ($_GET['filtro'] ?? '') === 'todos' ? 'selected' : '' ?>>Todos</option>
                    <option value="classificados" <?= ($_GET['filtro'] ?? '') === 'classificados' ? 'selected' : '' ?>>Liberados para Procedimento</option>
                    <option value="nao_classificados" <?= ($_GET['filtro'] ?? '') === 'nao_classificados' ? 'selected' : '' ?>>Pendentes IA</option>
                    <option value="alto_risco" <?= ($_GET['filtro'] ?? '') === 'alto_risco' ? 'selected' : '' ?>>Alto Risco</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <a href="classificacao-ia" class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-x-circle"></i> Limpar
                </a>
            </div>
        </form>

        <!-- Lista de Pacientes -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Idade</th>
                        <th>Sexo</th>
                        <th>Cirurgião</th>
                        <th>Nutricionista</th>
                        <th>Procedimento</th>
                        <th>Classificação IA</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pacientes)): ?>
                        <?php foreach ($pacientes as $paciente): ?>
                            <tr>
                                <td data-label="Nome"><?= htmlspecialchars($paciente['nome']) ?></td>
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
                                <td data-label="Cirurgião"><?= htmlspecialchars($paciente['medico_nome'] ?? '-') ?></td>
                                <td data-label="Nutricionista"><?= htmlspecialchars($paciente['anestesista_nome'] ?? '-') ?></td>
                                <td data-label="Procedimento"><?= htmlspecialchars($paciente['procedimento_nome'] ?? '-') ?></td>
                                <td data-label="Classificação IA" class="text-center">
                                    <?php if (!empty($paciente['classificacao_ia'])): ?>
                                        <?php
                                        $classificacao = $paciente['classificacao_ia'];
                                        $badge_class = '';
                                        $icon = '';
                                        $text = '';
                                        
                                        switch ($classificacao) {
                                            case 'baixo_risco':
                                                $badge_class = 'bg-success';
                                                $icon = 'bi-check-circle';
                                                $text = 'Baixo Risco (Liberado)';
                                                break;
                                           case 'risco_moderado':
                                                $badge_class = 'bg-warning text-dark';
                                                $icon = 'bi-exclamation-triangle';
                                                $text = 'Risco Moderado';
                                                break;
                                           case 'alto_risco':
                                                $badge_class = 'bg-danger';
                                                $icon = 'bi-exclamation-triangle-fill';
                                                $text = 'Alto Risco';
                                                break;
                                        }
                                        ?>
                                        <div class="d-flex justify-content-center">
                                            <span class="badge <?= $badge_class ?>">
                                                <i class="bi <?= $icon ?>"></i> <?= $text ?>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex justify-content-center">
                                            <span class="text-muted">
                                                <i class="bi bi-clock"></i> Pendente
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Ações" class="text-center">
                                    <a href="pacientes/view/<?= $paciente['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Ver Detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="pacientes/edit/<?= $paciente['id'] ?>" class="btn btn-sm btn-outline-secondary me-1" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="pacientes/delete/<?= $paciente['id'] ?>" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este paciente?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center text-muted">Nenhum paciente encontrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Paginação -->
        <?php if (isset($total_pages) && $total_pages > 1): ?>
            <nav aria-label="Navegação de páginas" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&busca=<?= urlencode($busca) ?>&filtro=<?= urlencode($filtro) ?>">
                                <i class="bi bi-chevron-left"></i> Anterior
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $page + 2);
                    
                    if ($start_page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=1&busca=<?= urlencode($busca) ?>&filtro=<?= urlencode($filtro) ?>">1</a>
                        </li>
                        <?php if ($start_page > 2): ?>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&busca=<?= urlencode($busca) ?>&filtro=<?= urlencode($filtro) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($end_page < $total_pages): ?>
                        <?php if ($end_page < $total_pages - 1): ?>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        <?php endif; ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $total_pages ?>&busca=<?= urlencode($busca) ?>&filtro=<?= urlencode($filtro) ?>"><?= $total_pages ?></a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&busca=<?= urlencode($busca) ?>&filtro=<?= urlencode($filtro) ?>">
                                Próxima <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <!-- Informações da paginação -->
            <div class="text-center text-muted mt-2">
                <small>
                    <?php if (isset($total) && $total > 0): ?>
                        Mostrando <?= (($page - 1) * $limit) + 1 ?> a <?= min($page * $limit, $total) ?> de <?= $total ?> paciente<?= $total > 1 ? 's' : '' ?>
                    <?php else: ?>
                        Nenhum paciente encontrado
                    <?php endif; ?>
                </small>
            </div>
        <?php endif; ?>
</div>