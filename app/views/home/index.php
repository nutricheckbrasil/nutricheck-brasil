<?php require_once APP_PATH . '/views/layouts/landing.php'; ?>

<!-- Hero Section -->
<div class="hero-section text-center py-5 text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="bi bi-clipboard2-pulse"></i> NutriCheck
                </h1>
                <p class="lead mb-4">
                    Pré-operatório nutricional inteligente para reduzir complicações,
                    tempo de internação e custos hospitalares.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="auth/login" class="btn btn-light btn-lg">
                        <i class="bi bi-box-arrow-in-right"></i> Acessar Sistema
                    </a>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>

<!-- Problem / Solution / Benefits Section -->
<div class="container my-5">
    <div class="row g-4 align-items-stretch">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm pitch-card">
                <div class="card-body">
                    <h3 class="h5 fw-bold text-uppercase text-muted mb-3">O problema</h3>
                    <ul class="mb-0 ps-3">
                        <li>Triagem nutricional pré-operatória pouco estruturada.</li>
                        <li>Informações incompletas chegando ao hospital.</li>
                        <li>Maior variabilidade assistencial e tempo de internação.</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm pitch-card">
                <div class="card-body">
                    <h3 class="h5 fw-bold text-uppercase text-muted mb-3">A solução: NutriCheck</h3>
                    <ul class="mb-0 ps-3">
                        <li>Plataforma web e mobile-first para o pré-operatório.</li>
                        <li>Convite por link/QR com vídeos e questionário validado.</li>
                        <li>Classificação automática de risco e relatório padronizado.</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm pitch-card">
                <div class="card-body">
                    <h3 class="h5 fw-bold text-uppercase text-muted mb-3">Principais benefícios</h3>
                    <ul class="mb-0 ps-3">
                        <li>Padronização da triagem e da jornada pré-operatória.</li>
                        <li>Antecipação de dados críticos para a equipe assistencial.</li>
                        <li>Redução de complicações, infecções e custos hospitalares.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container my-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold">Como o NutriCheck funciona</h2>
            <p class="text-muted">Plataforma web que organiza e digitaliza a jornada nutricional antes da internação</p>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm testimonial-card">
                <div class="card-body text-center">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-people text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="card-title">Triagem estruturada</h5>
                    <p class="card-text text-muted">
                        Questionário validado, classificação automática de risco e registro de dados essenciais.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm testimonial-card">
                <div class="card-body text-center">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-person-vcard text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="card-title">Fluxo pré-operatório</h5>
                    <p class="card-text text-muted">
                        Passo a passo padronizado do convite ao relatório enviado ao hospital antes da admissão.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm testimonial-card">
                <div class="card-body text-center">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-clipboard2-pulse text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="card-title">Motor de regras</h5>
                    <p class="card-text text-muted">
                        Algoritmos configuráveis por especialidade e perfil nutricional, com foco em segurança clínica.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm testimonial-card">
                <div class="card-body text-center">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-phone text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="card-title">Acesso simples</h5>
                    <p class="card-text text-muted">
                        Acesso mobile-first via link ou QR Code, sem necessidade de instalação no hospital.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Journey Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Jornada do Paciente</h2>
                <p class="text-muted">Processo completo de avaliação e orientação de nutrição pré-operatória</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary">
                            <i class="bi bi-person-plus text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h5>Cadastro pelo Nutricionista</h5>
                            <p class="text-muted mb-0">Paciente é cadastrado no sistema pelo nutricionista responsável.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary">
                            <i class="bi bi-shield-lock text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h5>Aceite do Termo LGPD</h5>
                            <p class="text-muted mb-0">Paciente aceita os termos de proteção de dados.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary">
                            <i class="bi bi-camera text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h5>Captura de Selfie</h5>
                            <p class="text-muted mb-0">Paciente tira uma selfie para identificação.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary">
                            <i class="bi bi-cpu text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h5>Vídeo Explicativo</h5>
                            <p class="text-muted mb-0">Paciente assiste vídeo sobre orientações de nutrição pré-operatória.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary">
                            <i class="bi bi-clipboard2-pulse text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h5>Questionário</h5>
                            <p class="text-muted mb-0">Paciente responde questionário de saúde e hábitos alimentares.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary">
                            <i class="bi bi-journal-medical text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h5>Orientação nutricional pré-operatória</h5>
                            <p class="text-muted mb-0">Paciente recebe orientações importantes sobre jejum, hidratação e preparo nutricional antes da cirurgia.</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary">
                            <i class="bi bi-exclamation-triangle text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h5>Seleção de Paciente de Alto Risco</h5>
                            <p class="text-muted mb-0">Identificação e encaminhamento de pacientes que necessitam de atenção nutricional especial.</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success">
                            <i class="bi bi-check2-circle text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <h5>Liberação</h5>
                            <p class="text-muted mb-0">Nutricionista libera o paciente para cirurgia após revisão da avaliação nutricional.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Benefícios do Sistema -->
<div class="container my-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold">Por que usar o NutriCheck?</h2>
            <p class="text-muted">Soluções que facilitam a rotina da equipe de nutrição e garantem segurança ao paciente</p>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm text-center testimonial-card">
                <div class="feature-icon mb-3">
                    <i class="bi bi-shield-lock text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="card-title">Segurança de Dados</h5>
                <p class="card-text text-muted">
                    Proteção total das informações dos pacientes, em conformidade com a LGPD.
                </p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm text-center testimonial-card">
                <div class="feature-icon mb-3">
                    <i class="bi bi-speedometer2 text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="card-title">Agilidade</h5>
                <p class="card-text text-muted">
                    Processos digitais que reduzem o tempo de avaliação e aumentam a eficiência.
                </p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm text-center testimonial-card">
                <div class="feature-icon mb-3">
                    <i class="bi bi-people text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="card-title">Foco no Paciente</h5>
                <p class="card-text text-muted">
                    Jornada humanizada e acompanhamento próximo em todas as etapas.
                </p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm text-center testimonial-card">
                <div class="feature-icon mb-3">
                    <i class="bi bi-headset text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="card-title">Suporte Especializado</h5>
                <p class="card-text text-muted">
                    Equipe pronta para ajudar sempre que precisar.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>

<style>
.hero-section {
    background: linear-gradient(135deg, #1f7a4d 0%, #0f3d2e 100%);
    padding: 4rem 0;
    margin-top: 0 !important;
    padding-top: 4rem !important;
}
.feature-icon {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.timeline {
    position: relative;
    padding: 2.5rem 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, rgba(31, 122, 77, 0.15) 0%, #1f7a4d 50%, rgba(31, 122, 77, 0.15) 100%);
    transform: translateX(-50%);
    border-radius: 2px;
}

.timeline-item {
    position: relative;
    margin-bottom: 2.5rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-item:nth-child(odd) .timeline-content {
    margin-left: 0;
    margin-right: 4rem;
    text-align: right;
}

.timeline-item:nth-child(even) .timeline-content {
    margin-left: 4rem;
    margin-right: 0;
}

.timeline-marker {
    position: absolute;
    left: 50%;
    top: 0;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transform: translateX(-50%);
    z-index: 2;
    box-shadow: 0 4px 12px rgba(15, 61, 46, 0.35);
    border: 3px solid #fff;
}

.timeline-marker i {
    font-size: 1.35rem;
}

.timeline-content {
    background: #fff;
    padding: 1.5rem 1.75rem;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06), 0 0 0 1px rgba(31, 122, 77, 0.08);
    border: 1px solid rgba(31, 122, 77, 0.12);
    transition: box-shadow 0.2s ease;
}

.timeline-content:hover {
    box-shadow: 0 6px 20px rgba(31, 122, 77, 0.12), 0 0 0 1px rgba(31, 122, 77, 0.15);
}

.timeline-content h5 {
    font-size: 1.05rem;
    font-weight: 600;
    color: #2d2a3a;
    margin-bottom: 0.35rem;
}

.timeline-content .text-muted {
    font-size: 0.9rem;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .timeline::before {
        left: 27px;
    }
    
    .timeline-marker {
        left: 27px;
        width: 50px;
        height: 50px;
    }
    
    .timeline-marker i {
        font-size: 1.2rem;
    }
    
    .timeline-content {
        margin-left: 85px !important;
        margin-right: 0 !important;
        text-align: left !important;
        padding: 1.25rem 1.5rem;
    }
}

.testimonial-card {
    border: 1.5px solid rgba(31, 122, 77, 0.25);
    border-radius: 1.5rem;
    background: #f0fdf4;
    box-shadow: 0 6px 32px 0 rgba(31, 122, 77, 0.16), 0 1.5px 6px 0 rgba(0,0,0,0.04);
    transition: box-shadow 0.2s;
}
.testimonial-card:hover {
    box-shadow: 0 12px 40px 0 rgba(15, 61, 46, 0.25), 0 2px 8px 0 rgba(0,0,0,0.08);
}
.testimonial-quote {
    text-align: left;
}

.pitch-card {
    border-radius: 1.25rem;
    background: #ffffff;
    border: 1px solid rgba(15, 61, 46, 0.08);
}

.pitch-card ul {
    list-style: none;
    padding-left: 0;
}
</style>
