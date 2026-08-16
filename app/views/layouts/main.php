<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'NutriCheck' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= rtrim(BASE_URL ?? '', '/') ?>/assets/css/style.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            min-height: 100vh;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
            min-height: 0;
        }
        .main-content-wrapper {
            width: 100%;
        }
        @media (max-width: 767.98px) {
            main {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
        footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= isset($_SESSION['user_id']) ? BASE_URL . '/pacientes' : BASE_URL . '/' ?>">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    NutriCheck
                <?php endif; ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarNav">
                <ul class="navbar-nav">
                    <!-- Menu removido -->
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" style="color: white !important;">
                                <i class="bi bi-person-circle" style="color: white !important;"></i> <span style="color: white !important;"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/perfil">Meu Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalConfirmarSair">Sair</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php if (isset($_SESSION['user_id'])): ?>
    <!-- Botão de menu para mobile -->
    <button class="btn btn-primary sidebar-toggle-btn d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMedicoOffcanvas" aria-controls="sidebarMedicoOffcanvas" style="position: absolute; top: 16px; left: 16px; z-index: 1051;">
        <i class="bi bi-list" style="font-size: 2rem;"></i>
    </button>
    
    <!-- Sidebar -->
    <?php include __DIR__ . '/sidebar.php'; ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main style="flex: 1 0 auto; min-height: 0; margin-left: 225px; padding: 20px; width: calc(100% - 225px); position: relative; z-index: 10;">
        <?php if (isset($content)): ?>
            <?= $content ?>
        <?php endif; ?>
    </main>

    <!-- Modal Confirmar Sair -->
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="modal fade" id="modalConfirmarSair" tabindex="-1" aria-labelledby="modalConfirmarSairLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header border-0 pb-0" style="padding: 1.5rem 1.5rem 0.5rem;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="bi bi-box-arrow-right text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="modalConfirmarSairLabel">Sair da conta</h5>
                            <p class="text-muted small mb-0 mt-1">Esta ação encerrará sua sessão.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body pt-2" style="padding: 0.5rem 1.5rem 1.5rem;">
                    <p class="mb-0">Deseja realmente sair do NutriCheck?</p>
                </div>
                <div class="modal-footer border-0 gap-2" style="padding: 0 1.5rem 1.5rem;">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Não</button>
                    <a href="<?= BASE_URL ?>/auth/logout" class="btn btn-primary px-4">Sim, sair</a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Toast Container for Notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
        <?php
        $rotaAtual = $_SERVER['REQUEST_URI'] ?? '';
        if (isset($_SESSION['flash_message']) && strpos($rotaAtual, '/auth/login') === false): ?>
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-<?= $_SESSION['flash_type'] ?? 'info' ?> text-white">
                    <strong class="me-auto">Notificação</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    <?= htmlspecialchars($_SESSION['flash_message']) ?>
                </div>
            </div>
            <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
        <?php endif; ?>
        
        <?php if (isset($success) && !empty($success)): ?>
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-success text-white">
                    <strong class="me-auto">Sucesso</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    <?= htmlspecialchars($success) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS removido temporariamente -->
    
    <!-- Auto-dismiss toasts + fechar offcanvas ao abrir modal Sair -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toasts = document.querySelectorAll('.toast');
            toasts.forEach(function(toast) {
                setTimeout(function() {
                    var bsToast = new bootstrap.Toast(toast);
                    bsToast.hide();
                }, 5000);
            });
            var modalSair = document.getElementById('modalConfirmarSair');
            if (modalSair) {
                modalSair.addEventListener('show.bs.modal', function() {
                    var offcanvas = document.getElementById('sidebarMedicoOffcanvas');
                    if (offcanvas && window.getComputedStyle(offcanvas).visibility !== 'hidden') {
                        var bsOff = bootstrap.Offcanvas.getInstance(offcanvas);
                        if (bsOff) bsOff.hide();
                    }
                });
            }
        });
    </script>
</body>
</html> 