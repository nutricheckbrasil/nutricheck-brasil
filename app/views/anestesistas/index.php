<div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0">Gestão de Anestesistas</h4>
                <a href="<?= BASE_URL ?>/anestesistas/create" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Cadastrar Anestesista
                </a>
            </div>
            
            <!-- Cards de Estatísticas -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0"><?= $stats['total_anestesistas'] ?></h6>
                                    <small>Total de Anestesistas</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-people fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0"><?= $stats['ativos'] ?></h6>
                                    <small>Ativos</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-check-circle fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0"><?= $stats['inativos'] ?></h6>
                                    <small>Inativos</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-pause-circle fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0"><?= $stats['com_qr_code'] ?></h6>
                                    <small>Com QR Code</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-qr-code fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filtros -->
            <div class="card mb-3">
                <div class="card-body py-2">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <select name="filtro" class="form-select">
                                <option value="todos" <?= $filtro === 'todos' ? 'selected' : '' ?>>Todos</option>
                                <option value="ativos" <?= $filtro === 'ativos' ? 'selected' : '' ?>>Ativos</option>
                                <option value="inativos" <?= $filtro === 'inativos' ? 'selected' : '' ?>>Inativos</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por nome, email ou CRM..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Tabela de Anestesistas -->
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($anestesistas)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>CRM</th>
                                        <th>Telefone</th>
                                        <th>QR Code</th>
                                        <th>Status</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($anestesistas as $anestesista): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($anestesista['foto_path'])): ?>
                                                    <img src="<?= BASE_URL ?>/<?= $anestesista['foto_path'] ?>" alt="Foto" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-person text-white"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($anestesista['nome']) ?></td>
                                            <td><?= htmlspecialchars($anestesista['email']) ?></td>
                                            <td><?= htmlspecialchars($anestesista['crm']) ?></td>
                                            <td><?= htmlspecialchars($anestesista['telefone']) ?></td>
                                            <td>
                                                <?php if (!empty($anestesista['qr_code_path'])): ?>
                                                    <img src="<?= BASE_URL ?>/<?= $anestesista['qr_code_path'] ?>" alt="QR Code" style="width: 30px; height: 30px;">
                                                <?php else: ?>
                                                    <span class="text-muted">Não gerado</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $anestesista['status'] === 'ativo' ? 'success' : 'secondary' ?>">
                                                    <?= ucfirst($anestesista['status']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="<?= BASE_URL ?>/anestesistas/view/<?= $anestesista['id'] ?>" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?= BASE_URL ?>/anestesistas/edit/<?= $anestesista['id'] ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <?php if (empty($anestesista['qr_code_path'])): ?>
                                                        <a href="<?= BASE_URL ?>/anestesistas/regenerate-qr/<?= $anestesista['id'] ?>" class="btn btn-sm btn-outline-info" title="Gerar QR Code">
                                                            <i class="bi bi-qr-code"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmarExclusao(<?= $anestesista['id'] ?>)" title="Excluir">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">Nenhum anestesista encontrado</h5>
                            <p class="text-muted">Comece cadastrando seu primeiro anestesista.</p>
                            <a href="<?= BASE_URL ?>/anestesistas/create" class="btn btn-primary">
                                <i class="bi bi-person-plus"></i> Cadastrar Anestesista
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
</div>

<script>
function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir este anestesista?')) {
        window.location.href = '<?= BASE_URL ?>/anestesistas/delete/' + id;
    }
}
</script>
