<?php require_once APP_PATH . '/views/layouts/main.php'; ?>

<?php if (!empty(
    isset(
        // compatibilidade para $errors vindo do controller
        $errors
    ) ? $errors : []
)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <div><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="main-login-wrapper">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow login-card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-clipboard2-pulse text-primary" style="font-size: 3rem;"></i>
                        <h2 class="mt-3">Acessar NutriCheck</h2>
                        <p class="text-muted">Plataforma de pré-operatório nutricional inteligente</p>
                    </div>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Entrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: #f0fdf4;
}
.main-login-wrapper {
    flex: 1 0 auto;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    min-height: 80vh;
    margin-top: 48px;
}
.login-card {
    border: none;
    border-radius: 1rem;
    background: #ffffff;
}

.form-control, .form-select {
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
}

.btn {
    border-radius: 0.5rem;
    padding: 0.75rem 1.5rem;
}
</style> 