<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'NutriCheck' ?></title>
    
    <!-- Favicon - prancheta + folha (nutrição hospitalar) -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect x=%2228%22 y=%2220%22 width=%2244%22 height=%2260%22 rx=%224%22 fill=%22none%22 stroke=%22%239b87f5%22 stroke-width=%225%22/><path d=%22M38 20 L38 12 L62 12 L62 20%22 fill=%22none%22 stroke=%22%239b87f5%22 stroke-width=%224%22/><path d=%22M50 45 Q55 35 62 42 Q58 55 50 58 Q42 55 38 42 Q45 35 50 45%22 fill=%22none%22 stroke=%22%239b87f5%22 stroke-width=%223%22/></svg>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= rtrim(BASE_URL ?? '', '/') ?>/assets/css/style.css?v=<?= time() ?>" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
        main {
            flex: 1 0 auto;
            min-height: 0;
            margin: 0 !important;
            padding: 0 !important;
        }
        .main-content-wrapper {
            width: 100%;
        }
        footer {
            flex-shrink: 0;
        }
        /* Garantir que não há espaçamento no topo */
        .hero-section {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <main>
        <?php if (isset($content)): ?>
            <?= $content ?>
        <?php endif; ?>
    </main>

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
    <!-- Custom JS -->
    <script src="<?= rtrim(BASE_URL ?? '', '/') ?>/assets/js/app.js"></script>
    
    <!-- Auto-dismiss toasts after 5 seconds -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss toasts after 5 seconds
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(function(toast) {
                setTimeout(function() {
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.hide();
                }, 5000); // 5 seconds
            });
        });
    </script>
</body>
</html>
