<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-2">
        <div class="col-12">
            <h4 class="mb-0">
                <i class="bi bi-speedometer2 me-2"></i>Meu Dashboard
            </h4>
            <p class="text-muted mb-0">Visão geral das suas atividades</p>
        </div>
    </div>

    <!-- Cards de Estatísticas Principais -->
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 mb-2">
            <div class="card bg-primary text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0"><?= $stats['total_pacientes'] ?? 0 ?></h6>
                            <small>Meus Pacientes</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-2">
            <div class="card bg-success text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0"><?= $stats['agendamentos_hoje'] ?? 0 ?></h6>
                            <small>Agendamentos Hoje</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-calendar-day fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-2">
            <div class="card bg-info text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0"><?= $stats['agendamentos_semana'] ?? 0 ?></h6>
                            <small>Esta Semana</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-calendar-week fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-2">
            <div class="card bg-warning text-dark">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0"><?= $stats['agendamentos_pendentes'] ?? 0 ?></h6>
                            <small>Pendentes</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-clock fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header py-2">
                    <h6 class="mb-0 text-primary">
                        <i class="bi bi-lightning me-2"></i>Ações Rápidas
                    </h6>
                </div>
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-lg-3 col-md-6">
                            <a href="<?= BASE_URL ?>/pacientes" class="btn btn-primary w-100">
                                <i class="bi bi-people me-2"></i>Ver Pacientes
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?= BASE_URL ?>/agendamentos" class="btn btn-success w-100">
                                <i class="bi bi-calendar-event me-2"></i>Agendamentos
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?= BASE_URL ?>/classificacao-ia" class="btn btn-info w-100">
                                <i class="bi bi-robot me-2"></i>Classificação IA
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?= BASE_URL ?>/ajuda" class="btn btn-warning w-100">
                                <i class="bi bi-question-circle me-2"></i>Ajuda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Próximos Agendamentos -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header py-2">
                    <h6 class="mb-0 text-primary">
                        <i class="bi bi-calendar-check me-2"></i>Próximos Agendamentos
                    </h6>
                </div>
                <div class="card-body py-3">
                    <div class="text-center">
                        <i class="bi bi-calendar-event fs-1 text-muted mb-3"></i>
                        <h6 class="text-muted">Nenhum agendamento próximo</h6>
                        <p class="text-muted mb-0">Seus próximos agendamentos aparecerão aqui.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

