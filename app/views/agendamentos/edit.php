<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-pencil-square me-2"></i>Editar Agendamento</h2>
                <a href="<?= BASE_URL ?>/agendamentos" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Botão para abrir modal de edição -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Agendamento #<?= $agendamento['id'] ?></h5>
                    <p class="card-text">
                        <strong>Paciente:</strong> <?= htmlspecialchars($agendamento['paciente_nome'] ?? 'N/A') ?><br>
                        <strong>Nutricionista:</strong> <?= htmlspecialchars($agendamento['anestesista_nome'] ?? 'N/A') ?><br>
                        <strong>Procedimento:</strong> <?= htmlspecialchars($agendamento['procedimento_nome'] ?? 'N/A') ?><br>
                        <strong>Data:</strong> <?= date('d/m/Y', strtotime($agendamento['data_agendamento'])) ?><br>
                        <strong>Horário:</strong> <?= date('H:i', strtotime($agendamento['hora_agendamento'])) ?>
                    </p>
                    <button type="button" class="btn btn-primary" onclick="abrirModalEdicao()">
                        <i class="bi bi-pencil me-1"></i>Editar Agendamento
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição -->
<div id="modalEdicaoOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div id="modalEdicaoContent" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); max-width: 600px; width: 90%; max-height: 90%; overflow-y: auto;">
        <!-- Header -->
        <div style="padding: 20px; border-bottom: 1px solid #dee2e6; background: #0d6efd; color: white; border-radius: 8px 8px 0 0;">
            <h5 style="margin: 0; display: flex; align-items: center;">
                <i class="bi bi-pencil-square me-2"></i>Editar Agendamento
                <button onclick="fecharModalEdicao()" style="margin-left: auto; background: none; border: none; color: white; font-size: 24px; cursor: pointer; padding: 0; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">&times;</button>
            </h5>
        </div>
        
        <!-- Body -->
        <div style="padding: 20px;">
            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <h6><i class="bi bi-exclamation-triangle me-1"></i>Corrija os seguintes erros:</h6>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <form method="POST" id="formEdicaoAgendamento">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <!-- Seleção de Paciente -->
                    <div>
                        <label for="paciente_id_modal" style="display: block; margin-bottom: 5px; font-weight: bold;">
                            Paciente <span style="color: red;">*</span>
                        </label>
                        <select id="paciente_id_modal" name="paciente_id" required style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                            <option value="">Selecione um paciente</option>
                            <?php foreach ($pacientes as $paciente): ?>
                            <?php
                            $idade = '';
                            if ($paciente['data_nascimento']) {
                                $nasc = new DateTime($paciente['data_nascimento']);
                                $hoje = new DateTime();
                                $idade = $hoje->diff($nasc)->y;
                            }
                            $display = $paciente['nome'];
                            if ($idade) {
                                $display .= " ({$idade} anos, " . ucfirst($paciente['sexo']) . ")";
                            }
                            ?>
                            <option value="<?= $paciente['id'] ?>" 
                                    <?= $values['paciente_id'] == $paciente['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($display) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Seleção de Nutricionista -->
                    <div>
                        <label for="anestesista_id_modal" style="display: block; margin-bottom: 5px; font-weight: bold;">
                            Nutricionista <span style="color: red;">*</span>
                        </label>
                        <select id="anestesista_id_modal" name="anestesista_id" required style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                            <option value="">Selecione um nutricionista</option>
                            <?php foreach ($anestesistas as $anestesista): ?>
                            <option value="<?= $anestesista['id'] ?>" 
                                    <?= $values['anestesista_id'] == $anestesista['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($anestesista['nome']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Seleção de Procedimento -->
                    <div>
                        <label for="procedimento_id_modal" style="display: block; margin-bottom: 5px; font-weight: bold;">
                            Procedimento <span style="color: red;">*</span>
                        </label>
                        <select id="procedimento_id_modal" name="procedimento_id" required style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                            <option value="">Selecione um procedimento</option>
                            <?php foreach ($procedimentos as $procedimento): ?>
                            <option value="<?= $procedimento['id'] ?>" 
                                    <?= $values['procedimento_id'] == $procedimento['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($procedimento['nome']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status_modal" style="display: block; margin-bottom: 5px; font-weight: bold;">
                            Status
                        </label>
                        <select id="status_modal" name="status" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                            <option value="agendado" <?= $values['status'] === 'agendado' ? 'selected' : '' ?>>Agendado</option>
                            <option value="confirmado" <?= $values['status'] === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                            <option value="em_andamento" <?= $values['status'] === 'em_andamento' ? 'selected' : '' ?>>Em Andamento</option>
                            <option value="concluido" <?= $values['status'] === 'concluido' ? 'selected' : '' ?>>Concluído</option>
                            <option value="cancelado" <?= $values['status'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                        </select>
                    </div>

                    <!-- Data do Agendamento -->
                    <div>
                        <label for="data_agendamento_modal" style="display: block; margin-bottom: 5px; font-weight: bold;">
                            Data <span style="color: red;">*</span>
                        </label>
                        <input type="date" 
                               id="data_agendamento_modal" 
                               name="data_agendamento" 
                               value="<?= htmlspecialchars($values['data_agendamento']) ?>"
                               required 
                               style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                    </div>

                    <!-- Horário do Agendamento -->
                    <div>
                        <label for="hora_agendamento_modal" style="display: block; margin-bottom: 5px; font-weight: bold;">
                            Horário <span style="color: red;">*</span>
                        </label>
                        <input type="time" 
                               id="hora_agendamento_modal" 
                               name="hora_agendamento" 
                               value="<?= htmlspecialchars($values['hora_agendamento']) ?>"
                               required 
                               style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                    </div>
                </div>

                <!-- Observações -->
                <div style="margin-top: 15px;">
                    <label for="observacoes_modal" style="display: block; margin-bottom: 5px; font-weight: bold;">
                        Observações
                    </label>
                    <textarea id="observacoes_modal" 
                              name="observacoes" 
                              rows="3" 
                              placeholder="Informações adicionais sobre o agendamento..."
                              style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px; resize: vertical;"><?= htmlspecialchars($values['observacoes']) ?></textarea>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div style="padding: 20px; border-top: 1px solid #dee2e6; display: flex; gap: 10px; justify-content: flex-end;">
            <button type="button" class="btn btn-secondary" onclick="fecharModalEdicao()">
                <i class="bi bi-x-circle me-1"></i>Cancelar
            </button>
            <button type="button" class="btn btn-primary" onclick="salvarEdicao()">
                <i class="bi bi-check-circle me-1"></i>Salvar Alterações
            </button>
        </div>
    </div>
</div>

<script>
function abrirModalEdicao() {
    document.getElementById('modalEdicaoOverlay').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function fecharModalEdicao() {
    document.getElementById('modalEdicaoOverlay').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function salvarEdicao() {
    // Validar campos obrigatórios
    const paciente = document.getElementById('paciente_id_modal').value;
    const anestesista = document.getElementById('anestesista_id_modal').value;
    const procedimento = document.getElementById('procedimento_id_modal').value;
    const data = document.getElementById('data_agendamento_modal').value;
    const hora = document.getElementById('hora_agendamento_modal').value;
    
    if (!paciente || !anestesista || !procedimento || !data || !hora) {
        alert('Por favor, preencha todos os campos obrigatórios.');
        return;
    }
    
    // Enviar formulário
    document.getElementById('formEdicaoAgendamento').submit();
}

// Fechar modal ao clicar fora
document.getElementById('modalEdicaoOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModalEdicao();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('modalEdicaoOverlay').style.display === 'block') {
        fecharModalEdicao();
    }
});
</script>