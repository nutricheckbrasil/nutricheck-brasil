<?php
// Verificar se há assinatura ativa
$tem_assinatura = !empty($assinatura);
$pacientes_gratis_restantes = 10 - ($stats['pacientes_gratis_usados'] ?? 0);
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="bi bi-credit-card me-2"></i>Financeiro
            </h1>
            <p class="text-muted">Gerencie sua assinatura e pagamentos</p>
        </div>
    </div>

    <!-- 1. DASHBOARD DE ESTATÍSTICAS (PRIMEIRO) -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center border-2 border-success shadow" style="background: linear-gradient(135deg, #f8fff9 0%, #ffffff 100%);">
                <div class="card-body py-3">
                    <div class="mb-2">
                        <i class="bi bi-currency-dollar text-success" style="font-size: 1.8rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.75rem;">Total Pago</h6>
                    <h5 class="text-success mb-0 fw-bold">R$ <?= number_format($stats['total_pago'], 2, ',', '.') ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-2 border-primary shadow" style="background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%);">
                <div class="card-body py-3">
                    <div class="mb-2">
                        <i class="bi bi-receipt text-primary" style="font-size: 1.8rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.75rem;">Pagamentos</h6>
                    <h5 class="text-primary mb-0 fw-bold"><?= $stats['total_pagamentos'] ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-2 border-info shadow" style="background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);">
                <div class="card-body py-3">
                    <div class="mb-2">
                        <i class="bi bi-people text-info" style="font-size: 1.8rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.75rem;">Pacientes Usados</h6>
                    <h5 class="text-info mb-0 fw-bold"><?= $stats['pacientes_usados'] ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-2 border-warning shadow" style="background: linear-gradient(135deg, #fffbf0 0%, #ffffff 100%);">
                <div class="card-body py-3">
                    <div class="mb-2">
                        <i class="bi bi-gift text-warning" style="font-size: 1.8rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1 fw-bold text-uppercase" style="font-size: 0.75rem;">Pacientes Grátis</h6>
                    <h5 class="text-warning mb-0 fw-bold"><?= $stats['pacientes_gratis_usados'] ?> / 10</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. ESCOLHA SEU PLANO (SEGUNDO - COM BOTÃO DE RECOLHER) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-2 border-primary shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center" 
                     style="cursor: pointer; transition: all 0.3s;" 
                     onmouseover="this.style.backgroundColor='#0056b3'" 
                     onmouseout="this.style.backgroundColor='#0d6efd'"
                     onclick="togglePlanos()"
                     data-bs-toggle="collapse" 
                     data-bs-target="#planosCollapse">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-tag me-2"></i>Escolha seu Plano
                        <small class="ms-2 opacity-75" style="font-size: 0.7rem;">(Clique para expandir/recolher)</small>
                    </h5>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-light text-primary me-2" id="planosStatus">Recolhido</span>
                        <i class="bi bi-chevron-down fs-4" id="planosIcon" style="transition: transform 0.3s;"></i>
                    </div>
                </div>
                <div class="collapse" id="planosCollapse">
                    <div class="card-body">
                        <div class="row g-4">
                            <?php foreach ($planos as $plano): ?>
                                <div class="col-md-4">
                                    <div class="card h-100 <?= $tem_assinatura && $assinatura['plano_id'] == $plano['id'] ? 'border-3 border-success shadow-lg' : 'border' ?>" 
                                         style="<?= $tem_assinatura && $assinatura['plano_id'] == $plano['id'] ? 'background: linear-gradient(135deg, #f0fff4 0%, #ffffff 100%);' : '' ?>">
                                        <?php if ($tem_assinatura && $assinatura['plano_id'] == $plano['id']): ?>
                                            <div class="card-header bg-success text-white text-center fw-bold">
                                                <i class="bi bi-check-circle-fill me-1"></i>PLANO ATUAL
                                            </div>
                                        <?php endif; ?>
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><?= htmlspecialchars($plano['nome']) ?></h5>
                                            <p class="text-muted small"><?= htmlspecialchars($plano['descricao']) ?></p>
                                            
                                            <div class="mb-3">
                                                <h3 class="text-primary">
                                                    R$ <?= number_format($plano['preco_mensal'], 2, ',', '.') ?>
                                                    <small class="text-muted fs-6">/mês</small>
                                                </h3>
                                            </div>
                                            
                                            <ul class="list-unstyled mb-3 flex-grow-1">
                                                <li class="mb-2">
                                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                    Até <?= $plano['pacientes_incluidos'] ?> pacientes incluídos
                                                </li>
                                                <?php
                                                $recursos = json_decode($plano['recursos'] ?? '[]', true);
                                                foreach ($recursos as $recurso):
                                                ?>
                                                    <li class="mb-2">
                                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                        <?= htmlspecialchars($recurso) ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                            
                                            <div class="mt-auto">
                                                <?php if ($tem_assinatura && $assinatura['plano_id'] == $plano['id']): ?>
                                                    <button class="btn btn-outline-primary w-100" disabled>
                                                        <i class="bi bi-check-circle me-1"></i>Plano Atual
                                                    </button>
                                                <?php else: ?>
                                                    <a href="<?= BASE_URL ?>/financeiro/metodos-pagamento?plano_id=<?= $plano['id'] ?>" class="btn btn-primary w-100">
                                                        <i class="bi bi-arrow-right-circle me-1"></i>Escolher Plano
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. ASSINATURA ATUAL (TERCEIRO - FOCO NELA) -->
    <?php if ($tem_assinatura): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-primary shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-check-circle me-2"></i>Assinatura Atual
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="text-primary mb-3"><?= htmlspecialchars($assinatura['plano_nome']) ?></h3>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <i class="bi bi-currency-dollar me-2 text-muted"></i>
                                            <strong>Valor:</strong> 
                                            <span class="text-primary fs-5">R$ <?= number_format($assinatura['preco_mensal'], 2, ',', '.') ?>/mês</span>
                                        </p>
                                        <p class="mb-2">
                                            <i class="bi bi-calendar-check me-2 text-muted"></i>
                                            <strong>Expira em:</strong> 
                                            <span class="text-dark"><?= date('d/m/Y', strtotime($assinatura['data_expiracao'])) ?></span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <i class="bi bi-info-circle me-2 text-muted"></i>
                                            <strong>Status:</strong> 
                                            <span class="badge bg-<?= $assinatura['status'] === 'ativa' ? 'success' : 'warning' ?> fs-6">
                                                <?= ucfirst($assinatura['status']) ?>
                                            </span>
                                        </p>
                                        <p class="mb-0">
                                            <i class="bi bi-people me-2 text-muted"></i>
                                            <strong>Pacientes usados:</strong> 
                                            <span class="text-dark"><?= $assinatura['pacientes_usados'] ?> / <?= $assinatura['pacientes_incluidos'] ?></span>
                                        </p>
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
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="<?= BASE_URL ?>/financeiro/assinatura" class="btn btn-primary mb-2 w-100">
                                    <i class="bi bi-eye me-1"></i>Ver Detalhes
                                </a>
                                <a href="<?= BASE_URL ?>/financeiro/metodos-pagamento?plano_id=<?= $assinatura['plano_id'] ?>" class="btn btn-success w-100">
                                    <i class="bi bi-arrow-repeat me-1"></i>Renovar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Aviso sobre pacientes gratuitos (se não tiver assinatura) -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info border-0 shadow-sm">
                    <h5 class="alert-heading">
                        <i class="bi bi-info-circle me-2"></i>Período Gratuito
                    </h5>
                    <p class="mb-0">
                        Você está usando o período gratuito. Você pode cadastrar até <strong>10 pacientes gratuitamente</strong>.
                        <br>
                        <strong>Pacientes usados:</strong> <?= $stats['pacientes_gratis_usados'] ?> / 10
                        <?php if ($pacientes_gratis_restantes > 0): ?>
                            <br>
                            <strong>Restantes:</strong> <?= $pacientes_gratis_restantes ?>
                        <?php else: ?>
                            <br>
                            <span class="text-danger"><strong>Limite atingido!</strong> Escolha um plano para continuar.</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Botão para Histórico (em vez de tabela) -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="mb-3">Histórico de Pagamentos</h5>
                    <p class="text-muted mb-3">Visualize todos os seus pagamentos e transações</p>
                    <a href="<?= BASE_URL ?>/financeiro/historico" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-clock-history me-2"></i>Ver Histórico Completo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePlanos() {
    const icon = document.getElementById('planosIcon');
    const status = document.getElementById('planosStatus');
    const collapse = document.getElementById('planosCollapse');
    
    // Aguardar a animação do collapse
    setTimeout(() => {
        if (collapse.classList.contains('show')) {
            icon.classList.remove('bi-chevron-down');
            icon.classList.add('bi-chevron-up');
            icon.style.transform = 'rotate(180deg)';
            status.textContent = 'Expandido';
        } else {
            icon.classList.remove('bi-chevron-up');
            icon.classList.add('bi-chevron-down');
            icon.style.transform = 'rotate(0deg)';
            status.textContent = 'Recolhido';
        }
    }, 100);
}

// Inicializar estado do ícone
document.addEventListener('DOMContentLoaded', function() {
    const collapse = document.getElementById('planosCollapse');
    const icon = document.getElementById('planosIcon');
    const status = document.getElementById('planosStatus');
    
    // Por padrão, começar recolhido
    if (!collapse.classList.contains('show')) {
        icon.classList.remove('bi-chevron-up');
        icon.classList.add('bi-chevron-down');
        icon.style.transform = 'rotate(0deg)';
        status.textContent = 'Recolhido';
    } else {
        icon.style.transform = 'rotate(180deg)';
        status.textContent = 'Expandido';
    }
});
</script>


