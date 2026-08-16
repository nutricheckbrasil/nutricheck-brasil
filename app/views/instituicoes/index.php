<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="mb-0">
            <i class="bi bi-building text-primary"></i> Instituições
        </h2>
        <a href="<?= BASE_URL ?>/instituicoes/create" class="btn btn-primary btn-lg mt-3 mt-md-0">
            <i class="bi bi-building-add"></i> Nova Instituição
        </a>
    </div>

    <!-- Filtros -->
    <form class="row g-2 mb-4" method="get" action="">
        <div class="col-12 col-md-6 col-lg-4">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou CNPJ" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
        </div>
        <div class="col-12 col-md-3">
            <select name="status" class="form-select">
                <option value="">Todos os Status</option>
                <option value="ativo" <?= ($_GET['status'] ?? '') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                <option value="inativo" <?= ($_GET['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-search"></i> Buscar
            </button>
            <a href="<?= BASE_URL ?>/instituicoes" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-x-circle"></i> Limpar
            </a>
        </div>
    </form>

    <!-- Lista de Instituições -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>CNPJ</th>
                    <th>Usuários</th>
                    <th>Pacientes</th>
                    <th>QR Code</th>
                    <th>Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($instituicoes)): ?>
                    <?php foreach ($instituicoes as $instituicao): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($instituicao['nome']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($instituicao['cnpj']) ?></td>
                            <td>
                                <span class="badge bg-primary"><?= $instituicao['total_usuarios'] ?? 0 ?></span>
                            </td>
                            <td>
                                <span class="badge bg-success"><?= $instituicao['total_pacientes'] ?? 0 ?></span>
                            </td>
                            <td>
                                <?php if ($instituicao['tem_qr_code'] > 0): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Gerado
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-x-circle"></i> Não gerado
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $instituicao['ativo'] ? 'success' : 'secondary' ?>">
                                    <?= $instituicao['ativo'] ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= BASE_URL ?>/instituicoes/view/<?= $instituicao['id'] ?>" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/instituicoes/edit/<?= $instituicao['id'] ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($instituicao['tem_qr_code'] == 0): ?>
                                        <a href="<?= BASE_URL ?>/instituicoes/regenerate-qr/<?= $instituicao['id'] ?>" class="btn btn-sm btn-outline-info" title="Gerar QR Code">
                                            <i class="bi bi-qr-code"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= BASE_URL ?>/instituicoes/regenerate-qr/<?= $instituicao['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Regenerar QR Code">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </a>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmarExclusao(<?= $instituicao['id'] ?>)" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted">Nenhuma instituição encontrada.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Paginação -->
    <?php if (isset($total_pages) && $total_pages > 1): ?>
        <nav aria-label="Paginação de instituições" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?>&busca=<?= urlencode($busca ?? '') ?>&status=<?= urlencode($status ?? '') ?>">&laquo; Anterior</a>
                    </li>
                <?php endif; ?>
                
                <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&busca=<?= urlencode($busca ?? '') ?>&status=<?= urlencode($status ?? '') ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>&busca=<?= urlencode($busca ?? '') ?>&status=<?= urlencode($status ?? '') ?>">Próximo &raquo;</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="text-center text-muted small mt-2">
            Mostrando <?= count($instituicoes) ?> de <?= $total ?? count($instituicoes) ?> instituições
        </div>
    <?php endif; ?>
</div>

<script>
function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir esta instituição?')) {
        window.location.href = '<?= BASE_URL ?>/instituicoes/delete/' + id;
    }
}
</script>