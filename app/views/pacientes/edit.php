<?php // Layout será incluído pelo controller ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
            <h2 class="mb-0">Editar Paciente</h2>
            <a href="<?= BASE_URL ?>/pacientes" class="btn btn-outline-secondary mt-3 mt-md-0"><i class="bi bi-arrow-left"></i> Voltar</a>
        </div>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="post" class="row g-3" autocomplete="off">
            <div class="col-md-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control campo-destaque" id="nome" name="nome" required value="<?= htmlspecialchars($paciente['nome'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label for="sobrenome" class="form-label">Sobrenome</label>
                <input type="text" class="form-control campo-destaque" id="sobrenome" name="sobrenome" value="<?= htmlspecialchars($paciente['sobrenome'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control campo-destaque" id="data_nascimento" name="data_nascimento" required value="<?= htmlspecialchars($paciente['data_nascimento'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control campo-destaque" id="telefone" name="telefone" maxlength="15" pattern="\(\d{2}\) \d{4,5}-\d{4}" placeholder="(99) 99999-9999" value="<?= htmlspecialchars($paciente['telefone'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control campo-destaque" id="cpf" name="cpf" maxlength="14" placeholder="000.000.000-00" value="<?= htmlspecialchars($paciente['cpf'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label for="sexo" class="form-label">Sexo</label>
                <select class="form-select campo-destaque" id="sexo" name="sexo" required>
                    <option value="">Selecione</option>
                    <option value="M" <?= (isset($paciente['sexo']) && $paciente['sexo'] == 'M') ? 'selected' : '' ?>>Masculino</option>
                    <option value="F" <?= (isset($paciente['sexo']) && $paciente['sexo'] == 'F') ? 'selected' : '' ?>>Feminino</option>
                    <option value="O" <?= (isset($paciente['sexo']) && $paciente['sexo'] == 'O') ? 'selected' : '' ?>>Outro</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="procedimento_id" class="form-label">Procedimento</label>
                <select class="form-select campo-destaque" id="procedimento_id" name="procedimento_id" required>
                    <option value="">Selecione o procedimento</option>
                    <?php if (!empty($procedimentos)): ?>
                        <?php foreach ($procedimentos as $proc): ?>
                            <option value="<?= $proc['id'] ?>" <?= (isset($paciente['procedimento_id']) && $paciente['procedimento_id'] == $proc['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($proc['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="anestesista_id" class="form-label">
                    <i class="bi bi-person-badge text-primary"></i> Nutricionista
                </label>
                <select class="form-select campo-destaque" id="anestesista_id" name="anestesista_id">
                    <option value="">Selecione o nutricionista</option>
                    <?php if (!empty($anestesistas)): ?>
                        <?php foreach ($anestesistas as $anest): ?>
                            <option value="<?= $anest['id'] ?>" <?= (isset($paciente['anestesista_id']) && $paciente['anestesista_id'] == $anest['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($anest['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div class="form-text text-info">
                    <i class="bi bi-info-circle"></i> Se não informado, a equipe se autoajustará e alocará o paciente
                </div>
            </div>
            <div class="col-md-6">
                <label for="data_procedimento" class="form-label">
                    <i class="bi bi-calendar-check text-primary"></i> Data do Procedimento
                </label>
                <input type="date" class="form-control campo-destaque" id="data_procedimento" name="data_procedimento" value="<?= htmlspecialchars($paciente['data_procedimento'] ?? '') ?>">
                <div class="form-text text-info">
                    <i class="bi bi-info-circle"></i> Data agendada ou estimada para o procedimento
                </div>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control campo-destaque" id="email" name="email" required value="<?= htmlspecialchars($paciente['email'] ?? '') ?>" oninput="validarEmail()" onblur="validarEmail()">
                <div class="invalid-feedback" id="email-error"></div>
            </div>
            <div class="col-md-6">
                <label for="confirmar_email" class="form-label">Confirmar Email</label>
                <input type="email" class="form-control campo-destaque" id="confirmar_email" name="confirmar_email" required value="<?= htmlspecialchars($paciente['email'] ?? '') ?>" oninput="validarEmail()" onblur="validarEmail()">
                <div class="invalid-feedback" id="confirmar-email-error"></div>
            </div>
            
            <!-- Campos de Flag -->
            <div class="col-12 mt-4">
                <div class="bg-secondary bg-opacity-10 p-4 rounded border">
                    <h5 class="text-primary mb-3">
                        <i class="bi bi-flag"></i> Informações Especiais
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="necessita_orientacao_pre_anestesica" name="necessita_orientacao_pre_anestesica" value="1" <?= (isset($paciente['necessita_orientacao_pre_anestesica']) && $paciente['necessita_orientacao_pre_anestesica']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="necessita_orientacao_pre_anestesica">
                                    <i class="bi bi-exclamation-triangle text-warning"></i>
                                    <strong>Paciente necessita de orientação nutricional pré-operatória</strong>
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="paciente_alto_risco" name="paciente_alto_risco" value="1" <?= (isset($paciente['paciente_alto_risco']) && $paciente['paciente_alto_risco']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="paciente_alto_risco">
                                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                    <strong>Paciente com necessidade de atenção nutricional especial</strong>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informação sobre Classificação IA -->
            <div class="col-12 mt-3">
                <div class="bg-info bg-opacity-10 p-3 rounded border border-info">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-robot text-info me-2 fs-4"></i>
                        <div>
                            <h6 class="text-info mb-1">
                                <strong>Classificação IA</strong>
                            </h6>
                            <p class="text-muted mb-0 small">
                                <?php if (!empty($paciente['classificacao_ia'])): ?>
                                    Classificação atual: 
                                    <strong>
                                        <?php
                                        switch ($paciente['classificacao_ia']) {
                                            case 'baixo_risco':
                                                echo '<span class="text-success">Baixo Risco (Liberado para Procedimento)</span>';
                                                break;
                                            case 'risco_moderado':
                                                echo '<span class="text-warning">Risco Moderado</span>';
                                                break;
                                            case 'alto_risco':
                                                echo '<span class="text-danger">Alto Risco</span>';
                                                break;
                                        }
                                        ?>
                                    </strong>
                                <?php else: ?>
                                    A classificação de risco será gerada automaticamente por nossa IA. 
                                    Os valores possíveis são: <strong>Baixo Risco (Liberado para Procedimento)</strong>, <strong>Risco Moderado</strong> ou <strong>Alto Risco</strong>.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save"></i> Salvar</button>
            </div>
        </form>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script>
        $(function() {
            $('#telefone').mask('(00) 00000-0000');
            $('#cpf').mask('000.000.000-00');
        });
        
        function validarEmail() {
            var email = document.getElementById('email');
            var confirmar = document.getElementById('confirmar_email');
            var emailError = document.getElementById('email-error');
            var confirmarError = document.getElementById('confirmar-email-error');
            
            // Limpar mensagens de erro anteriores
            email.classList.remove('is-invalid');
            confirmar.classList.remove('is-invalid');
            emailError.textContent = '';
            confirmarError.textContent = '';
            
            // Validar formato do email principal
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email.value && !emailRegex.test(email.value)) {
                email.classList.add('is-invalid');
                emailError.textContent = 'Digite um email válido';
                return false;
            }
            
            // Validar formato do email de confirmação
            if (confirmar.value && !emailRegex.test(confirmar.value)) {
                confirmar.classList.add('is-invalid');
                confirmarError.textContent = 'Digite um email válido';
                return false;
            }
            
            // Validar se os emails coincidem
            if (email.value && confirmar.value && email.value !== confirmar.value) {
                confirmar.classList.add('is-invalid');
                confirmarError.textContent = 'Os emails não coincidem';
                return false;
            }
            
            // Se tudo estiver correto, adicionar classe de sucesso
            if (email.value && emailRegex.test(email.value)) {
                email.classList.add('is-valid');
            }
            if (confirmar.value && emailRegex.test(confirmar.value) && email.value === confirmar.value) {
                confirmar.classList.add('is-valid');
            }
            
            return true;
        }
        
        // Validar antes do envio do formulário
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!validarEmail()) {
                e.preventDefault();
                return false;
            }
        });
        </script>
        <style>
        .campo-destaque {
            border: 2px solid #0d6efd;
            box-shadow: 0 0 0 0.1rem #0d6efd22;
            font-size: 1.08rem;
        }
        .campo-destaque:focus {
            border-color: #0a58ca;
            box-shadow: 0 0 0 0.2rem #0d6efd33;
        }
        
        /* Mensagens informativas com azul mais escuro */
        .form-text.text-info {
            color: #1e40af !important;
        }
        </style>
        </div>
    </div>
</div> 