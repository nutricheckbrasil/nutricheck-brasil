<?php require_once APP_PATH . '/views/layouts/main.php'; ?>
<?php if (isset($_SESSION['perfil_id']) && in_array($_SESSION['perfil_id'], [2,3])): ?>
<?php
$agendamentosPorData = [];
foreach ($agendamentos as $ag) {
    $agendamentosPorData[$ag['data']][] = $ag;
}
$dataSelecionada = $_GET['data'] ?? date('Y-m-d');
?>
<style>
.calendario-board {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}
.calendario-board > div {
    flex: 1 1 320px;
    min-width: 300px;
    display: flex;
    flex-direction: column;
}
.calendario-board .card {
    flex: 1 1 auto;
    height: 100%;
}
.compact-calendar {
    width: 100%;
    max-width: 420px;
    margin-bottom: 1rem;
    font-size: 1.2rem;
}
.compact-calendar th, .compact-calendar td {
    text-align: center;
    padding: 0.4rem 0.3rem;
    font-size: 0.9rem;
    vertical-align: middle;
}
.compact-calendar a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 1.8em;
    height: 1.8em;
    text-align: center;
    border-radius: 50%;
    transition: background 0.2s, color 0.2s;
}
.compact-calendar .selected-day a {
    background: #0d6efd;
    color: #fff !important;
    font-weight: bold;
    box-shadow: 0 2px 8px #0d6efd33;
}
.compact-calendar .today a {
    border: 2px solid #0d6efd;
    color: #0d6efd !important;
    background: #fff;
    font-weight: bold;
}
.compact-calendar .has-agendamento a {
    position: relative;
}
.compact-calendar .has-agendamento a:after {
    content: '';
    display: block;
    width: 6px;
    height: 6px;
    background: #0d6efd;
    border-radius: 50%;
    position: absolute;
    bottom: 3px;
    left: 50%;
    transform: translateX(-50%);
}
@media (max-width: 991.98px) {
    .calendario-board {
        flex-direction: column;
        gap: 1rem;
    }
    .agendamento-painel {
        margin-top: 2rem;
    }
    .compact-calendar {
        max-width: 100%;
        font-size: 0.9rem;
    }
    .compact-calendar th, .compact-calendar td {
        padding: 0.3rem 0.2rem;
        font-size: 0.8rem;
    }
    .compact-calendar a {
        width: 1.6em;
        height: 1.6em;
    }
}
.table-pacientes {
    border-radius: 0.5rem;
    overflow: hidden;
}
.table-pacientes thead th {
    background: #f8f9fa;
    font-weight: bold;
    border-bottom: 2px solid #dee2e6;
}
.table-pacientes tbody tr:hover {
    background: #f1f3f9;
}
</style>
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
<!-- FullCalendar Locale PT-BR -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.min.js"></script>
<!-- Bootstrap Tooltip para eventos -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Script de inicialização customizado -->
<script src="/assets/js/fullcalendar-init.js"></script>
<script>
window.fcEvents = <?= $fc_events_json ?>;
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss toasts
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(function(toast) {
        setTimeout(function() {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }, 5000);
    });

    // Auto-load patient data when patient is selected
    const pacienteSelect = document.getElementById('paciente_select');
    const procedimentoNomeInput = document.getElementById('procedimento_nome');
    const procedimentoIdInput = document.getElementById('procedimento_id');
    const pacienteInfo = document.getElementById('paciente_info');
    const pacienteIdade = document.getElementById('paciente_idade');
    const pacienteStatus = document.getElementById('paciente_status');
    const pacienteAnestesista = document.getElementById('paciente_anestesista');
    
    if (pacienteSelect) {
        pacienteSelect.addEventListener('change', function() {
            const pacienteId = this.value;
            
            // Limpar informações anteriores
            procedimentoIdInput.value = '';
            procedimentoNomeInput.value = '';
            pacienteInfo.style.display = 'none';
            pacienteIdade.textContent = '-';
            pacienteStatus.innerHTML = '-';
            pacienteAnestesista.textContent = '-';
            
            if (pacienteId) {
                // Fetch patient's complete data
                fetch(`agendamentos/getPacienteDados?paciente_id=${pacienteId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.paciente) {
                            const paciente = data.paciente;
                            
                            // Preencher procedimento
                            if (paciente.procedimento_id && paciente.procedimento_nome) {
                                procedimentoIdInput.value = paciente.procedimento_id;
                                procedimentoNomeInput.value = paciente.procedimento_nome;
                            }
                            
                            // Preencher informações do paciente
                            pacienteIdade.textContent = paciente.idade ? paciente.idade + ' anos' : '-';
                            
                            // Status com badge
                            if (paciente.status_text) {
                                pacienteStatus.innerHTML = `<span class="badge bg-${paciente.status_class}">${paciente.status_text}</span>`;
                            }
                            
                            pacienteAnestesista.textContent = paciente.anestesista_nome || '-';
                            
                            // Mostrar card de informações
                            pacienteInfo.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao carregar dados do paciente:', error);
                    });
            }
        });
    }
});
</script>
<div class="d-flex">
    <?php require APP_PATH . '/views/layouts/sidebar.php'; ?>
    <div class="flex-grow-1 p-4" style="min-height: 100vh;">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <h2 class="mb-0">Agendamentos</h2>
            </div>
            <!-- Board de Resumo -->
            <div class="row mb-4">
                <div class="col-md-3 mb-2">
                    <div class="card text-center bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="fs-3 fw-bold"><?= $board['hoje'] ?></div>
                            <div>Agendamentos Hoje</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="card text-center bg-info text-white h-100">
                        <div class="card-body">
                            <div class="fs-3 fw-bold"><?= $board['semana'] ?></div>
                            <div>Na Semana</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="card text-center bg-success text-white h-100">
                        <div class="card-body">
                            <div class="fs-3 fw-bold"><?= $board['realizados'] ?></div>
                            <div>Realizados</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="card text-center bg-danger text-white h-100">
                        <div class="card-body">
                            <div class="fs-3 fw-bold"><?= $board['cancelados'] ?></div>
                            <div>Cancelados</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Calendário e Pacientes lado a lado -->
            <div class="calendario-board mb-4">
                <div>
                    <div class="card h-100 d-flex flex-column">
                        <div class="card-header bg-primary text-white">
                            <strong>Novo Agendamento Rápido</strong>
                        </div>
                        <div class="card-body flex-grow-1 d-flex flex-column">
                            <?php if (!empty($quick_errors)): ?>
                                <div class="alert alert-danger py-2">
                                    <?= implode('<br>', $quick_errors) ?>
                                </div>
                            <?php endif; ?>
                            <form method="post" autocomplete="off" class="flex-grow-1 d-flex flex-column">
                                <input type="hidden" name="quick_agendamento" value="1">
                                <div class="mb-2">
                                    <label class="form-label">Paciente *</label>
                                    <select name="paciente_id" id="paciente_select" class="form-select" required>
                                        <option value="">Selecione...</option>
                                        <?php foreach ($pacientes as $p): ?>
                                            <option value="<?= $p['id'] ?>" <?= $quick_values['paciente_id'] == $p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['nome']) ?> <?= htmlspecialchars($p['sobrenome']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <!-- Informações do Paciente -->
                                <div id="paciente_info" class="mb-3" style="display: none;">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white py-2">
                                            <small><i class="bi bi-info-circle me-1"></i>Informações do Paciente</small>
                                        </div>
                                        <div class="card-body py-2">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <small class="text-muted">Idade:</small>
                                                    <div id="paciente_idade" class="fw-bold">-</div>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted">Status:</small>
                                                    <div id="paciente_status">-</div>
                                                </div>
                                                <div class="col-12">
                                                    <small class="text-muted">Nutricionista:</small>
                                                    <div id="paciente_anestesista" class="fw-bold">-</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <label class="form-label">Procedimento</label>
                                    <input type="text" name="procedimento_nome" id="procedimento_nome" class="form-control" readonly value="<?= htmlspecialchars($quick_values['procedimento_nome'] ?? '') ?>">
                                    <input type="hidden" name="procedimento_id" id="procedimento_id" value="<?= $quick_values['procedimento_id'] ?? '' ?>">
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label">Data *</label>
                                        <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($quick_values['data'] ?: date('Y-m-d')) ?>" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Hora *</label>
                                        <input type="time" name="hora" class="form-control" value="<?= htmlspecialchars($quick_values['hora']) ?>" required>
                                    </div>
                                </div>
                                <div class="mb-2 flex-grow-1">
                                    <label class="form-label">Observações</label>
                                    <textarea name="observacoes" class="form-control" rows="2"><?= htmlspecialchars($quick_values['observacoes']) ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100 mt-auto">Salvar Agendamento</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="card h-100 d-flex flex-column">
                        <div class="card-header bg-primary text-white">
                            <strong>Pacientes por Status</strong>
                        </div>
                        <div class="card-body flex-grow-1">
                            <div class="table-responsive table-pacientes">
                                <table class="table table-hover table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Paciente</th>
                                            <th>Idade</th>
                                            <th>Procedimento</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pacientes_status as $p): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($p['nome']) ?></td>
                                                <td><?= $p['idade'] !== '' ? $p['idade'] . ' anos' : '-' ?></td>
                                                <td><?= htmlspecialchars($p['procedimento']) ?></td>
                                                <td>
                                                    <?php if ($p['status'] === 'Nunca fez procedimento'): ?>
                                                        <span class="badge bg-secondary">Nunca fez</span>
                                                    <?php elseif ($p['status'] === 'Agendamento futuro'): ?>
                                                        <span class="badge bg-info text-dark">Agendado</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Já realizou</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                
                                <!-- Paginação -->
                                <?php if ($total_pages > 1): ?>
                                    <nav aria-label="Paginação de pacientes" class="mt-3">
                                        <ul class="pagination pagination-sm justify-content-center mb-0">
                                            <?php if ($page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?= $page - 1 ?>">&laquo; Anterior</a>
                                                </li>
                                            <?php endif; ?>
                                            
                                            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>
                                            
                                            <?php if ($page < $total_pages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?= $page + 1 ?>">Próximo &raquo;</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                    <div class="text-center text-muted small mt-2">
                                        Mostrando <?= count($pacientes_status) ?> de <?= $total_pacientes ?> pacientes
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="card h-100 d-flex flex-column">
                        <div class="card-header bg-primary text-white">
                            <strong>Calendário</strong>
                        </div>
                        <div class="card-body flex-grow-1">
                            <?php
                            $hoje = date('Y-m-d');
                            $ano = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');
                            $mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('m');
                            $primeiroDia = date('N', strtotime("$ano-$mes-01"));
                            $diasNoMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
                            $diasSemana = ['S', 'T', 'Q', 'Q', 'S', 'S', 'D'];
                            $meses = [
                                1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                                5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                                9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                            ];
                            ?>
                            <form method="get" class="mb-2 d-flex justify-content-between align-items-center">
                                <?php
                                $mesAnterior = $mes - 1;
                                $anoAnterior = $ano;
                                if ($mesAnterior < 1) {
                                    $mesAnterior = 12;
                                    $anoAnterior = $ano - 1;
                                }
                                
                                $mesProximo = $mes + 1;
                                $anoProximo = $ano;
                                if ($mesProximo > 12) {
                                    $mesProximo = 1;
                                    $anoProximo = $ano + 1;
                                }
                                ?>
                                <button type="submit" name="mes" value="<?= $mesAnterior ?>" class="btn btn-sm btn-outline-secondary">
                                    <input type="hidden" name="ano" value="<?= $anoAnterior ?>">&lt;
                                </button>
                                <span class="fw-bold"><?= $meses[intval($mes)] ?> de <?= $ano ?></span>
                                <button type="submit" name="mes" value="<?= $mesProximo ?>" class="btn btn-sm btn-outline-secondary">
                                    <input type="hidden" name="ano" value="<?= $anoProximo ?>">&gt;
                                </button>
                            </form>
                            <table class="table table-bordered compact-calendar mb-0">
                                <thead><tr>
                                    <?php foreach ($diasSemana as $d) echo "<th>$d</th>"; ?>
                                </tr></thead>
                                <tbody>
                                <tr>
                                <?php
                                $dia = 1;
                                $col = 1;
                                for ($i = 1; $i < $primeiroDia; $i++, $col++) {
                                    echo '<td></td>';
                                }
                                while ($dia <= $diasNoMes) {
                                    $dataAtual = sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
                                    $classes = [];
                                    if ($dataAtual == $hoje) $classes[] = 'today';
                                    if ($dataAtual == $dataSelecionada) $classes[] = 'selected-day';
                                    if (isset($agendamentosPorData[$dataAtual])) $classes[] = 'has-agendamento';
                                    echo '<td class="'.implode(' ', $classes).'">';
                                    echo '<a href="?data='.$dataAtual.'" style="text-decoration:none;'.($dataAtual == $dataSelecionada?'font-weight:bold;':'').'">'.$dia.'</a>';
                                    echo '</td>';
                                    if ($col % 7 == 0) echo '</tr><tr>';
                                    $dia++;
                                    $col++;
                                }
                                while (($col-1) % 7 != 0) {
                                    echo '<td></td>';
                                    $col++;
                                }
                                ?>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Agendamentos do dia -->
            <div class="agendamento-painel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Agendamentos do dia <?= date('d/m/Y', strtotime($dataSelecionada)) ?></h4>
                </div>
                <div class="card">
                    <div class="card-body">
                        <?php if (!empty($agendamentosPorData[$dataSelecionada])): ?>
                            <div class="list-group">
                                <?php foreach ($agendamentosPorData[$dataSelecionada] as $ag): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap mb-2">
                                        <div>
                                            <div class="fw-bold">
                                                <?= htmlspecialchars($ag['paciente_nome']) ?> <?= htmlspecialchars($ag['paciente_sobrenome']) ?>
                                                <span class="badge bg-secondary ms-2"><?= htmlspecialchars($ag['procedimento_nome']) ?></span>
                                            </div>
                                            <div class="text-muted small">
                                                <?= date('H:i', strtotime($ag['hora'])) ?> | Profissional: <?= htmlspecialchars($ag['profissional_nome']) ?> (<?= $ag['profissional_tipo'] ?>)
                                            </div>
                                            <?php if (!empty($ag['observacoes'])): ?>
                                                <div class="small mt-1"><i class="bi bi-info-circle"></i> <?= nl2br(htmlspecialchars($ag['observacoes'])) ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <span class="badge bg-<?= $ag['status'] == 'realizado' ? 'success' : ($ag['status'] == 'cancelado' ? 'danger' : 'primary') ?> me-2">
                                                <?= ucfirst($ag['status']) ?>
                                            </span>
                                            <a href="agendamentos/edit/<?= $ag['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Editar"><i class="bi bi-pencil"></i></a>
                                            <a href="agendamentos/delete/<?= $ag['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir este agendamento?');" title="Excluir"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-calendar-event" style="font-size: 2.5rem;"></i>
                                <p class="mt-3">Nenhum agendamento para este dia.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss toasts
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(function(toast) {
        setTimeout(function() {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }, 5000);
    });

    // Auto-load procedure when patient is selected
    const pacienteSelect = document.querySelector('select[name="paciente_id"]');
    const procedimentoSelect = document.querySelector('select[name="procedimento_id"]');
    
    if (pacienteSelect && procedimentoSelect) {
        pacienteSelect.addEventListener('change', function() {
            const pacienteId = this.value;
            if (pacienteId) {
                // Fetch patient's procedure
                fetch(`agendamentos/getPacienteProcedimento?paciente_id=${pacienteId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.procedimento_id) {
                            procedimentoSelect.value = data.procedimento_id;
                        } else {
                            procedimentoSelect.value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao carregar procedimento:', error);
                        procedimentoSelect.value = '';
                    });
            } else {
                procedimentoSelect.value = '';
            }
        });
    }
});
</script>

<?php endif; ?> 