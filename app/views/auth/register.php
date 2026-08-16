<?php require_once APP_PATH . '/views/layouts/landing.php'; ?>

<style>
body {
    background-color: #f1f3f9;
}
.main-login-wrapper {
    margin-top: 48px;
}
</style>

<div class="main-login-wrapper">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="bi bi-calendar-check text-primary" style="font-size: 3rem;"></i>
                    <h2 class="mt-3">Solicite uma Demonstração</h2>
                    <p class="text-muted">Deixe seus dados e entraremos em contato para agendar uma demonstração personalizada</p>
                </div>

                <form method="POST" action="demonstracoes/solicitar">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome Completo *</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cargo" class="form-label">Cargo/Função *</label>
                            <input type="text" class="form-control" id="cargo" name="cargo" 
                                   placeholder="Ex: Diretor Médico, Coordenador" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="instituicao" class="form-label">Nome da Instituição *</label>
                            <input type="text" class="form-control" id="instituicao" name="instituicao" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cnpj" class="form-label">CNPJ</label>
                            <input type="text" class="form-control" id="cnpj" name="cnpj" 
                                   placeholder="00.000.000/0000-00">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefone" class="form-label">Telefone *</label>
                            <input type="tel" class="form-control" id="telefone" name="telefone" 
                                   placeholder="(11) 99999-9999" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantidade_medicos" class="form-label">Quantidade de Médicos</label>
                            <select class="form-select" id="quantidade_medicos" name="quantidade_medicos">
                                <option value="">Selecione</option>
                                <option value="1-10">1 a 10 médicos</option>
                                <option value="11-50">11 a 50 médicos</option>
                                <option value="51-100">51 a 100 médicos</option>
                                <option value="100+">Mais de 100 médicos</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="interesse" class="form-label">Interesse Principal</label>
                            <select class="form-select" id="interesse" name="interesse">
                                <option value="">Selecione</option>
                                <option value="gestao_pacientes">Gestão de Pacientes</option>
                                <option value="agendamentos">Sistema de Agendamentos</option>
                                <option value="consentimentos">Gestão de Consentimentos</option>
                                <option value="relatorios">Relatórios e Analytics</option>
                                <option value="integracao">Integração com Sistemas</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mensagem" class="form-label">Mensagem (Opcional)</label>
                        <textarea class="form-control" id="mensagem" name="mensagem" rows="4" 
                                  placeholder="Conte-nos mais sobre suas necessidades, horários preferidos para contato, ou qualquer informação adicional..."></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="aceite" name="aceite" required>
                            <label class="form-check-label" for="aceite">
                                Concordo em receber contato da equipe NutriCheck para demonstração do produto
                            </label>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send"></i> Solicitar Demonstração
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="text-muted">
                        Já tem uma conta? 
                        <a href="auth/login" class="text-decoration-none">Faça login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<style>
.card {
    border: none;
    border-radius: 1rem;
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

<script>
// Máscara para CNPJ
document.getElementById('cnpj').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/^(\d{2})(\d)/, '$1.$2');
    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
    value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
    value = value.replace(/(\d{4})(\d)/, '$1-$2');
    e.target.value = value;
});

// Máscara para telefone
document.getElementById('telefone').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
    value = value.replace(/(\d)(\d{4})$/, '$1-$2');
    e.target.value = value;
});

// Validação do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const aceite = document.getElementById('aceite');
    if (!aceite.checked) {
        e.preventDefault();
        alert('É necessário aceitar receber contato para prosseguir.');
        return false;
    }
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>