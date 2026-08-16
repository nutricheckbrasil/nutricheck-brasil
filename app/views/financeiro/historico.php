<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="bi bi-clock-history me-2"></i>Histórico de Pagamentos
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-primary shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>Todos os Pagamentos
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($pagamentos)): ?>
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-2"></i>
                            Nenhum pagamento registrado ainda.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="bi bi-calendar-event me-1"></i>Data</th>
                                        <th><i class="bi bi-tag me-1"></i>Plano</th>
                                        <th><i class="bi bi-currency-dollar me-1"></i>Valor</th>
                                        <th><i class="bi bi-credit-card me-1"></i>Método</th>
                                        <th><i class="bi bi-info-circle me-1"></i>Status</th>
                                        <th><i class="bi bi-check-circle me-1"></i>Data Pagamento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pagamentos as $pagamento): ?>
                                        <tr>
                                            <td>
                                                <i class="bi bi-calendar3 me-2 text-muted"></i>
                                                <strong><?= date('d/m/Y', strtotime($pagamento['created_at'])) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= date('H:i', strtotime($pagamento['created_at'])) ?></small>
                                            </td>
                                            <td>
                                                <i class="bi bi-tag-fill me-2 text-primary"></i>
                                                <strong><?= htmlspecialchars($pagamento['plano_nome']) ?></strong>
                                            </td>
                                            <td>
                                                <span class="text-success fs-5 fw-bold">
                                                    R$ <?= number_format($pagamento['valor'], 2, ',', '.') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <i class="bi bi-<?= $pagamento['metodo_pagamento'] === 'pix' ? 'qr-code' : 'credit-card' ?> me-1 text-primary"></i>
                                                <span class="text-dark"><?= ucfirst(str_replace('_', ' ', $pagamento['metodo_pagamento'])) ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= getStatusColor($pagamento['status']) ?> fs-6 px-3 py-2">
                                                    <i class="bi bi-<?= $pagamento['status'] === 'aprovado' ? 'check-circle' : ($pagamento['status'] === 'pendente' ? 'clock' : ($pagamento['status'] === 'rejeitado' ? 'x-circle' : 'info-circle')) ?> me-1"></i>
                                                    <?= ucfirst($pagamento['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($pagamento['data_pagamento']): ?>
                                                    <i class="bi bi-check-circle-fill me-2 text-success"></i>
                                                    <strong><?= date('d/m/Y', strtotime($pagamento['data_pagamento'])) ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?= date('H:i', strtotime($pagamento['data_pagamento'])) ?></small>
                                                <?php else: ?>
                                                    <span class="text-muted">
                                                        <i class="bi bi-dash-circle me-1"></i>Não pago
                                                    </span>
                                                <?php endif; ?>
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
    
    <div class="row mt-3">
        <div class="col-12">
            <a href="<?= BASE_URL ?>/financeiro" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Voltar
            </a>
        </div>
    </div>
</div>

<?php
// Usar a função getStatusColor do config.php se não existir
if (!function_exists('getStatusColor')) {
    function getStatusColor($status) {
        $colors = [
            'pendente' => 'warning',
            'processando' => 'info',
            'aprovado' => 'success',
            'rejeitado' => 'danger',
            'cancelado' => 'secondary',
            'reembolsado' => 'dark'
        ];
        return $colors[$status] ?? 'secondary';
    }
}
?>

