<div class="container-fluid">
        <div class="mb-2 d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="mb-0"><?= isset($via_qr) && $via_qr ? 'Cadastro de Paciente via QR Code' : 'Novo Paciente' ?></h4>
            <a href="../pacientes" class="btn btn-outline-secondary mt-3 mt-md-0"><i class="bi bi-arrow-left"></i> Voltar</a>
        </div>
        
        <?php if (isset($via_qr) && $via_qr && isset($anestesista)): ?>
        <div class="alert alert-info mb-4">
            <h6 class="mb-2">
                <i class="bi bi-person-badge text-primary"></i>
                Nutricionista Responsável
            </h6>
            <div class="row">
                <div class="col-md-6">
                    <strong>Nome:</strong> <?= htmlspecialchars($anestesista['nome']) ?>
                </div>
                <div class="col-md-6">
                    <strong>CRN:</strong> <?= htmlspecialchars($anestesista['crm']) ?>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <strong>Instituição:</strong> <?= htmlspecialchars($anestesista['instituicao_nome']) ?>
                </div>
                <div class="col-md-6">
                    <strong>Email:</strong> <?= htmlspecialchars($anestesista['email']) ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" class="row g-3" autocomplete="off">
            <div class="col-md-3">
                <label for="nome" class="form-label">
                    <i class="bi bi-person text-primary"></i> Nome
                    <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control campo-destaque campo-obrigatorio" id="nome" name="nome" required value="<?= htmlspecialchars($dados['nome'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label for="sobrenome" class="form-label">
                    <i class="bi bi-person text-secondary"></i> Sobrenome
                </label>
                <input type="text" class="form-control campo-destaque" id="sobrenome" name="sobrenome" value="<?= htmlspecialchars($dados['sobrenome'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label for="data_nascimento" class="form-label">
                    <i class="bi bi-calendar text-primary"></i> Data de Nascimento
                    <span class="text-danger">*</span>
                </label>
                <input type="date" class="form-control campo-destaque campo-obrigatorio" id="data_nascimento" name="data_nascimento" required value="<?= htmlspecialchars($dados['data_nascimento'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label for="telefone" class="form-label">
                    <i class="bi bi-telephone text-primary"></i> Telefone
                    <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control campo-destaque campo-obrigatorio" id="telefone" name="telefone" maxlength="15" pattern="\(\d{2}\) \d{4,5}-\d{4}" placeholder="(99) 99999-9999" required value="<?= htmlspecialchars($dados['telefone'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label for="cpf" class="form-label">
                    <i class="bi bi-card-text text-primary"></i> CPF
                    <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control campo-destaque campo-obrigatorio" id="cpf" name="cpf" maxlength="14" placeholder="000.000.000-00" required value="<?= htmlspecialchars($dados['cpf'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label for="sexo" class="form-label">
                    <i class="bi bi-gender-ambiguous text-primary"></i> Sexo
                    <span class="text-danger">*</span>
                </label>
                <select class="form-select campo-destaque campo-obrigatorio" id="sexo" name="sexo" required>
                    <option value="">Selecione</option>
                    <option value="M" <?= (isset($dados['sexo']) && $dados['sexo'] == 'M') ? 'selected' : '' ?>>Masculino</option>
                    <option value="F" <?= (isset($dados['sexo']) && $dados['sexo'] == 'F') ? 'selected' : '' ?>>Feminino</option>
                    <option value="O" <?= (isset($dados['sexo']) && $dados['sexo'] == 'O') ? 'selected' : '' ?>>Outro</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="procedimento_id" class="form-label">
                    <i class="bi bi-clipboard-pulse text-primary"></i> Procedimento
                    <span class="text-danger">*</span>
                </label>
                <select class="form-select campo-destaque campo-obrigatorio" id="procedimento_id" name="procedimento_id" required>
                    <option value="">Selecione o procedimento</option>
                    <?php if (!empty($procedimentos)): ?>
                        <?php foreach ($procedimentos as $proc): ?>
                            <option value="<?= $proc['id'] ?>" <?= (isset($dados['procedimento_id']) && $dados['procedimento_id'] == $proc['id']) ? 'selected' : '' ?>>
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
                <select class="form-select campo-destaque" id="anestesista_id" name="anestesista_id" <?= isset($via_qr) && $via_qr ? 'disabled' : '' ?>>
                    <option value="">Selecione o nutricionista</option>
                    <?php if (!empty($anestesistas)): ?>
                        <?php foreach ($anestesistas as $anest): ?>
                            <option value="<?= $anest['id'] ?>" <?= (isset($dados['anestesista_id']) && $dados['anestesista_id'] == $anest['id']) || (isset($via_qr) && $via_qr && isset($anestesista) && $anest['id'] == $anestesista['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($anest['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <?php if (isset($via_qr) && $via_qr): ?>
                    <input type="hidden" name="anestesista_id" value="<?= $anestesista['id'] ?>">
                    <div class="form-text text-info">
                        <i class="bi bi-info-circle"></i> Nutricionista pré-selecionado via QR Code
                    </div>
                <?php else: ?>
                    <div class="form-text text-info">
                        <i class="bi bi-info-circle"></i> Se não informado, a equipe se autoajustará e alocará o paciente
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label for="data_procedimento" class="form-label">
                    <i class="bi bi-calendar-check text-primary"></i> Data do Procedimento
                    <small class="text-muted">(opcional)</small>
                </label>
                <input type="date" class="form-control campo-destaque" id="data_procedimento" name="data_procedimento" value="<?= htmlspecialchars($dados['data_procedimento'] ?? '') ?>">
                <div class="form-text text-info">
                    <i class="bi bi-info-circle"></i> Se não informado, será definida uma data estimada (15 dias à frente)
                </div>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">
                    <i class="bi bi-envelope text-primary"></i> Email
                    <span class="text-danger">*</span>
                </label>
                <input type="email" class="form-control campo-destaque campo-obrigatorio" id="email" name="email" required value="<?= htmlspecialchars($dados['email'] ?? '') ?>" oninput="validarEmail()" onblur="validarEmail()">
                <div class="invalid-feedback" id="email-error"></div>
            </div>
            <div class="col-md-6">
                <label for="confirmar_email" class="form-label">
                    <i class="bi bi-envelope-check text-primary"></i> Confirmar Email
                    <span class="text-danger">*</span>
                </label>
                <input type="email" class="form-control campo-destaque campo-obrigatorio" id="confirmar_email" name="confirmar_email" required value="<?= htmlspecialchars($dados['confirmar_email'] ?? '') ?>" oninput="validarEmail()" onblur="validarEmail()">
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
                                <input class="form-check-input" type="checkbox" id="necessita_orientacao_pre_anestesica" name="necessita_orientacao_pre_anestesica" value="1" <?= (isset($dados['necessita_orientacao_pre_anestesica']) && $dados['necessita_orientacao_pre_anestesica']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="necessita_orientacao_pre_anestesica">
                                    <i class="bi bi-exclamation-triangle text-warning"></i>
                                    <strong>Paciente necessita de orientação nutricional pré-operatória</strong>
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="paciente_alto_risco" name="paciente_alto_risco" value="1" <?= (isset($dados['paciente_alto_risco']) && $dados['paciente_alto_risco']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="paciente_alto_risco">
                                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                                    <strong>Paciente com necessidade de atenção nutricional especial</strong>
                                </label>
                            </div>
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
        
        /* Estilo para campos obrigatórios */
        .campo-obrigatorio {
            border-left: 4px solid #dc3545 !important;
            background-color: #fff5f5;
        }
        .campo-obrigatorio:focus {
            border-left-color: #dc3545 !important;
            background-color: #fff;
        }
        
        /* Destaque para labels obrigatórios */
        .form-label .text-danger {
            font-weight: bold;
            font-size: 1.1em;
        }
        
        /* Mensagens informativas com azul mais escuro */
        .form-text.text-info {
            color: #1e40af !important;
        }
        </style>
</div> 