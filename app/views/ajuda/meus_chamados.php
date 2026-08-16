<div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
            <h4 class="mb-0">
                <i class="bi bi-ticket-detailed text-primary me-2"></i>Meus Chamados
            </h4>
            <a href="<?= BASE_URL ?>/ajuda/abrir-chamado" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Novo Chamado
            </a>
        </div>

        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            Chamado aberto com sucesso! Nossa equipe entrará em contato em breve.
        </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <!-- Estatísticas -->
        <div class="row mb-3">
            <div class="col-md-3 mb-2">
                <div class="card bg-primary text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0"><?= count($chamados) ?></h6>
                                <small>Total de Chamados</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-ticket-detailed fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-2">
                <div class="card bg-warning text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0"><?= count(array_filter($chamados, function($c) { return $c['status'] == STATUS_CHAMADO_ABERTO; })) ?></h6>
                                <small>Em Aberto</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-clock fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-2">
                <div class="card bg-info text-white">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0"><?= count(array_filter($chamados, function($c) { return $c['status'] == STATUS_CHAMADO_EM_ANDAMENTO; })) ?></h6>
                                <small>Em Andamento</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-gear fs-4"></i>
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
                                <h6 class="mb-0"><?= count(array_filter($chamados, function($c) { return $c['status'] == STATUS_CHAMADO_RESOLVIDO; })) ?></h6>
                                <small>Resolvidos</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Chamados -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-ul me-2"></i>Histórico de Chamados
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($chamados)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">Nenhum chamado encontrado</h5>
                        <p class="text-muted">Você ainda não abriu nenhum chamado de suporte.</p>
                        <a href="<?= BASE_URL ?>/ajuda/abrir-chamado" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Abrir Primeiro Chamado
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Número</th>
                                    <th>Assunto</th>
                                    <th>Categoria</th>
                                    <th>Urgência</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($chamados as $chamado): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($chamado['numero_chamado'] ?? 'N/A') ?></strong>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?= htmlspecialchars($chamado['assunto'] ?? 'Sem assunto') ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars(substr($chamado['descricao'] ?? '', 0, 50)) ?>...
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?= ucfirst(str_replace('_', ' ', $chamado['categoria'] ?? 'geral')) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $urgenciaClass = '';
                                            $urgenciaText = '';
                                            switch ($chamado['urgencia'] ?? 'baixa') {
                                                case URGENCIA_URGENTE:
                                                    $urgenciaClass = 'bg-danger';
                                                    $urgenciaText = 'Urgente';
                                                    break;
                                                case URGENCIA_ALTA:
                                                    $urgenciaClass = 'bg-warning';
                                                    $urgenciaText = 'Alta';
                                                    break;
                                                case URGENCIA_NORMAL:
                                                    $urgenciaClass = 'bg-info';
                                                    $urgenciaText = 'Normal';
                                                    break;
                                                case URGENCIA_BAIXA:
                                                    $urgenciaClass = 'bg-success';
                                                    $urgenciaText = 'Baixa';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge <?= $urgenciaClass ?>">
                                                <?= $urgenciaText ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            $statusText = '';
                                            switch ($chamado['status']) {
                                                case STATUS_CHAMADO_ABERTO:
                                                    $statusClass = 'bg-warning';
                                                    $statusText = 'Em Aberto';
                                                    break;
                                                case STATUS_CHAMADO_EM_ANDAMENTO:
                                                    $statusClass = 'bg-info';
                                                    $statusText = 'Em Andamento';
                                                    break;
                                                case STATUS_CHAMADO_AGUARDANDO_RESPOSTA:
                                                    $statusClass = 'bg-primary';
                                                    $statusText = 'Aguardando Resposta';
                                                    break;
                                                case STATUS_CHAMADO_RESOLVIDO:
                                                    $statusClass = 'bg-success';
                                                    $statusText = 'Resolvido';
                                                    break;
                                                case STATUS_CHAMADO_FECHADO:
                                                    $statusClass = 'bg-secondary';
                                                    $statusText = 'Fechado';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge <?= $statusClass ?>">
                                                <?= $statusText ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('d/m/Y H:i', strtotime($chamado['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= BASE_URL ?>/ajuda/visualizar-chamado?id=<?= $chamado['id'] ?>"
                                               class="btn btn-sm btn-outline-primary" style="display: none;">
                                                <i class="bi bi-eye"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
    </div>
</div>

 