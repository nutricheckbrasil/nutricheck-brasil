<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><?= htmlspecialchars($instituicao['nome']) ?></h2>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/instituicoes/edit/<?= $instituicao['id'] ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <a href="<?= BASE_URL ?>/instituicoes" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-<?= $instituicao['ativo'] ? 'success' : 'secondary' ?> text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $instituicao['ativo'] ? 'Ativo' : 'Inativo' ?></h4>
                            <small>Status</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-<?= $instituicao['ativo'] ? 'check-circle' : 'pause-circle' ?>" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_usuarios'] ?></h4>
                            <small>Usuários</small>
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
                            <h4 class="mb-0"><?= $stats['total_pacientes'] ?></h4>
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
                            <h4 class="mb-0"><?= $stats['total_anestesistas'] ?></h4>
                            <small>Nutricionistas</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-badge" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informações Detalhadas -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informações da Instituição
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6><i class="bi bi-building me-2"></i>Nome</h6>
                            <p class="text-muted"><?= htmlspecialchars($instituicao['nome']) ?></p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <h6><i class="bi bi-file-text me-2"></i>CNPJ</h6>
                            <p class="text-muted"><?= htmlspecialchars($instituicao['cnpj']) ?></p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <h6><i class="bi bi-envelope me-2"></i>Email</h6>
                            <p class="text-muted"><?= htmlspecialchars($instituicao['email']) ?></p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <h6><i class="bi bi-person me-2"></i>Responsável</h6>
                            <p class="text-muted"><?= htmlspecialchars($instituicao['responsavel'] ?? 'Não informado') ?></p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <h6><i class="bi bi-briefcase me-2"></i>Cargo/Função</h6>
                            <p class="text-muted"><?= htmlspecialchars($instituicao['cargo'] ?? 'Não informado') ?></p>
                        </div>
                        
                        
                        <?php if (!empty($instituicao['endereco'])): ?>
                        <div class="col-md-6 mb-3">
                            <h6><i class="bi bi-geo-alt me-2"></i>Endereço</h6>
                            <p class="text-muted"><?= htmlspecialchars($instituicao['endereco']) ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($instituicao['telefone'])): ?>
                        <div class="col-md-6 mb-3">
                            <h6><i class="bi bi-telephone me-2"></i>Telefone</h6>
                            <p class="text-muted"><?= htmlspecialchars($instituicao['telefone']) ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="col-md-6 mb-3">
                            <h6><i class="bi bi-calendar me-2"></i>Data de Criação</h6>
                            <p class="text-muted"><?= date('d/m/Y H:i', strtotime($instituicao['created_at'])) ?></p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <h6><i class="bi bi-clock me-2"></i>Última Atualização</h6>
                            <p class="text-muted">
                                <?= $instituicao['updated_at'] ? date('d/m/Y H:i', strtotime($instituicao['updated_at'])) : 'Nunca' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-qr-code me-2"></i>QR Code
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($qr_codes)): ?>
                        <div class="row">
                            <?php foreach ($qr_codes as $qr): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="text-center">
                                        <?php if (!empty($qr['arquivo_path'])): ?>
                                            <div class="mb-3">
                                                <img src="<?= BASE_URL ?>/<?= $qr['arquivo_path'] ?>" 
                                                     alt="QR Code" class="img-fluid" style="max-width: 200px;">
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">Código: <?= htmlspecialchars($qr['codigo']) ?></small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <a href="<?= BASE_URL ?>/<?= $qr['arquivo_path'] ?>" download class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-download"></i> Baixar QR Code
                                            </a>
                                        </div>
                                        
                                        <div class="alert alert-info">
                                            <small>
                                                <strong>URL de Cadastro:</strong><br>
                                                <code><?= htmlspecialchars($qr['url_publica']) ?></code>
                                            </small>
                                        </div>
                                        
                                        <div class="mt-2">
                                            <span class="badge bg-<?= $qr['ativo'] ? 'success' : 'secondary' ?>">
                                                <?= $qr['ativo'] ? 'Ativo' : 'Inativo' ?>
                                            </span>
                                        </div>
                                        
                                        <div class="mt-2">
                                            <a href="<?= BASE_URL ?>/instituicoes/regenerate-qr/<?= $instituicao['id'] ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-arrow-clockwise"></i> Regenerar QR Code
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-qr-code text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">Nenhum QR Code</h5>
                            <p class="text-muted">Esta instituição ainda não possui QR Codes gerados.</p>
                            <button type="button" class="btn btn-primary" onclick="gerarQR()">
                                <i class="bi bi-qr-code me-2"></i>Gerar QR Code
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Foto da Instituição -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-camera me-2"></i>Foto da Instituição
                    </h5>
                </div>
                <div class="card-body text-center">
                    <?php if (!empty($instituicao['foto_path'])): ?>
                        <img src="<?= BASE_URL ?>/<?= $instituicao['foto_path'] ?>" 
                             alt="Foto da Instituição" class="img-fluid" style="max-width: 100%;">
                    <?php else: ?>
                        <div class="text-muted">
                            <i class="bi bi-camera" style="font-size: 3rem;"></i>
                            <p class="mt-2">Nenhuma foto cadastrada</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-tools me-2"></i>Ações Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= BASE_URL ?>/instituicoes/edit/<?= $instituicao['id'] ?>" class="btn btn-outline-warning">
                            <i class="bi bi-pencil me-2"></i>Editar Instituição
                        </a>
                        
                        <?php if (empty($qr_codes)): ?>
                            <button type="button" class="btn btn-outline-success" onclick="gerarQR()">
                                <i class="bi bi-qr-code me-2"></i>Gerar QR Code
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-outline-info" onclick="regenerarQR()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Regenerar QR Code
                            </button>
                        <?php endif; ?>
                        
                        <a href="<?= BASE_URL ?>/nutricionistas?instituicao=<?= $instituicao['id'] ?>" class="btn btn-outline-primary">
                            <i class="bi bi-people me-2"></i>Ver Nutricionistas
                        </a>
                        
                        <a href="<?= BASE_URL ?>/pacientes?instituicao=<?= $instituicao['id'] ?>" class="btn btn-outline-success">
                            <i class="bi bi-person-heart me-2"></i>Ver Pacientes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function gerarQR() {
    if (confirm('Gerar QR Code para esta instituição?')) {
        fetch('<?= BASE_URL ?>/instituicoes/generate-qr', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                instituicao_id: <?= $instituicao['id'] ?>
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('QR Code gerado com sucesso!');
                location.reload();
            } else {
                alert('Erro ao gerar QR Code: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao gerar QR Code');
        });
    }
}

function regenerarQR() {
    if (confirm('Regenerar QR Code? O QR Code atual será substituído.')) {
        gerarQR();
    }
}
</script>