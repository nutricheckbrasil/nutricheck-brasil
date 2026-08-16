<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
        <h4 class="mb-0">
            <i class="bi bi-plus-circle text-primary me-2"></i>Abrir Novo Chamado
        </h4>
        <a href="<?= BASE_URL ?>/ajuda" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Voltar
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Formulário de Abertura de Chamado -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header py-2">
                    <h6 class="mb-0">
                        <i class="bi bi-clipboard-plus me-2"></i>Formulário de Chamado
                    </h6>
                </div>
                <div class="card-body py-2">
                    <form method="POST" action="<?= BASE_URL ?>/ajuda/abrir-chamado">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="categoria" class="form-label">
                                    <i class="bi bi-tag me-1"></i>Categoria <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <option value="">Selecione uma categoria</option>
                                    <option value="problema_tecnico" <?= (isset($_POST['categoria']) && $_POST['categoria'] == 'problema_tecnico') ? 'selected' : '' ?>>Problema Técnico</option>
                                    <option value="duvida_clinica" <?= (isset($_POST['categoria']) && $_POST['categoria'] == 'duvida_clinica') ? 'selected' : '' ?>>Dúvida Clínica</option>
                                    <option value="duvida_sistema" <?= (isset($_POST['categoria']) && $_POST['categoria'] == 'duvida_sistema') ? 'selected' : '' ?>>Dúvida sobre o Sistema</option>
                                    <option value="sugestao" <?= (isset($_POST['categoria']) && $_POST['categoria'] == 'sugestao') ? 'selected' : '' ?>>Sugestão</option>
                                    <option value="outros" <?= (isset($_POST['categoria']) && $_POST['categoria'] == 'outros') ? 'selected' : '' ?>>Outros</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="urgencia" class="form-label">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Nível de Urgência <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="urgencia" name="urgencia" required>
                                    <option value="">Selecione a urgência</option>
                                    <option value="baixa" <?= (isset($_POST['urgencia']) && $_POST['urgencia'] == 'baixa') ? 'selected' : '' ?>>Baixa</option>
                                    <option value="normal" <?= (isset($_POST['urgencia']) && $_POST['urgencia'] == 'normal') ? 'selected' : '' ?>>Normal</option>
                                    <option value="alta" <?= (isset($_POST['urgencia']) && $_POST['urgencia'] == 'alta') ? 'selected' : '' ?>>Alta</option>
                                    <option value="urgente" <?= (isset($_POST['urgencia']) && $_POST['urgencia'] == 'urgente') ? 'selected' : '' ?>>Urgente</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="assunto" class="form-label">
                                <i class="bi bi-chat-text me-1"></i>Assunto <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="assunto" name="assunto" 
                                   value="<?= htmlspecialchars($_POST['assunto'] ?? '') ?>" 
                                   placeholder="Digite um assunto claro e objetivo" required>
                            <div class="form-text">
                                <i class="bi bi-lightbulb me-1"></i>
                                Ex: "Erro ao salvar dados do paciente" ou "Dúvida sobre protocolo de anestesia"
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="descricao" class="form-label">
                                <i class="bi bi-file-text me-1"></i>Descrição Detalhada <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="6" 
                                      placeholder="Descreva detalhadamente o problema, dúvida ou sugestão..." required><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Quanto mais detalhes você fornecer, mais rápido poderemos ajudá-lo.
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>/ajuda" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-2"></i>Abrir Chamado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Dicas para um Chamado Eficiente -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightbulb me-2"></i>Dicas para um Chamado Eficiente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><i class="bi bi-check-circle text-success me-2"></i>Seja Específico</h6>
                        <small class="text-muted">Descreva exatamente o que aconteceu e quando.</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="bi bi-check-circle text-success me-2"></i>Inclua Contexto</h6>
                        <small class="text-muted">Mencione qual tela, paciente ou situação específica.</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="bi bi-check-circle text-success me-2"></i>Escolha a Urgência Correta</h6>
                        <small class="text-muted">Urgente = impede o trabalho. Alta = afeta a rotina.</small>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="bi bi-check-circle text-success me-2"></i>Use a Categoria Adequada</h6>
                        <small class="text-muted">Isso ajuda nossa equipe a direcionar melhor o chamado.</small>
                    </div>
                </div>
            </div>
            
            <!-- Informações de Contato -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-telephone me-2"></i>Contato de Emergência
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Problema Crítico?</strong><br>
                        Se o sistema está impedindo seu trabalho, ligue diretamente:
                    </div>
                    
                    <a href="tel:+5551981066986" class="btn btn-warning w-100 mb-2">
                        <i class="bi bi-telephone me-2"></i>(51) 98106-6986
                    </a>
                    
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        Atendimento 24h para emergências técnicas
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validação em tempo real
document.getElementById('urgencia').addEventListener('change', function() {
    const urgencia = this.value;
    const descricao = document.getElementById('descricao');
    
    if (urgencia === 'urgente') {
        descricao.placeholder = 'ATENÇÃO: Descreva detalhadamente o problema urgente. Se possível, ligue diretamente para (11) 99999-9999.';
        descricao.style.borderColor = '#dc3545';
    } else {
        descricao.placeholder = 'Descreva detalhadamente o problema, dúvida ou sugestão...';
        descricao.style.borderColor = '';
    }
});
</script>
