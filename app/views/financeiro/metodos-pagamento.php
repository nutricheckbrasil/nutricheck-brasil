<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/financeiro">Financeiro</a></li>
                    <li class="breadcrumb-item active">Métodos de Pagamento</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">
                <i class="bi bi-credit-card me-2"></i>Métodos de Pagamento
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Resumo do Plano</h5>
                </div>
                <div class="card-body">
                    <h4><?= htmlspecialchars($plano['nome']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($plano['descricao']) ?></p>
                    <h3 class="text-primary">
                        R$ <?= number_format($plano['preco_mensal'], 2, ',', '.') ?>
                        <small class="text-muted fs-6">/mês</small>
                    </h3>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Escolha o Método de Pagamento</h5>
                </div>
                <div class="card-body">
                    <form id="formPagamento">
                        <input type="hidden" name="plano_id" value="<?= $plano['id'] ?>">
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo" id="metodoPix" value="pix" checked>
                                <label class="form-check-label w-100" for="metodoPix">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-qr-code fs-3 me-3 text-primary"></i>
                                        <div>
                                            <strong>PIX</strong>
                                            <p class="text-muted small mb-0">Aprovação imediata</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Outros métodos podem ser adicionados aqui no futuro -->
                        <!--
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodo" id="metodoCartao" value="cartao_credito" disabled>
                                <label class="form-check-label w-100" for="metodoCartao">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-credit-card fs-3 me-3 text-muted"></i>
                                        <div>
                                            <strong>Cartão de Crédito</strong>
                                            <p class="text-muted small mb-0">Em breve</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        -->

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Após confirmar o pagamento, você receberá um QR Code PIX para realizar o pagamento.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle me-1"></i>Confirmar Pagamento
                            </button>
                            <a href="<?= BASE_URL ?>/financeiro" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-circle me-1"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Informações Importantes</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <i class="bi bi-shield-check text-success me-2"></i>
                            <strong>Seguro</strong><br>
                            <small class="text-muted">Pagamento processado pelo Mercado Pago</small>
                        </li>
                        <li class="mb-3">
                            <i class="bi bi-clock text-primary me-2"></i>
                            <strong>Renovação Automática</strong><br>
                            <small class="text-muted">Sua assinatura será renovada automaticamente</small>
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-arrow-counterclockwise text-info me-2"></i>
                            <strong>Cancelamento</strong><br>
                            <small class="text-muted">Você pode cancelar a qualquer momento</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de QR Code PIX -->
<div class="modal fade" id="modalPix" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pagamento via PIX</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrCodeContainer" class="mb-3">
                    <!-- QR Code será inserido aqui -->
                </div>
                <p class="text-muted">Escaneie o QR Code com o app do seu banco</p>
                <div class="alert alert-info">
                    <small>O pagamento será processado automaticamente após a confirmação.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('formPagamento').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Processando...';
    
    try {
        const response = await fetch('<?= BASE_URL ?>/financeiro/processar-pagamento', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            if (data.mercado_pago && data.mercado_pago.qr_code) {
                // Mostrar QR Code
                document.getElementById('qrCodeContainer').innerHTML = 
                    '<img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' + 
                    encodeURIComponent(data.mercado_pago.qr_code) + '" class="img-fluid">';
                
                const modal = new bootstrap.Modal(document.getElementById('modalPix'));
                modal.show();
            } else {
                alert('Pagamento registrado com sucesso! Aguardando confirmação.');
                window.location.href = '<?= BASE_URL ?>/financeiro';
            }
        } else {
            alert('Erro: ' + (data.error || 'Não foi possível processar o pagamento'));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao processar pagamento. Tente novamente.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});
</script>

