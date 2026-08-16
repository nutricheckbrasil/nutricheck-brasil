<div class="container-fluid">
    <!-- Header com estatísticas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4><i class="bi bi-calendar-check me-2"></i>Agendamentos</h4>
                <button type="button" class="btn btn-primary" onclick="abrirModal()">
                    <i class="bi bi-plus-circle me-1"></i>Novo Agendamento
                </button>
            </div>
            
            <!-- Cards de estatísticas -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0"><?= $stats['hoje'] ?></h6>
                                    <small class="mb-0">Hoje</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-calendar-day fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0"><?= $stats['semana'] ?></h6>
                                    <small class="mb-0">Esta Semana</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-calendar-week fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0"><?= $stats['concluidos'] ?></h6>
                                    <small class="mb-0">Concluídos</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-check-circle fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-0"><?= $stats['cancelados'] ?></h6>
                                    <small class="mb-0">Cancelados</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-x-circle fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header py-2">
                    <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filtros</h6>
                </div>
                <div class="card-body py-2">
                    <form method="GET" class="row g-2 align-items-end">
                        <?php if (!empty($instituicoes)): ?>
                        <div class="col-md-2">
                            <label for="instituicao_id" class="form-label small">Instituição</label>
                            <select class="form-select form-select-sm" id="instituicao_id" name="instituicao_id">
                                <option value="">Todas</option>
                                <?php foreach ($instituicoes as $instituicao): ?>
                                <option value="<?= $instituicao['id'] ?>" <?= ($filtros['instituicao_id'] ?? '') == $instituicao['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($instituicao['nome'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-2">
                            <label for="data" class="form-label small">Data</label>
                            <input type="date" class="form-control form-control-sm" id="data" name="data" value="<?= htmlspecialchars($filtros['data'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label small">Status</label>
                            <select class="form-select form-select-sm" id="status" name="status">
                                <option value="">Todos</option>
                                <option value="agendado" <?= ($filtros['status'] ?? '') === 'agendado' ? 'selected' : '' ?>>Agendado</option>
                                <option value="confirmado" <?= ($filtros['status'] ?? '') === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                                <option value="em_andamento" <?= ($filtros['status'] ?? '') === 'em_andamento' ? 'selected' : '' ?>>Em Andamento</option>
                                <option value="concluido" <?= ($filtros['status'] ?? '') === 'concluido' ? 'selected' : '' ?>>Concluído</option>
                                <option value="cancelado" <?= ($filtros['status'] ?? '') === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="anestesista_id" class="form-label small">Nutricionista</label>
                            <select class="form-select form-select-sm" id="anestesista_id" name="anestesista_id">
                                <option value="">Todos</option>
                                <?php foreach ($anestesistas as $anestesista): ?>
                                <option value="<?= $anestesista['id'] ?>" <?= ($filtros['anestesista_id'] ?? '') == $anestesista['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($anestesista['nome'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-search me-1"></i>Filtrar
                            </button>
                            <a href="<?= BASE_URL ?>/agendamentos" class="btn btn-outline-secondary btn-sm ms-1">
                                <i class="bi bi-arrow-clockwise me-1"></i>Limpar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de agendamentos -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header py-2">
                    <h6 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>Agendamentos
                        <span class="badge bg-primary ms-2"><?= count($agendamentos) ?></span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($agendamentos)): ?>
                    <div class="text-center py-3">
                        <i class="bi bi-calendar-x fs-2 text-muted"></i>
                        <h6 class="text-muted mt-2">Nenhum agendamento encontrado</h6>
                        <p class="text-muted small">Tente ajustar os filtros ou criar um novo agendamento.</p>
                        <button type="button" class="btn btn-primary btn-sm" onclick="abrirModal()">
                            <i class="bi bi-plus-circle me-1"></i>Novo Agendamento
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Paciente</th>
                                    <th>Nutricionista</th>
                                    <th>Procedimento</th>
                                    <th>Data</th>
                                    <th>Horário</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($agendamentos as $agendamento): ?>
                                <?php
                                // Calcular idade do paciente
                                $idade = '';
                                if ($agendamento['data_nascimento']) {
                                    $nasc = new DateTime($agendamento['data_nascimento']);
                                    $hoje = new DateTime();
                                    $idade = $hoje->diff($nasc)->y;
                                }
                                
                                // Status badge
                                $status_classes = [
                                    'agendado' => 'bg-warning',
                                    'confirmado' => 'bg-info',
                                    'em_andamento' => 'bg-primary',
                                    'concluido' => 'bg-success',
                                    'cancelado' => 'bg-danger'
                                ];
                                
                                $status_labels = [
                                    'agendado' => 'Agendado',
                                    'confirmado' => 'Confirmado',
                                    'em_andamento' => 'Em Andamento',
                                    'concluido' => 'Concluído',
                                    'cancelado' => 'Cancelado'
                                ];
                                ?>
                                <tr>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($agendamento['paciente_nome'], ENT_QUOTES, 'UTF-8') ?></strong>
                                            <?php if ($idade): ?>
                                            <br><small class="text-muted"><?= $idade ?> anos, <?= ucfirst($agendamento['sexo']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <i class="bi bi-person-badge me-1"></i>
                                            <?= htmlspecialchars($agendamento['anestesista_nome'], ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <i class="bi bi-clipboard-pulse me-1"></i>
                                            <?= htmlspecialchars($agendamento['procedimento_nome'], ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <i class="bi bi-calendar-date me-1"></i>
                                            <?= date('d/m/Y', strtotime($agendamento['data_agendamento'])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <i class="bi bi-clock me-1"></i>
                                            <?= date('H:i', strtotime($agendamento['hora_agendamento'])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?= $status_classes[$agendamento['status']] ?? 'bg-secondary' ?>">
                                            <?= $status_labels[$agendamento['status']] ?? ucfirst($agendamento['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= BASE_URL ?>/agendamentos/edit/<?= $agendamento['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" 
                                               class="btn btn-sm btn-outline-danger" 
                                               title="Excluir"
                                                    onclick="mostrarModalExclusao(<?= $agendamento['id'] ?>, '<?= htmlspecialchars($agendamento['paciente_nome'], ENT_QUOTES, 'UTF-8') ?>', '<?= date('d/m/Y H:i', strtotime($agendamento['data_agendamento'] . ' ' . $agendamento['hora_agendamento'])) ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
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
</div>

<!-- Modal customizado sem Bootstrap -->
<div id="modalOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div id="modalContent" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); max-width: 800px; width: 90%; max-height: 90%; overflow-y: auto;">
        <div style="padding: 20px; border-bottom: 1px solid #dee2e6; background: #0d6efd; color: white; border-radius: 8px 8px 0 0;">
            <h5 style="margin: 0; display: flex; align-items: center;">
                <i class="bi bi-plus-circle me-2"></i>Novo Agendamento
                <button onclick="fecharModal()" style="margin-left: auto; background: none; border: none; color: white; font-size: 24px; cursor: pointer; padding: 0; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">&times;</button>
            </h5>
        </div>
        
        <form method="POST" action="<?= BASE_URL ?>/agendamentos/create" style="padding: 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <!-- Seleção de Paciente -->
                <div>
                    <label for="paciente_id" style="display: block; margin-bottom: 5px; font-weight: bold;">
                        Paciente <span style="color: red;">*</span>
                    </label>
                    <select id="paciente_id" name="paciente_id" required style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;" onchange="console.log('🎯 Evento onchange disparado!'); preencherDadosPaciente()">
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
                                data-procedimento-id="<?= $paciente['procedimento_id'] ?? '' ?>"
                                data-procedimento-nome="<?= htmlspecialchars($paciente['procedimento_nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                data-anestesista-id="<?= $paciente['anestesista_id'] ?? '' ?>"
                                data-anestesista-nome="<?= htmlspecialchars($paciente['anestesista_nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                data-data-procedimento="<?= $paciente['data_procedimento'] ?? '' ?>">
                            <?= htmlspecialchars($display, ENT_QUOTES, 'UTF-8') ?>
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
                        <option value="<?= $anestesista['id'] ?>">
                            <?= htmlspecialchars($anestesista['nome'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Seleção de Procedimento -->
                <div>
                    <label for="procedimento_id" style="display: block; margin-bottom: 5px; font-weight: bold;">
                        Procedimento <span style="color: red;">*</span>
                    </label>
                    <select id="procedimento_id" name="procedimento_id" required style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                        <option value="">Selecione um procedimento</option>
                        <?php foreach ($procedimentos as $procedimento): ?>
                        <option value="<?= $procedimento['id'] ?>">
                            <?= htmlspecialchars($procedimento['nome'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Data do Agendamento -->
                <div>
                    <label for="data_agendamento" style="display: block; margin-bottom: 5px; font-weight: bold;">
                        Data <span style="color: red;">*</span>
                    </label>
                    <input type="date" 
                           id="data_agendamento" 
                           name="data_agendamento" 
                           min="<?= date('Y-m-d') ?>"
                           value="<?= date('Y-m-d') ?>"
                           required
                           style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                </div>

                <!-- Horário do Agendamento -->
                <div>
                    <label for="hora_agendamento" style="display: block; margin-bottom: 5px; font-weight: bold;">
                        Horário <span style="color: red;">*</span>
                    </label>
                    <input type="time" 
                           id="hora_agendamento" 
                           name="hora_agendamento" 
                           required
                           style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                </div>

                <!-- Observações -->
                <div style="grid-column: 1 / -1;">
                    <label for="observacoes" style="display: block; margin-bottom: 5px; font-weight: bold;">Observações</label>
                    <textarea id="observacoes" 
                              name="observacoes" 
                              rows="3" 
                              placeholder="Informações adicionais sobre o agendamento..."
                              style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 4px; resize: vertical;"></textarea>
                </div>
            </div>
            
            <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="fecharModal()" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i>Salvar Agendamento
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal customizado de Confirmação de Exclusão -->
<div id="modalExclusaoOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div id="modalExclusaoContent" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); max-width: 450px; width: 90%; max-height: 90%; overflow-y: auto;">
        <!-- Header -->
        <div style="padding: 20px; border-bottom: 1px solid #dee2e6; background: #dc3545; color: white; border-radius: 8px 8px 0 0;">
            <h5 style="margin: 0; display: flex; align-items: center;">
                <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Exclusão
                <button onclick="fecharModalExclusao()" style="margin-left: auto; background: none; border: none; color: white; font-size: 24px; cursor: pointer; padding: 0; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">&times;</button>
            </h5>
        </div>
        
        <!-- Body -->
        <div style="padding: 20px;">
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Atenção!</strong> Esta ação não pode ser desfeita.
            </div>
            
            <div class="text-center mb-3">
                <i class="bi bi-trash text-danger" style="font-size: 3rem;"></i>
            </div>
            
            <div class="text-center">
                <h6 class="mb-2">Tem certeza que deseja excluir o agendamento:</h6>
                <div class="alert alert-light">
                    <strong id="pacienteNomeExclusao" class="text-primary"></strong><br>
                    <small class="text-muted" id="dataHoraExclusao"></small>
                </div>
                <p class="text-muted small">
                    <i class="bi bi-info-circle me-1"></i>
                    O agendamento será removido permanentemente do sistema.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="padding: 20px; border-top: 1px solid #dee2e6; display: flex; gap: 10px; justify-content: flex-end;">
            <button type="button" class="btn btn-secondary" onclick="fecharModalExclusao()">
                <i class="bi bi-x-circle me-1"></i>Cancelar
            </button>
            <button type="button" class="btn btn-danger" onclick="confirmarExclusao()">
                <i class="bi bi-trash me-1"></i>Sim, Excluir
            </button>
        </div>
    </div>
</div>

<script>
function abrirModal() {
    document.getElementById('modalOverlay').style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    // Limpar campos ao abrir o modal
    document.getElementById('paciente_id').value = '';
    document.getElementById('procedimento_id').value = '';
    document.getElementById('anestesista_id_modal').value = '';
    document.getElementById('data_agendamento').value = '<?= date('Y-m-d') ?>';
    document.getElementById('hora_agendamento').value = '';
    document.getElementById('observacoes').value = '';
}

function fecharModal() {
    document.getElementById('modalOverlay').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Fechar modal clicando fora
document.getElementById('modalOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModal();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (document.getElementById('modalOverlay').style.display === 'block') {
        fecharModal();
        }
        if (document.getElementById('modalExclusaoOverlay').style.display === 'block') {
            fecharModalExclusao();
        }
    }
});

// Variável para armazenar ID do agendamento a ser excluído
let agendamentoIdParaExcluir = null;

// Funções do modal de exclusão
function mostrarModalExclusao(agendamentoId, pacienteNome, dataHora) {
    console.log('Abrindo modal de exclusão para agendamento:', agendamentoId);
    
    agendamentoIdParaExcluir = agendamentoId;
    document.getElementById('pacienteNomeExclusao').textContent = pacienteNome;
    document.getElementById('dataHoraExclusao').textContent = dataHora;
    
    // Mostrar modal customizado
    document.getElementById('modalExclusaoOverlay').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function fecharModalExclusao() {
    document.getElementById('modalExclusaoOverlay').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function confirmarExclusao() {
    if (!agendamentoIdParaExcluir) {
        alert('Erro: ID do agendamento não encontrado');
        return;
    }
    
    // Redirecionar para exclusão
    window.location.href = '<?= BASE_URL ?>/agendamentos/delete/' + agendamentoIdParaExcluir;
}

// Fechar modal de exclusão clicando fora
document.getElementById('modalExclusaoOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModalExclusao();
    }
});

// Função para auto-preenchimento baseado no paciente selecionado
function preencherDadosPaciente() {
    console.log('🚀 Função preencherDadosPaciente executada!');
    
    const pacienteSelect = document.getElementById('paciente_id');
    const selectedOption = pacienteSelect.options[pacienteSelect.selectedIndex];
    
    console.log('📋 Paciente selecionado:', {
        index: pacienteSelect.selectedIndex,
        value: selectedOption ? selectedOption.value : 'null',
        text: selectedOption ? selectedOption.text : 'null'
    });
    
    if (!selectedOption || !selectedOption.value) {
        console.log('❌ Nenhum paciente selecionado, limpando campos');
        // Limpar campos se nenhum paciente selecionado
        document.getElementById('procedimento_id').value = '';
        document.getElementById('anestesista_id_modal').value = '';
        document.getElementById('data_agendamento').value = '<?= date('Y-m-d') ?>';
        return;
    }
    
    // Obter dados do paciente selecionado
    const procedimentoId = selectedOption.getAttribute('data-procedimento-id');
    const procedimentoNome = selectedOption.getAttribute('data-procedimento-nome');
    const anestesistaId = selectedOption.getAttribute('data-anestesista-id');
    const anestesistaNome = selectedOption.getAttribute('data-anestesista-nome');
    const dataProcedimento = selectedOption.getAttribute('data-data-procedimento');
    
        console.log('Dados do paciente:', {
            procedimentoId,
            procedimentoNome,
            anestesistaId,
            anestesistaNome,
            dataProcedimento
        });
        
        // Debug: verificar se os dados não estão vazios
        if (!anestesistaId || anestesistaId === '' || anestesistaId === 'null') {
            console.log('❌ PROBLEMA: anestesistaId está vazio ou null:', anestesistaId);
            return;
        }
        if (!anestesistaNome || anestesistaNome === '' || anestesistaNome === 'null') {
            console.log('❌ PROBLEMA: anestesistaNome está vazio ou null:', anestesistaNome);
            return;
        }
    
    // Auto-preenchimento do procedimento
    if (procedimentoId && procedimentoNome) {
        const procedimentoSelect = document.getElementById('procedimento_id');
        
        console.log('Tentando preencher procedimento:', procedimentoNome, 'ID:', procedimentoId);
        console.log('Opções disponíveis no select de procedimento:');
        for (let i = 0; i < procedimentoSelect.options.length; i++) {
            console.log(`  Opção ${i}: value="${procedimentoSelect.options[i].value}", text="${procedimentoSelect.options[i].text}"`);
        }
        
        // Tentar diferentes formas de definir o valor
        let procedimentoEncontrado = false;
        
        // Tentar com string
        procedimentoSelect.value = String(procedimentoId);
        if (procedimentoSelect.value === String(procedimentoId)) {
            console.log('✅ Procedimento preenchido com sucesso (string):', procedimentoNome);
            procedimentoEncontrado = true;
        }
        
        // Tentar com número se string não funcionou
        if (!procedimentoEncontrado) {
            procedimentoSelect.value = parseInt(procedimentoId);
            if (procedimentoSelect.value === parseInt(procedimentoId)) {
                console.log('✅ Procedimento preenchido com sucesso (número):', procedimentoNome);
                procedimentoEncontrado = true;
            }
        }
        
        // Se ainda não encontrou, tentar por texto
        if (!procedimentoEncontrado) {
            console.log('❌ Procedimento não encontrado por ID:', procedimentoNome, 'ID:', procedimentoId);
            console.log('Valor atual do select:', procedimentoSelect.value);
            
            // Tentar encontrar por texto
            for (let i = 0; i < procedimentoSelect.options.length; i++) {
                if (procedimentoSelect.options[i].text.includes(procedimentoNome)) {
                    procedimentoSelect.selectedIndex = i;
                    console.log('✅ Procedimento encontrado por texto:', procedimentoNome);
                    procedimentoEncontrado = true;
                    break;
                }
            }
        }
        
        // Se ainda não encontrou, mostrar erro detalhado
        if (!procedimentoEncontrado) {
            console.log('❌ Procedimento não encontrado de forma alguma:', procedimentoNome);
        }
    }
    
    // Auto-preenchimento do anestesista (só se tiver anestesista associado)
    if (anestesistaId && anestesistaNome && anestesistaId !== '' && anestesistaId !== 'null') {
        const anestesistaSelect = document.getElementById('anestesista_id_modal');
        
        console.log('Tentando preencher anestesista:', anestesistaNome, 'ID:', anestesistaId);
        console.log('Opções disponíveis no select:');
        for (let i = 0; i < anestesistaSelect.options.length; i++) {
            console.log(`  Opção ${i}: value="${anestesistaSelect.options[i].value}", text="${anestesistaSelect.options[i].text}"`);
        }
        
        // Tentar diferentes formas de definir o valor
        let anestesistaEncontrado = false;
        
        // Tentar com string
        anestesistaSelect.value = String(anestesistaId);
        if (anestesistaSelect.value === String(anestesistaId)) {
            console.log('✅ Anestesista preenchido com sucesso (string):', anestesistaNome);
            anestesistaEncontrado = true;
        }
        
        // Tentar com número se string não funcionou
        if (!anestesistaEncontrado) {
            anestesistaSelect.value = parseInt(anestesistaId);
            if (anestesistaSelect.value === parseInt(anestesistaId)) {
                console.log('✅ Anestesista preenchido com sucesso (número):', anestesistaNome);
                anestesistaEncontrado = true;
            }
        }
        
        // Se ainda não encontrou, tentar por texto
        if (!anestesistaEncontrado) {
            console.log('❌ Anestesista não encontrado por ID:', anestesistaNome, 'ID:', anestesistaId);
            console.log('Valor atual do select:', anestesistaSelect.value);
            
            // Tentar encontrar por texto
            for (let i = 0; i < anestesistaSelect.options.length; i++) {
                if (anestesistaSelect.options[i].text.includes(anestesistaNome)) {
                    anestesistaSelect.selectedIndex = i;
                    console.log('✅ Anestesista encontrado por texto:', anestesistaNome);
                    anestesistaEncontrado = true;
                    break;
                }
            }
        }
        
        // Se ainda não encontrou, mostrar erro detalhado
        if (!anestesistaEncontrado) {
            console.log('❌ Anestesista não encontrado de forma alguma:', anestesistaNome);
        }
    }
    
    // Auto-preenchimento da data se o paciente tem data de procedimento
    if (dataProcedimento && dataProcedimento !== '0000-00-00') {
        const dataFormatada = dataProcedimento.split('-').reverse().join('-');
        document.getElementById('data_agendamento').value = dataProcedimento;
        console.log('Data preenchida:', dataProcedimento);
    }
    
    // Forçar atualização visual dos selects
    setTimeout(() => {
        // Disparar evento change para garantir que os selects sejam atualizados visualmente
        if (procedimentoId) {
            const procSelect = document.getElementById('procedimento_id');
            procSelect.dispatchEvent(new Event('change'));
            procSelect.dispatchEvent(new Event('input'));
            console.log('Evento change disparado para procedimento');
        }
        if (anestesistaId) {
            const anestSelect = document.getElementById('anestesista_id_modal');
            anestSelect.dispatchEvent(new Event('change'));
            anestSelect.dispatchEvent(new Event('input'));
            console.log('Evento change disparado para anestesista');
        }
        
        // Forçar re-renderização visual
        document.querySelectorAll('select').forEach(select => {
            select.style.display = 'none';
            select.offsetHeight; // Trigger reflow
            select.style.display = 'block';
        });
    }, 100);
    
    // Debug: verificar se chegou até aqui
    console.log('🔍 Verificando dados para alerta:', {
        procedimentoNome: procedimentoNome,
        anestesistaNome: anestesistaNome,
        dataProcedimento: dataProcedimento,
        temProcedimento: !!procedimentoNome,
        temAnestesista: !!anestesistaNome,
        temData: !!(dataProcedimento && dataProcedimento !== '0000-00-00')
    });
    
    // Mostrar informações preenchidas
    if (procedimentoNome || anestesistaNome || (dataProcedimento && dataProcedimento !== '0000-00-00')) {
        let mensagem = 'Dados preenchidos automaticamente:\n';
        if (procedimentoNome) mensagem += `• Procedimento: ${procedimentoNome}\n`;
        if (anestesistaNome) mensagem += `• Nutricionista: ${anestesistaNome}\n`;
        if (dataProcedimento && dataProcedimento !== '0000-00-00') {
            mensagem += `• Data sugerida: ${dataProcedimento.split('-').reverse().join('/')}\n`;
        }
        mensagem += '\nVocê pode alterar qualquer informação se necessário.';
        
        console.log('📘 Mostrando alerta AZUL (informativo)');
        // Criar alerta elegante ao invés de alert() nativo
        mostrarAlertaInformativo(mensagem);
    } else {
        // Se não há dados para preencher, mostrar alerta amarelo
        let mensagem = 'Paciente selecionado sem dados pré-cadastrados:\n\n';
        mensagem += '• Procedimento: Não informado\n';
        mensagem += '• Nutricionista: Não associado\n';
        mensagem += '• Data: Data atual\n\n';
        mensagem += 'Preencha manualmente os campos obrigatórios.';
        
        console.log('📙 Mostrando alerta AMARELO (aviso)');
        mostrarAlertaAviso(mensagem);
    }
}

// Função para mostrar alerta informativo elegante
function mostrarAlertaInformativo(mensagem) {
    // Remover alerta anterior se existir
    const alertaAnterior = document.getElementById('alertaAutoPreenchimento');
    if (alertaAnterior) {
        alertaAnterior.remove();
    }
    
    // Criar novo alerta
    const alerta = document.createElement('div');
    alerta.id = 'alertaAutoPreenchimento';
    alerta.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #d1ecf1;
        border: 1px solid #bee5eb;
        color: #0c5460;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        max-width: 400px;
        font-size: 14px;
        line-height: 1.4;
    `;
    
    alerta.innerHTML = `
        <div style="display: flex; align-items: flex-start;">
            <i class="bi bi-info-circle me-2" style="color: #0c5460; font-size: 18px; margin-top: 2px;"></i>
            <div style="flex: 1;">
                <strong>Auto-preenchimento</strong><br>
                ${mensagem.replace(/\n/g, '<br>')}
            </div>
            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: #0c5460; font-size: 18px; cursor: pointer; margin-left: 10px; padding: 0;">&times;</button>
        </div>
    `;
    
    document.body.appendChild(alerta);
    
    // Remover automaticamente após 8 segundos
    setTimeout(() => {
        if (alerta.parentElement) {
            alerta.remove();
        }
    }, 8000);
}

// Função para mostrar alerta de aviso (amarelo)
function mostrarAlertaAviso(mensagem) {
    console.log('🔧 Função mostrarAlertaAviso chamada com mensagem:', mensagem);
    
    // Remover alerta anterior se existir
    const alertaAnterior = document.getElementById('alertaAviso');
    if (alertaAnterior) {
        alertaAnterior.remove();
    }
    
    // Criar novo alerta
    const alerta = document.createElement('div');
    alerta.id = 'alertaAviso';
    alerta.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        max-width: 400px;
        font-size: 14px;
        line-height: 1.4;
    `;
    
    alerta.innerHTML = `
        <div style="display: flex; align-items: flex-start;">
            <i class="bi bi-exclamation-triangle me-2" style="color: #856404; font-size: 18px; margin-top: 2px;"></i>
            <div style="flex: 1;">
                <strong>Atenção</strong><br>
                ${mensagem.replace(/\n/g, '<br>')}
            </div>
            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: #856404; font-size: 18px; cursor: pointer; margin-left: 10px; padding: 0;">&times;</button>
        </div>
    `;
    
    document.body.appendChild(alerta);
    
    // Remover automaticamente após 8 segundos
    setTimeout(() => {
        if (alerta.parentElement) {
            alerta.remove();
        }
    }, 8000);
}
</script>