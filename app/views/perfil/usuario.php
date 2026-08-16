<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-person-circle me-2"></i>Meu Perfil - <?= ucfirst($usuario['perfil_nome']) ?></h2>
        <button type="button" class="btn btn-primary" onclick="toggleEdit()">
            <i class="bi bi-pencil me-1"></i>Editar Perfil
        </button>
    </div>

    <!-- Estatísticas baseadas no perfil -->
    <?php if ($usuario['perfil_id'] == 3): // Nutricionista ?>
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_pacientes'] ?? 0 ?></h4>
                            <small>Meus Pacientes</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-heart" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['agendamentos_hoje'] ?? 0 ?></h4>
                            <small>Agendamentos Hoje</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-calendar-day" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['agendamentos_semana'] ?? 0 ?></h4>
                            <small>Esta Semana</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-calendar-week" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: // Outros perfis ?>
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_acessos'] ?? 0 ?></h4>
                            <small>Total de Acessos</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['ultimo_acesso'] ? date('d/m/Y', strtotime($stats['ultimo_acesso'])) : 'Nunca' ?></h4>
                            <small>Último Acesso</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-clock-history" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Foto/Avatar do Usuário -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center">
                        <?php
                        $color = 'primary';
                        $icon = 'bi-building';
                        if ($usuario['perfil_id'] == 3) {
                            $color = 'success';
                            $icon = 'bi-person-badge';
                        } elseif ($usuario['perfil_id'] == 4) {
                            $color = 'info';
                            $icon = 'bi-person-heart';
                        } elseif ($usuario['perfil_id'] == 5) {
                            $color = 'warning';
                            $icon = 'bi-person-gear';
                        }
                        ?>
                        
                        <?php if (!empty($usuario['foto_path'])): ?>
                            <img src="<?= BASE_URL ?>/<?= $usuario['foto_path'] ?>" 
                                 alt="Foto do Usuário" 
                                 class="rounded-circle me-4"
                                 style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #<?= $color === 'primary' ? '0d6efd' : ($color === 'success' ? '198754' : ($color === 'info' ? '0dcaf0' : 'ffc107')) ?>;">
                        <?php else: ?>
                            <div class="bg-<?= $color ?> rounded-circle d-flex align-items-center justify-content-center me-4" 
                                 style="width: 120px; height: 120px; border: 4px solid #<?= $color === 'primary' ? '0d6efd' : ($color === 'success' ? '198754' : ($color === 'info' ? '0dcaf0' : 'ffc107')) ?>;">
                                <i class="bi <?= $icon ?> text-white" style="font-size: 3rem;"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="text-start">
                            <h3 class="mb-1"><?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="text-muted mb-2">
                                <i class="bi <?= $icon ?> me-2"></i><?= htmlspecialchars($usuario['perfil_descricao'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <p class="text-muted mb-1">
                                <i class="bi bi-envelope me-2"></i><?= htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <?php if (!empty($usuario['telefone'])): ?>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-telephone me-2"></i><?= htmlspecialchars($usuario['telefone'], ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($usuario['perfil_id'] == 3 && !empty($usuario['crm'])): // Nutricionista ?>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-award me-2"></i>CRN: <?= htmlspecialchars($usuario['crm'], ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário de Edição -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person me-2"></i>Informações Pessoais
                    </h5>
                </div>
                <div class="card-body">
                    <form id="formPerfil" method="POST" action="<?= BASE_URL ?>/perfil/update-usuario" style="display: none;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control" id="nome" name="nome" 
                                       value="<?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" 
                                       value="<?= htmlspecialchars($usuario['telefone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            
                            <?php if ($usuario['perfil_id'] == 3): // Nutricionista ?>
                            <div class="col-md-6">
                                <label for="crm" class="form-label">CRN</label>
                                <input type="text" class="form-control" id="crm" name="crm" 
                                       value="<?= htmlspecialchars($usuario['crm'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <?php endif; ?>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="bi bi-check-circle me-1"></i>Salvar Alterações
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="toggleEdit()">
                                    <i class="bi bi-x-circle me-1"></i>Cancelar
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Visualização dos dados (modo leitura) -->
                    <div id="viewMode">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nome Completo</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Telefone</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($usuario['telefone'] ?? 'Não informado', ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            
                            <?php if ($usuario['perfil_id'] == 3): // Nutricionista ?>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">CRN</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($usuario['crm'] ?? 'Não informado', ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Perfil</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-primary"><?= htmlspecialchars($usuario['perfil_descricao'], ENT_QUOTES, 'UTF-8') ?></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Instituição</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($usuario['instituicao_nome'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-<?= $usuario['status'] === 'ativo' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($usuario['status']) ?>
                                    </span>
                                </p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Cadastrado em</label>
                                <p class="form-control-plaintext"><?= date('d/m/Y H:i', strtotime($usuario['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações Adicionais -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <i class="bi <?= $icon ?> text-<?= $color ?> me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h6 class="mb-0"><?= htmlspecialchars($usuario['perfil_descricao'], ENT_QUOTES, 'UTF-8') ?></h6>
                                    <small class="text-muted">Perfil: <?= htmlspecialchars($usuario['perfil_nome'], ENT_QUOTES, 'UTF-8') ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <h6 class="mb-2">Funcionalidades Disponíveis</h6>
                            <ul class="list-unstyled mb-0">
                                <?php if ($usuario['perfil_id'] == 3): // Nutricionista ?>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Gerenciar Pacientes</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Agendamentos</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Relatórios</li>
                                <?php elseif ($usuario['perfil_id'] == 4): // Paciente ?>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Visualizar Meus Dados</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Agendamentos</li>
                                <?php elseif ($usuario['perfil_id'] == 5): // Funcionário ?>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Gerenciar Pacientes</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Agendamentos</li>
                                <li><i class="bi bi-check-circle text-success me-2"></i>Relatórios</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEdit() {
    const form = document.getElementById('formPerfil');
    const viewMode = document.getElementById('viewMode');
    
    if (form.style.display === 'none') {
        form.style.display = 'block';
        viewMode.style.display = 'none';
    } else {
        form.style.display = 'none';
        viewMode.style.display = 'block';
    }
}
</script>