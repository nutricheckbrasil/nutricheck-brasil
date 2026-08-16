<?php require_once APP_PATH . '/views/layouts/main.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-check text-primary"></i> Solicitações de Demonstração</h2>
        <div class="text-muted">
            Total: <?= $total ?> solicitações
        </div>
    </div>

    <?php if (!empty($demonstracoes)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Data</th>
                        <th>Nome</th>
                        <th>Instituição</th>
                        <th>Contato</th>
                        <th>Interesse</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($demonstracoes as $demo): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($demo['created_at'])) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($demo['nome_completo']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($demo['cargo_funcao']) ?></small>
                            </td>
                            <td>
                                <?= htmlspecialchars($demo['instituicao']) ?>
                                <?php if ($demo['cnpj']): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($demo['cnpj']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><?= htmlspecialchars($demo['email']) ?></div>
                                <div><?= htmlspecialchars($demo['telefone']) ?></div>
                            </td>
                            <td>
                                <?php
                                $interesses = [
                                    'gestao_pacientes' => 'Gestão de Pacientes',
                                    'agendamentos' => 'Sistema de Agendamentos',
                                    'consentimentos' => 'Gestão de Consentimentos',
                                    'relatorios' => 'Relatórios e Analytics',
                                    'integracao' => 'Integração com Sistemas',
                                    'outro' => 'Outro'
                                ];
                                echo $interesses[$demo['interesse_principal']] ?? $demo['interesse_principal'];
                                ?>
                            </td>
                            <td>
                                <?php
                                $status_classes = [
                                    'pendente' => 'bg-warning',
                                    'contatado' => 'bg-info',
                                    'agendado' => 'bg-primary',
                                    'realizado' => 'bg-success',
                                    'cancelado' => 'bg-danger'
                                ];
                                $status_labels = [
                                    'pendente' => 'Pendente',
                                    'contatado' => 'Contatado',
                                    'agendado' => 'Agendado',
                                    'realizado' => 'Realizado',
                                    'cancelado' => 'Cancelado'
                                ];
                                ?>
                                <span class="badge <?= $status_classes[$demo['status']] ?>">
                                    <?= $status_labels[$demo['status']] ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        onclick="abrirModal(<?= $demo['id'] ?>)">
                                    <i class="bi bi-eye"></i> Ver
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Paginação" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>">&laquo; Anterior</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>">Próximo &raquo;</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
            <h4 class="text-muted mt-3">Nenhuma solicitação encontrada</h4>
            <p class="text-muted">Ainda não há solicitações de demonstração.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Modal para detalhes -->
<div class="modal fade" id="modalDetalhes" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes da Solicitação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
function abrirModal(id) {
    // Aqui você pode implementar AJAX para carregar os detalhes
    // Por enquanto, vamos apenas abrir o modal
    const modal = new bootstrap.Modal(document.getElementById('modalDetalhes'));
    modal.show();
}
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?> 