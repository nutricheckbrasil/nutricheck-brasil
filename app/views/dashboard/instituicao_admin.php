<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard da Instituição
            </h1>
            <p class="text-muted">Visão geral da sua instituição</p>
        </div>
    </div>

    <!-- Cards de Estatísticas Principais -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total de Usuários
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $stats['total_usuarios'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pacientes Cadastrados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $stats['total_pacientes'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-injured fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Nutricionistas Ativos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $stats['total_anestesistas'] ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-md fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Agendamentos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $stats['total_agendamentos'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt"></i> Ações Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="<?= BASE_URL ?>/nutricionistas/create" class="btn btn-success btn-block">
                                <i class="fas fa-user-plus"></i> Novo Nutricionista
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="<?= BASE_URL ?>/pacientes/create" class="btn btn-info btn-block">
                                <i class="fas fa-user-injured"></i> Novo Paciente
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="<?= BASE_URL ?>/agendamentos" class="btn btn-warning btn-block">
                                <i class="fas fa-calendar-alt"></i> Agendamentos
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="<?= BASE_URL ?>/equipe-nutricionistas" class="btn btn-primary btn-block">
                                <i class="fas fa-users"></i> Equipe Nutricionistas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4 equal-height-card">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie"></i> Distribuição Atual
                    </h6>
                </div>
                <div class="card-body d-flex flex-column">
                    <?php if ($stats['total_usuarios'] == 0 && $stats['total_pacientes'] == 0 && $stats['total_anestesistas'] == 0): ?>
                        <div class="text-center py-4 flex-grow-1 d-flex flex-column justify-content-center">
                            <i class="fas fa-chart-pie fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-600">Sem Dados</h5>
                            <p class="text-gray-500">Adicione usuários, pacientes e nutricionistas para ver a distribuição.</p>
                        </div>
                    <?php else: ?>
                        <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                            <canvas id="distributionChart"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card shadow mb-4 equal-height-card">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar"></i> Crescimento Mensal (Últimos 6 meses)
                    </h6>
                </div>
                <div class="card-body d-flex flex-column">
                    <?php if ($stats['total_usuarios'] == 0 && $stats['total_pacientes'] == 0 && $stats['total_anestesistas'] == 0): ?>
                        <div class="text-center py-4 flex-grow-1 d-flex flex-column justify-content-center">
                            <i class="fas fa-chart-bar fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-600">Sem Crescimento</h5>
                            <p class="text-gray-500">Os dados de crescimento aparecerão conforme o sistema for populado.</p>
                        </div>
                    <?php else: ?>
                        <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                            <canvas id="monthlyGrowthChart"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Evolução -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line"></i> Evolução do Sistema ao Longo do Tempo
                    </h6>
                </div>
                <div class="card-body">
                    <?php if ($stats['total_usuarios'] == 0 && $stats['total_pacientes'] == 0 && $stats['total_anestesistas'] == 0): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-600">Sistema Novo</h5>
                            <p class="text-gray-500">Os gráficos começarão a aparecer conforme você adicionar dados ao sistema.</p>
                        </div>
                    <?php else: ?>
                        <canvas id="evolutionChart" height="100"></canvas>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Equalizar altura dos cards */
.equal-height-card {
    height: 100%;
}

.equal-height-card .card-body {
    min-height: 250px;
    max-height: 400px;
}

.equal-height-card canvas {
    max-height: 300px !important;
    width: 100% !important;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.text-xs {
    font-size: 0.7rem;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.btn-block {
    display: block;
    width: 100%;
}

.text-gray-600 {
    color: #5a5c69 !important;
}

.text-gray-500 {
    color: #6c757d !important;
}
</style>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
// Registrar plugin de data labels
Chart.register(ChartDataLabels);

// Dados reais do sistema atual
const currentStats = {
    usuarios: <?= $stats['total_usuarios'] ?>,
    pacientes: <?= $stats['total_pacientes'] ?>,
    anestesistas: <?= $stats['total_anestesistas'] ?>,
    agendamentos: <?= $stats['total_agendamentos'] ?? 0 ?>
};

const chartData = {
    evolution: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        usuarios: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, currentStats.usuarios],
        pacientes: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, currentStats.pacientes],
        anestesistas: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, currentStats.anestesistas]
    },
    monthlyGrowth: {
        labels: ['Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        usuarios: [0, 0, 0, 0, 0, currentStats.usuarios],
        pacientes: [0, 0, 0, 0, 0, currentStats.pacientes],
        anestesistas: [0, 0, 0, 0, 0, currentStats.anestesistas]
    }
};

// Gráfico de Evolução (Linha)
const evolutionCtx = document.getElementById('evolutionChart');
if (evolutionCtx) {
    new Chart(evolutionCtx.getContext('2d'), {
        type: 'line',
        data: {
            labels: chartData.evolution.labels,
            datasets: [
                {
                    label: 'Usuários',
                    data: chartData.evolution.usuarios,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Pacientes',
                    data: chartData.evolution.pacientes,
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Nutricionistas',
                    data: chartData.evolution.anestesistas,
                    borderColor: '#f6c23e',
                    backgroundColor: 'rgba(246, 194, 62, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 1.5,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Crescimento da Instituição'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

// Gráfico de Distribuição (Pizza)
const distributionCtx = document.getElementById('distributionChart');
if (distributionCtx) {
    new Chart(distributionCtx.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: [
                `Usuários (${currentStats.usuarios})`,
                `Pacientes (${currentStats.pacientes})`,
                `Nutricionistas (${currentStats.anestesistas})`
            ],
            datasets: [{
                data: [
                    currentStats.usuarios,
                    currentStats.pacientes,
                    currentStats.anestesistas
                ],
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#f6c23e'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 1.5,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const dataset = data.datasets[0];
                                    const value = dataset.data[i];
                                    const total = dataset.data.reduce((sum, val) => sum + val, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    
                                    return {
                                        text: `${label} (${percentage}%)`,
                                        fillStyle: dataset.backgroundColor[i],
                                        strokeStyle: dataset.borderColor,
                                        lineWidth: dataset.borderWidth,
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                },
                datalabels: {
                    display: true,
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    formatter: function(value, context) {
                        const total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                        if (value > 0) {
                            return value + '\n(' + percentage + '%)';
                        }
                        return '';
                    },
                    anchor: 'center',
                    align: 'center'
                }
            }
        }
    });
}

// Gráfico de Crescimento Mensal (Barras)
const monthlyGrowthCtx = document.getElementById('monthlyGrowthChart');
if (monthlyGrowthCtx) {
    new Chart(monthlyGrowthCtx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: chartData.monthlyGrowth.labels,
            datasets: [
                {
                    label: 'Usuários',
                    data: chartData.monthlyGrowth.usuarios,
                    backgroundColor: 'rgba(78, 115, 223, 0.8)',
                    borderColor: '#4e73df',
                    borderWidth: 1
                },
                {
                    label: 'Pacientes',
                    data: chartData.monthlyGrowth.pacientes,
                    backgroundColor: 'rgba(28, 200, 138, 0.8)',
                    borderColor: '#1cc88a',
                    borderWidth: 1
                },
                {
                    label: 'Nutricionistas',
                    data: chartData.monthlyGrowth.anestesistas,
                    backgroundColor: 'rgba(246, 194, 62, 0.8)',
                    borderColor: '#f6c23e',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 1.5,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            }
        }
    });
}
</script>