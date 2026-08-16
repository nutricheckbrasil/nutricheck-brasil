<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-building me-2"></i>Meu Perfil - Instituição</h2>
        <button type="button" class="btn btn-primary" onclick="toggleEdit()">
            <i class="bi bi-pencil me-1"></i>Editar Perfil
        </button>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_anestesistas'] ?? 0 ?></h4>
                            <small>Nutricionistas</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_pacientes'] ?? 0 ?></h4>
                            <small>Pacientes</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-heart" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_agendamentos'] ?? 0 ?></h4>
                            <small>Agendamentos</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['agendamentos_hoje'] ?? 0 ?></h4>
                            <small>Hoje</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-calendar-day" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logo/Avatar da Instituição -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="me-4">
                            <?php if (!empty($instituicao['foto_path'])): ?>
                                <img src="<?= BASE_URL ?>/<?= $instituicao['foto_path'] ?>" 
                                     alt="Logo da Instituição" 
                                     class="rounded-circle" 
                                     style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #0d6efd;">
                            <?php else: ?>
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; border: 4px solid #0d6efd;">
                                    <i class="bi bi-building text-white" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="text-start">
                            <h3 class="mb-1"><?= htmlspecialchars($instituicao['nome'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="text-muted mb-2">
                                <i class="bi bi-building me-2"></i>Instituição de Saúde
                            </p>
                            <p class="text-muted mb-0">
                                <i class="bi bi-envelope me-2"></i><?= htmlspecialchars($instituicao['email'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <?php if (!empty($instituicao['telefone'])): ?>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-telephone me-2"></i><?= htmlspecialchars($instituicao['telefone'], ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações da Instituição -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-building me-2"></i>Informações da Instituição
                    </h5>
                </div>
                <div class="card-body">
                    <form id="formPerfil" method="POST" action="<?= BASE_URL ?>/perfil/update-instituicao" style="display: none;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label">Nome da Instituição *</label>
                                <input type="text" class="form-control" id="nome" name="nome" 
                                       value="<?= htmlspecialchars($instituicao['nome'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($instituicao['email'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" 
                                       value="<?= htmlspecialchars($instituicao['telefone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="cnpj" class="form-label">CNPJ</label>
                                <input type="text" class="form-control" id="cnpj" name="cnpj" 
                                       value="<?= htmlspecialchars($instituicao['cnpj'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            
                            <div class="col-12">
                                <label for="endereco" class="form-label">Endereço</label>
                                <textarea class="form-control" id="endereco" name="endereco" rows="3"><?= htmlspecialchars($instituicao['endereco'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                            
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i>Salvar Alterações
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="toggleEdit()">
                                        <i class="bi bi-x-circle me-1"></i>Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div id="viewPerfil">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nome da Instituição</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($instituicao['nome'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($instituicao['email'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Telefone</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($instituicao['telefone'] ?? 'Não informado', ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">CNPJ</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($instituicao['cnpj'] ?? 'Não informado', ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-bold">Endereço</label>
                                <p class="form-control-plaintext"><?= htmlspecialchars($instituicao['endereco'] ?? 'Não informado', ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-<?= $instituicao['status'] === 'ativo' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($instituicao['status']) ?>
                                    </span>
                                </p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Cadastrado em</label>
                                <p class="form-control-plaintext"><?= date('d/m/Y H:i', strtotime($instituicao['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-shield-check me-2"></i>Status da Conta
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-building text-primary me-3" style="font-size: 2rem;"></i>
                        <div>
                            <h6 class="mb-0">Instituição de Saúde</h6>
                            <small class="text-muted">Perfil Institucional</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Permissões</label>
                        <ul class="list-unstyled mb-0">
                            <li><i class="bi bi-check-circle text-success me-2"></i>Gerenciar Nutricionistas</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Gerenciar Pacientes</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Agendamentos</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i>Relatórios</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Para alterar a senha, entre em contato com o administrador do sistema.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEdit() {
    const form = document.getElementById('formPerfil');
    const view = document.getElementById('viewPerfil');
    
    if (form.style.display === 'none') {
        form.style.display = 'block';
        view.style.display = 'none';
    } else {
        form.style.display = 'none';
        view.style.display = 'block';
    }
}
</script>