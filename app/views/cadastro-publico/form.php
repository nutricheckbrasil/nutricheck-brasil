<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 10px 30px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .institution-info {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .anestesista-info {
            background: rgba(118, 75, 162, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3 class="mb-0">
                            <i class="fas fa-user-plus"></i>
                            Cadastro de Paciente
                        </h3>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="institution-info">
                            <h5 class="text-primary mb-2">
                                <i class="fas fa-hospital"></i>
                                <?= htmlspecialchars($instituicao['nome']) ?>
                            </h5>
                            <?php if ($instituicao['endereco']): ?>
                            <p class="mb-1">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($instituicao['endereco']) ?>
                            </p>
                            <?php endif; ?>
                            <?php if ($instituicao['telefone']): ?>
                            <p class="mb-0">
                                <i class="fas fa-phone"></i>
                                <?= htmlspecialchars($instituicao['telefone']) ?>
                            </p>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($tipo_cadastro === 'anestesista' && isset($anestesista)): ?>
                        <div class="anestesista-info">
                            <h6 class="text-secondary mb-2">
                                <i class="fas fa-user-md"></i>
                                Nutricionista Responsável
                            </h6>
                            <p class="mb-0">
                                <strong><?= htmlspecialchars($anestesista['nome']) ?></strong>
                                <?php if ($anestesista['crm']): ?>
                                <br><small class="text-muted">CRM: <?= htmlspecialchars($anestesista['crm']) ?></small>
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" id="cadastroForm">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="nome" class="form-label">
                                            <i class="fas fa-user"></i> Nome Completo *
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="nome" 
                                               name="nome" 
                                               required 
                                               placeholder="Digite seu nome completo">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="cpf" class="form-label">
                                            <i class="fas fa-id-card"></i> CPF *
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="cpf" 
                                               name="cpf" 
                                               required 
                                               placeholder="000.000.000-00"
                                               data-mask="000.000.000-00">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="data_nascimento" class="form-label">
                                            <i class="fas fa-calendar"></i> Data de Nascimento *
                                        </label>
                                        <input type="date" 
                                               class="form-control" 
                                               id="data_nascimento" 
                                               name="data_nascimento" 
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="sexo" class="form-label">
                                            <i class="fas fa-venus-mars"></i> Sexo *
                                        </label>
                                        <select class="form-control" id="sexo" name="sexo" required>
                                            <option value="">Selecione...</option>
                                            <option value="M">Masculino</option>
                                            <option value="F">Feminino</option>
                                            <option value="O">Outro</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="telefone" class="form-label">
                                            <i class="fas fa-phone"></i> Telefone *
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="telefone" 
                                               name="telefone" 
                                               required 
                                               placeholder="(00) 00000-0000"
                                               data-mask="(00) 00000-0000">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope"></i> Email
                                        </label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               placeholder="seu@email.com">
                                        <small class="form-text text-muted">Opcional, mas recomendado para contato</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="endereco" class="form-label">
                                            <i class="fas fa-home"></i> Endereço
                                        </label>
                                        <textarea class="form-control" 
                                                  id="endereco" 
                                                  name="endereco" 
                                                  rows="3" 
                                                  placeholder="Rua, número, bairro, cidade - UF"></textarea>
                                        <small class="form-text text-muted">Opcional</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Informação:</strong>
                                <?php if ($tipo_cadastro === 'anestesista'): ?>
                                    Você será atribuído ao anestesista responsável indicado acima.
                                <?php else: ?>
                                    Você será contatado pela instituição para definir o anestesista responsável.
                                <?php endif; ?>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> 
                                    Realizar Cadastro
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <small class="text-white-50">
                        <i class="fas fa-shield-alt"></i>
                        Seus dados estão protegidos e serão utilizados apenas para fins médicos
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Máscara para CPF
            const cpfInput = document.getElementById('cpf');
            cpfInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/^(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
                    value = value.replace(/\.(\d{3})(\d)/, '.$1-$2');
                    this.value = value;
                }
            });
            
            // Máscara para telefone
            const telefoneInput = document.getElementById('telefone');
            telefoneInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    if (value.length <= 10) {
                        value = value.replace(/^(\d{2})(\d)/, '($1) $2');
                        value = value.replace(/(\d{4})(\d)/, '$1-$2');
                    } else {
                        value = value.replace(/^(\d{2})(\d)/, '($1) $2');
                        value = value.replace(/(\d{5})(\d)/, '$1-$2');
                    }
                    this.value = value;
                }
            });
            
            // Validação do formulário
            const form = document.getElementById('cadastroForm');
            form.addEventListener('submit', function(e) {
                const nome = document.getElementById('nome').value.trim();
                const cpf = document.getElementById('cpf').value.replace(/\D/g, '');
                const dataNascimento = document.getElementById('data_nascimento').value;
                const sexo = document.getElementById('sexo').value;
                const telefone = document.getElementById('telefone').value.replace(/\D/g, '');
                
                if (!nome || !cpf || !dataNascimento || !sexo || !telefone) {
                    e.preventDefault();
                    alert('Por favor, preencha todos os campos obrigatórios.');
                    return false;
                }
                
                if (cpf.length !== 11) {
                    e.preventDefault();
                    alert('CPF deve ter 11 dígitos.');
                    return false;
                }
                
                if (telefone.length < 10) {
                    e.preventDefault();
                    alert('Telefone deve ter pelo menos 10 dígitos.');
                    return false;
                }
            });
        });
    </script>
</body>
</html>
