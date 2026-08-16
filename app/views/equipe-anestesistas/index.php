<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
        <h4 class="mb-0">Equipe de Nutricionistas</h4>
        <button type="button" class="btn btn-outline-primary mt-3 mt-md-0" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise"></i> Atualizar
        </button>
    </div>

    <!-- Estatísticas Rápidas -->
    <div class="row mb-3">
        <div class="col-md-3 mb-2">
            <div class="card bg-primary text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0"><?= $stats['total_anestesistas'] ?></h6>
                            <small>Total Nutricionistas</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-2">
            <div class="card bg-success text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0"><?= $stats['total_pacientes'] ?></h6>
                            <small>Total Pacientes</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-heart fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-2">
            <div class="card bg-warning text-dark">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0"><?= $stats['pacientes_nao_alocados'] ?></h6>
                            <small>Não Alocados</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-x fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-2">
            <div class="card bg-info text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0"><?= $stats['pacientes_alocados'] ?></h6>
                            <small>Alocados</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-check fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="row">
        <!-- Nutricionistas e Pacientes -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-ul me-2"></i>Pacientes por Nutricionista
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($anestesistas_com_pacientes)): ?>
                        <div class="accordion" id="anestesistasAccordion">
                            <?php foreach ($anestesistas_com_pacientes as $index => $anestesista): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading<?= $anestesista['id'] ?>">
                                        <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?>" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapse<?= $anestesista['id'] ?>" 
                                                aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" 
                                                aria-controls="collapse<?= $anestesista['id'] ?>">
                                            <div class="d-flex align-items-center w-100">
                                                <img src="<?= BASE_URL ?>/<?= $anestesista['foto_path'] ?? 'public/assets/img/default-avatar.png' ?>" 
                                                     alt="Foto" 
                                                     class="rounded-circle me-3" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                <div class="flex-grow-1 text-start">
                                                    <strong><?= htmlspecialchars($anestesista['nome']) ?></strong>
                                                    <br>
                                                    <small class="text-muted">CRN: <?= htmlspecialchars($anestesista['crm']) ?></small>
                                                </div>
                                                <span class="badge bg-primary ms-2">
                                                    <?= count($anestesista['pacientes']) ?> paciente(s)
                                                </span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= $anestesista['id'] ?>" 
                                         class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" 
                                         aria-labelledby="heading<?= $anestesista['id'] ?>" 
                                         data-bs-parent="#anestesistasAccordion">
                                        <div class="accordion-body">
                                            <?php if (!empty($anestesista['pacientes'])): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Nome</th>
                                                                <th>Idade</th>
                                                                <th>Procedimento</th>
                                                                <th>Status</th>
                                                                <th class="text-center">Ações</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($anestesista['pacientes'] as $paciente): ?>
                                                                <tr>
                                                                    <td>
                                                                        <strong><?= htmlspecialchars($paciente['nome']) ?></strong>
                                                                        <?php if (!empty($paciente['sobrenome'])): ?>
                                                                            <br><small class="text-muted"><?= htmlspecialchars($paciente['sobrenome']) ?></small>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        $dn = $paciente['data_nascimento'] ?? '';
                                                                        if (!empty($dn) && $dn !== '0000-00-00' && substr($dn, 0, 4) !== '0000') {
                                                                            try {
                                                                                $nasc = new DateTime($dn);
                                                                                $hoje = new DateTime();
                                                                                if ($nasc > $hoje) { echo '-'; }
                                                                                else { echo $hoje->diff($nasc)->y . ' anos'; }
                                                                            } catch (Exception $e) { echo '-'; }
                                                                        } else {
                                                                            echo '-';
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td><?= htmlspecialchars($paciente['procedimento_nome'] ?? '-') ?></td>
                                                                    <td>
                                                                        <span class="badge bg-<?= $paciente['status_agendamento'] === 'Tem Agendamento' ? 'success' : 'warning' ?>">
                                                                            <?= $paciente['status_agendamento'] === 'Tem Agendamento' ? '<i class="bi bi-calendar-check me-1"></i>Com Agendamento' : '<i class="bi bi-calendar-x me-1"></i>Sem Agendamento' ?>
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" 
                                                                                class="btn btn-sm btn-outline-danger" 
                                                                                onclick="desalocarPaciente(<?= $paciente['id'] ?>, '<?= htmlspecialchars($paciente['nome']) ?>')"
                                                                                title="Desalocar paciente">
                                                                            <i class="bi bi-person-x"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-center py-3">
                                                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                                    <p class="text-muted mb-0 mt-2">Este nutricionista não possui pacientes alocados.</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">Nenhum nutricionista encontrado</h5>
                            <p class="text-muted">Não há nutricionistas cadastrados nesta instituição.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pacientes Não Alocados -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-x me-2"></i>Pacientes Não Alocados
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($pacientes_nao_alocados)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($pacientes_nao_alocados as $paciente): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div class="flex-grow-1">
                                        <?php
                                        $nomeCompleto = trim(($paciente['nome'] ?? '') . ' ' . ($paciente['sobrenome'] ?? ''));
                                        ?>
                                        <strong><?= htmlspecialchars($nomeCompleto ?: ($paciente['nome'] ?? '')) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?php
                                            $dn = $paciente['data_nascimento'] ?? '';
                                            if (!empty($dn) && $dn !== '0000-00-00' && substr($dn, 0, 4) !== '0000') {
                                                try {
                                                    $nasc = new DateTime($dn);
                                                    $hoje = new DateTime();
                                                    if ($nasc > $hoje) { echo 'Idade não informada'; }
                                                    else { echo $hoje->diff($nasc)->y . ' anos'; }
                                                } catch (Exception $e) { echo 'Idade não informada'; }
                                            } else {
                                                echo 'Idade não informada';
                                            }
                                            ?>
                                        </small>
                                    </div>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-primary ms-2" 
                                            onclick="mostrarModalAlocacao(<?= $paciente['id'] ?>, '<?= htmlspecialchars($paciente['nome']) ?>')"
                                            title="Alocar paciente">
                                        <i class="bi bi-person-plus"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                            <h5 class="text-success mt-3">Todos alocados!</h5>
                            <p class="text-muted">Todos os pacientes estão alocados para nutricionistas.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal customizado de Alocação -->
<div id="modalAlocacaoOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div id="modalAlocacaoContent" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); max-width: 500px; width: 90%; max-height: 90%; overflow-y: auto;">
        <!-- Header -->
        <div style="padding: 20px; border-bottom: 1px solid #dee2e6; background: #0d6efd; color: white; border-radius: 8px 8px 0 0;">
            <h5 style="margin: 0; display: flex; align-items: center;">
                <i class="bi bi-person-plus me-2"></i>Alocar Paciente
                <button onclick="fecharModalAlocacao()" style="margin-left: auto; background: none; border: none; color: white; font-size: 24px; cursor: pointer; padding: 0; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">&times;</button>
            </h5>
        </div>
        
        <!-- Body -->
        <div style="padding: 20px;">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Alocar paciente <strong id="nomePacienteModal" class="text-primary"></strong> para um nutricionista:
            </div>
            
            <div class="mb-3">
                <label for="anestesistaSelect" class="form-label">Selecione um nutricionista:</label>
                <select class="form-select" id="anestesistaSelect" required>
                    <option value="">Escolha um nutricionista...</option>
                    <?php foreach ($anestesistas_com_pacientes as $anestesista): ?>
                        <option value="<?= $anestesista['id'] ?>">
                            <?= htmlspecialchars($anestesista['nome']) ?> (CRN: <?= htmlspecialchars($anestesista['crm']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-text">
                <i class="bi bi-lightbulb me-1"></i>
                O paciente será alocado exclusivamente para o nutricionista selecionado.
            </div>
        </div>
        
        <!-- Footer -->
        <div style="padding: 20px; border-top: 1px solid #dee2e6; display: flex; gap: 10px; justify-content: flex-end;">
            <button type="button" class="btn btn-secondary" onclick="fecharModalAlocacao()">
                <i class="bi bi-x-circle me-1"></i>Cancelar
            </button>
            <button type="button" class="btn btn-primary" onclick="alocarPaciente()">
                <i class="bi bi-check-circle me-1"></i>Alocar
            </button>
        </div>
    </div>
</div>

<!-- Modal customizado de Confirmação de Desalocação -->
<div id="modalDesalocacaoOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div id="modalDesalocacaoContent" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); max-width: 450px; width: 90%; max-height: 90%; overflow-y: auto;">
        <!-- Header -->
        <div style="padding: 20px; border-bottom: 1px solid #dee2e6; background: #dc3545; color: white; border-radius: 8px 8px 0 0;">
            <h5 style="margin: 0; display: flex; align-items: center;">
                <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Desalocação
                <button onclick="fecharModalDesalocacao()" style="margin-left: auto; background: none; border: none; color: white; font-size: 24px; cursor: pointer; padding: 0; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">&times;</button>
            </h5>
        </div>
        
        <!-- Body -->
        <div style="padding: 20px;">
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Atenção!</strong> Esta ação irá desalocar o paciente.
            </div>
            
            <div class="text-center mb-3">
                <i class="bi bi-person-x text-danger" style="font-size: 3rem;"></i>
            </div>
            
            <div class="text-center">
                <h6 class="mb-2">Tem certeza que deseja desalocar o paciente:</h6>
                <h5 id="nomePacienteDesalocacao" class="text-primary mb-3"></h5>
                <p class="text-muted small">
                    <i class="bi bi-info-circle me-1"></i>
                    O paciente ficará disponível para nova alocação.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="padding: 20px; border-top: 1px solid #dee2e6; display: flex; gap: 10px; justify-content: flex-end;">
            <button type="button" class="btn btn-secondary" onclick="fecharModalDesalocacao()">
                <i class="bi bi-x-circle me-1"></i>Cancelar
            </button>
            <button type="button" class="btn btn-danger" onclick="confirmarDesalocacao()">
                <i class="bi bi-check-circle me-1"></i>Sim, Desalocar
            </button>
        </div>
    </div>
</div>

<script>
let pacienteIdParaAlocar = null;
let pacienteIdParaDesalocar = null;

function mostrarModalAlocacao(pacienteId, nomePaciente) {
    console.log('Abrindo modal para paciente:', pacienteId, nomePaciente);
    
    pacienteIdParaAlocar = pacienteId;
    document.getElementById('nomePacienteModal').textContent = nomePaciente;
    document.getElementById('anestesistaSelect').value = '';
    
    // Mostrar modal customizado
    document.getElementById('modalAlocacaoOverlay').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function fecharModalAlocacao() {
    document.getElementById('modalAlocacaoOverlay').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function alocarPaciente() {
    const anestesistaId = document.getElementById('anestesistaSelect').value;
    const btnAlocar = document.querySelector('#modalAlocacaoContent .btn-primary');
    
    console.log('Alocando paciente:', pacienteIdParaAlocar, 'para anestesista:', anestesistaId);
    
    if (!anestesistaId) {
        alert('Selecione um nutricionista');
        return;
    }
    
    if (!pacienteIdParaAlocar) {
        alert('Erro: ID do paciente não encontrado');
        return;
    }
    
    // Desabilitar botão e mostrar loading
    btnAlocar.disabled = true;
    btnAlocar.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Alocando...';
    
    fetch('<?= BASE_URL ?>/equipe-anestesistas/alocar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            paciente_id: pacienteIdParaAlocar,
            anestesista_id: anestesistaId
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Fechar modal customizado
            fecharModalAlocacao();
            // Recarregar página
            location.reload();
        } else {
            alert('Erro ao alocar paciente: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
        alert('Erro ao alocar paciente: ' + error.message);
    })
    .finally(() => {
        // Reabilitar botão
        btnAlocar.disabled = false;
        btnAlocar.innerHTML = '<i class="bi bi-check-circle me-1"></i>Alocar';
    });
}

function desalocarPaciente(pacienteId, nomePaciente) {
    console.log('Abrindo modal de desalocação para paciente:', pacienteId, nomePaciente);
    
    pacienteIdParaDesalocar = pacienteId;
    document.getElementById('nomePacienteDesalocacao').textContent = nomePaciente;
    
    // Mostrar modal customizado
    document.getElementById('modalDesalocacaoOverlay').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function fecharModalDesalocacao() {
    document.getElementById('modalDesalocacaoOverlay').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function confirmarDesalocacao() {
    const btnDesalocar = document.querySelector('#modalDesalocacaoContent .btn-danger');
    
    console.log('Desalocando paciente:', pacienteIdParaDesalocar);
    
    if (!pacienteIdParaDesalocar) {
        alert('Erro: ID do paciente não encontrado');
        return;
    }
    
    // Desabilitar botão e mostrar loading
    btnDesalocar.disabled = true;
    btnDesalocar.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Desalocando...';
    
    fetch('<?= BASE_URL ?>/equipe-anestesistas/desalocar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            paciente_id: pacienteIdParaDesalocar
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Fechar modal customizado
            fecharModalDesalocacao();
            // Recarregar página
            location.reload();
        } else {
            alert('Erro ao desalocar paciente: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
        alert('Erro ao desalocar paciente: ' + error.message);
    })
    .finally(() => {
        // Reabilitar botão
        btnDesalocar.disabled = false;
        btnDesalocar.innerHTML = '<i class="bi bi-check-circle me-1"></i>Sim, Desalocar';
    });
}

// Fechar modal clicando fora
document.getElementById('modalAlocacaoOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModalAlocacao();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (document.getElementById('modalAlocacaoOverlay').style.display === 'block') {
            fecharModalAlocacao();
        }
        if (document.getElementById('modalDesalocacaoOverlay').style.display === 'block') {
            fecharModalDesalocacao();
        }
    }
});

// Fechar modal de desalocação clicando fora
document.getElementById('modalDesalocacaoOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        fecharModalDesalocacao();
    }
});
</script>