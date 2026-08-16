<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/financeiro">Financeiro</a></li>
                    <li class="breadcrumb-item active">Detalhes da Assinatura</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">
                <i class="bi bi-file-earmark-text me-2"></i>Detalhes da Assinatura
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= htmlspecialchars($assinatura['plano_nome']) ?></h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <span class="badge bg-<?= $assinatura['status'] === 'ativa' ? 'success' : 'warning' ?> ms-2">
                                <?= ucfirst($assinatura['status']) ?>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Valor Mensal:</strong>
                            <span class="ms-2">R$ <?= number_format($assinatura['preco_mensal'], 2, ',', '.') ?></span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Data de Início:</strong>
                            <span class="ms-2"><?= date('d/m/Y', strtotime($assinatura['data_inicio'])) ?></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Data de Expiração:</strong>
                            <span class="ms-2"><?= date('d/m/Y', strtotime($assinatura['data_expiracao'])) ?></span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Pacientes Incluídos:</strong>
                            <span class="ms-2"><?= $assinatura['pacientes_incluidos'] ?></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Pacientes Usados:</strong>
                            <span class="ms-2"><?= $assinatura['pacientes_usados'] ?></span>
                        </div>
                    </div>
                    
                    <div class="progress mb-3" style="height: 25px;">
                        <?php
                        $percentual = $assinatura['pacientes_incluidos'] > 0 
                            ? ($assinatura['pacientes_usados'] / $assinatura['pacientes_incluidos']) * 100 
                            : 0;
                        $percentual = min($percentual, 100);
                        ?>
                        <div class="progress-bar <?= $percentual >= 90 ? 'bg-danger' : ($percentual >= 70 ? 'bg-warning' : 'bg-success') ?>" 
                             role="progressbar" 
                             style="width: <?= $percentual ?>%"
                             aria-valuenow="<?= $percentual ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            <?= number_format($percentual, 1) ?>%
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="<?= BASE_URL ?>/financeiro/metodos-pagamento?plano_id=<?= $assinatura['plano_id'] ?>" class="btn btn-success">
                            <i class="bi bi-arrow-repeat me-1"></i>Renovar Assinatura
                        </a>
                        <a href="<?= BASE_URL ?>/financeiro" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Ações Rápidas</h6>
                </div>
                <div class="card-body">
                    <a href="<?= BASE_URL ?>/financeiro/historico" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-clock-history me-1"></i>Ver Histórico
                    </a>
                    <a href="<?= BASE_URL ?>/financeiro/metodos-pagamento?plano_id=<?= $assinatura['plano_id'] ?>" class="btn btn-outline-success w-100 mb-2">
                        <i class="bi bi-credit-card me-1"></i>Métodos de Pagamento
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

