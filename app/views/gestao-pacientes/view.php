<?php require_once APP_PATH . '/views/layouts/main.php'; ?>
<?php if (isset($_SESSION['perfil_id']) && $_SESSION['perfil_id'] == 2): ?>
<style>
@media (max-width: 991.98px) {
  .sidebar-link-lg, .sidebar-text-lg {
    font-size: 1rem;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
  }
  .flex-grow-1.p-4 {
    padding: 1rem !important;
  }
  .btn.btn-lg {
    width: 100%;
    margin-top: 1rem;
  }
}
@media (max-width: 767.98px) {
  .d-flex > .flex-shrink-0 {
    display: none !important;
  }
  .d-flex > .flex-grow-1 {
    width: 100% !important;
    padding: 0.5rem !important;
  }
}
</style>
<div class="d-flex">
    <?php require APP_PATH . '/views/layouts/sidebar.php'; ?>
    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h2 class="mb-0">
                <i class="bi bi-person-badge text-primary"></i> Detalhes do Nutricionista
            </h2>
            <a href="<?= BASE_URL ?>/gestao-pacientes" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>

        <!-- Informações do Nutricionista -->
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informações Pessoais
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nome:</label>
                                <p class="mb-0"><?= htmlspecialchars($anestesista['nome']) ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email:</label>
                                <p class="mb-0"><?= htmlspecialchars($anestesista['email']) ?></p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">COREN:</label>
                                <p class="mb-0">
                                    <span class="badge bg-secondary"><?= htmlspecialchars($anestesista['coren']) ?></span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status:</label>
                                <p class="mb-0">
                                    <?php
                                    $statusClass = $anestesista['status'] === 'ativo' ? 'bg-success' : 'bg-warning';
                                    $statusText = $anestesista['status'] === 'ativo' ? 'Ativo' : 'Inativo';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Instituição:</label>
                                <p class="mb-0"><?= htmlspecialchars($anestesista['instituicao_nome'] ?? 'Não informada') ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Data de Cadastro:</label>
                                <p class="mb-0"><?= date('d/m/Y H:i', strtotime($anestesista['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Estatísticas -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-graph-up me-2"></i>Estatísticas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Total de Pacientes:</span>
                                <strong class="text-primary"><?= $anestesista['total_pacientes'] ?></strong>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Pacientes Ativos:</span>
                                <strong class="text-success"><?= $anestesista['pacientes_ativos'] ?></strong>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Pacientes Inativos:</span>
                                <strong class="text-warning"><?= $anestesista['total_pacientes'] - $anestesista['pacientes_ativos'] ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Ações -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-gear me-2"></i>Ações
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= BASE_URL ?>/gestao-pacientes/updateStatus/<?= $anestesista['id'] ?>" method="post">
                            <div class="mb-3">
                                <label for="status" class="form-label">Alterar Status:</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="ativo" <?= $anestesista['status'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                                    <option value="inativo" <?= $anestesista['status'] === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-circle me-2"></i>Atualizar Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Pacientes -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-people me-2"></i>Pacientes Atribuídos
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($pacientes)): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-people text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2">Nenhum paciente atribuído a este anestesista.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>Idade</th>
                                    <th>Procedimento</th>
                                    <th>Data de Atribuição</th>
                                    <th>Status</th>
                                    <th>Observações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pacientes as $paciente): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($paciente['nome'] . ' ' . ($paciente['sobrenome'] ?? '')) ?></strong>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= htmlspecialchars($paciente['cpf'] ?? '-') ?></small>
                                        </td>
                                        <td>
                                            <?php
                                            $idade = '';
                                            if (!empty($paciente['data_nascimento'])) {
                                                $nascimento = new DateTime($paciente['data_nascimento']);
                                                $hoje = new DateTime();
                                                $idade = $hoje->diff($nascimento)->y;
                                            }
                                            ?>
                                            <span class="badge bg-info"><?= $idade ? $idade . ' anos' : 'N/A' ?></span>
                                        </td>
                                        <td>
                                            <small><?= htmlspecialchars($paciente['procedimento_nome'] ?? '-') ?></small>
                                        </td>
                                        <td>
                                            <small><?= date('d/m/Y H:i', strtotime($paciente['data_atribuicao'])) ?></small>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = $paciente['status_atribuicao'] === 'ativo' ? 'bg-success' : 'bg-warning';
                                            $statusText = $paciente['status_atribuicao'] === 'ativo' ? 'Ativo' : 'Inativo';
                                            ?>
                                            <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= htmlspecialchars($paciente['observacoes_atribuicao'] ?? '-') ?></small>
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
</div>
<?php else: ?>
<!-- Layout original para outros perfis -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h2 class="mb-0">
        <i class="bi bi-person-badge text-primary"></i> Detalhes do Nutricionista
    </h2>
                        <a href="<?= BASE_URL ?>/gestao-pacientes" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left me-2"></i>Voltar
    </a>
</div>

<!-- Informações do Nutricionista -->
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informações Pessoais
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nome:</label>
                        <p class="mb-0"><?= htmlspecialchars($anestesista['nome']) ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email:</label>
                        <p class="mb-0"><?= htmlspecialchars($anestesista['email']) ?></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">COREN:</label>
                        <p class="mb-0">
                            <span class="badge bg-secondary"><?= htmlspecialchars($anestesista['coren']) ?></span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status:</label>
                        <p class="mb-0">
                            <?php
                            $statusClass = $anestesista['status'] === 'ativo' ? 'bg-success' : 'bg-warning';
                            $statusText = $anestesista['status'] === 'ativo' ? 'Ativo' : 'Inativo';
                            ?>
                            <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                        </p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Instituição:</label>
                        <p class="mb-0"><?= htmlspecialchars($anestesista['instituicao_nome'] ?? 'Não informada') ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Data de Cadastro:</label>
                        <p class="mb-0"><?= date('d/m/Y H:i', strtotime($anestesista['created_at'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Estatísticas -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>Estatísticas
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total de Pacientes:</span>
                        <strong class="text-primary"><?= $anestesista['total_pacientes'] ?></strong>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Pacientes Ativos:</span>
                        <strong class="text-success"><?= $anestesista['pacientes_ativos'] ?></strong>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Pacientes Inativos:</span>
                        <strong class="text-warning"><?= $anestesista['total_pacientes'] - $anestesista['pacientes_ativos'] ?></strong>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Ações -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-gear me-2"></i>Ações
                </h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/gestao-pacientes/updateStatus/<?= $anestesista['id'] ?>" method="post">
                    <div class="mb-3">
                        <label for="status" class="form-label">Alterar Status:</label>
                        <select class="form-select" id="status" name="status">
                            <option value="ativo" <?= $anestesista['status'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                            <option value="inativo" <?= $anestesista['status'] === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-2"></i>Atualizar Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Pacientes -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-people me-2"></i>Pacientes Atribuídos
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($pacientes)): ?>
            <div class="text-center py-4">
                <i class="bi bi-people text-muted" style="font-size: 2rem;"></i>
                <p class="text-muted mt-2">Nenhum paciente atribuído a este anestesista.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Idade</th>
                            <th>Procedimento</th>
                            <th>Data de Atribuição</th>
                            <th>Status</th>
                            <th>Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pacientes as $paciente): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($paciente['nome'] . ' ' . ($paciente['sobrenome'] ?? '')) ?></strong>
                                </td>
                                <td>
                                    <small class="text-muted"><?= htmlspecialchars($paciente['cpf'] ?? '-') ?></small>
                                </td>
                                <td>
                                    <?php
                                    $idade = '';
                                    if (!empty($paciente['data_nascimento'])) {
                                        $nascimento = new DateTime($paciente['data_nascimento']);
                                        $hoje = new DateTime();
                                        $idade = $hoje->diff($nascimento)->y;
                                    }
                                    ?>
                                    <span class="badge bg-info"><?= $idade ? $idade . ' anos' : 'N/A' ?></span>
                                </td>
                                <td>
                                    <small><?= htmlspecialchars($paciente['procedimento_nome'] ?? '-') ?></small>
                                </td>
                                <td>
                                    <small><?= date('d/m/Y H:i', strtotime($paciente['data_atribuicao'])) ?></small>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = $paciente['status_atribuicao'] === 'ativo' ? 'bg-success' : 'bg-warning';
                                    $statusText = $paciente['status_atribuicao'] === 'ativo' ? 'Ativo' : 'Inativo';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td>
                                    <small class="text-muted"><?= htmlspecialchars($paciente['observacoes_atribuicao'] ?? '-') ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
 