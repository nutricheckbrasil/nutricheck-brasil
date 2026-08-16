<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($title) ?><<?= BASE_URL ?>/title>
    <link href="https:<?= BASE_URL ?>/<?= BASE_URL ?>/cdn.jsdelivr.net<?= BASE_URL ?>/npm<?= BASE_URL ?>/bootstrap@5.1.3<?= BASE_URL ?>/dist<?= BASE_URL ?>/css<?= BASE_URL ?>/bootstrap.min.css" rel="stylesheet">
    <link href="https:<?= BASE_URL ?>/<?= BASE_URL ?>/cdnjs.cloudflare.com<?= BASE_URL ?>/ajax<?= BASE_URL ?>/libs<?= BASE_URL ?>/font-awesome<?= BASE_URL ?>/6.0.0<?= BASE_URL ?>/css<?= BASE_URL ?>/all.min.css" rel="stylesheet">
    <style>
        <?= BASE_URL ?>/* MOBILE-FIRST DESIGN *<?= BASE_URL ?>/
        * {
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .container {
            padding: 10px;
            max-width: 100%;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            margin: 0;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 20px 15px;
            text-align: center;
        }
        
        .card-header h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }
        
        .card-header p {
            font-size: 0.9rem;
            margin: 8px 0 0 0;
            opacity: 0.9;
        }
        
        .card-body {
            padding: 20px 15px;
        }
        
        .institution-info {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }
        
        .institution-info h5 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #667eea;
        }
        
        .institution-info p {
            font-size: 0.85rem;
            margin: 4px 0;
            color: #6c757d;
        }
        
        .anestesista-info {
            background: rgba(118, 75, 162, 0.1);
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #764ba2;
        }
        
        .anestesista-info h6 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #764ba2;
        }
        
        .anestesista-info p {
            font-size: 0.9rem;
            margin: 0;
            font-weight: 500;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 6px;
            font-size: 0.95rem;
        }
        
        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 14px 16px;
            font-size: 16px; <?= BASE_URL ?>/* Previne zoom no iOS *<?= BASE_URL ?>/
            height: auto;
            min-height: 50px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .form-group {
            margin-bottom: 18px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 16px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            min-height: 55px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px;
            margin: 15px 0;
        }
        
        .alert-info {
            background: rgba(13, 202, 240, 0.1);
            color: #0c5460;
            border-left: 4px solid #0dcaf0;
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 8px;
            color: #667eea;
        }
        
        .form-text {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 4px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .mt-4 {
            margin-top: 1.5rem;
        }
        
        .mb-3 {
            margin-bottom: 1rem;
        }
        
        <?= BASE_URL ?>/* Melhorias para touch *<?= BASE_URL ?>/
        .form-control, .form-select, .btn {
            -webkit-tap-highlight-color: transparent;
        }
        
        <?= BASE_URL ?>/* Animações suaves *<?= BASE_URL ?>/
        .card {
            animation: slideUp 0.5s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        <?= BASE_URL ?>/* Responsividade para telas maiores *<?= BASE_URL ?>/
        @media (min-width: 768px) {
            .container {
                max-width: 500px;
                margin: 0 auto;
                padding: 20px;
            }
            
            .card-header h3 {
                font-size: 1.6rem;
            }
            
            .form-control, .form-select {
                font-size: 16px;
            }
        }
        
        <?= BASE_URL ?>/* Melhorias para iOS *<?= BASE_URL ?>/
        @supports (-webkit-touch-callout: none) {
            .form-control, .form-select {
                font-size: 16px;
            }
        }
    <<?= BASE_URL ?>/style>
<<?= BASE_URL ?>/head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>
                    <i class="fas fa-user-plus"><<?= BASE_URL ?>/i>
                    Cadastro de Paciente
                <<?= BASE_URL ?>/h3>
                <p>Preencha seus dados para iniciar o processo de anestesia<<?= BASE_URL ?>/p>
            <<?= BASE_URL ?>/div>
            
            <div class="card-body">
                <!-- Informações da Instituição -->
                <div class="institution-info">
                    <h5>
                        <i class="fas fa-hospital"><<?= BASE_URL ?>/i>
                        <?= htmlspecialchars($instituicao['nome']) ?>
                    <<?= BASE_URL ?>/h5>
                    <?php if (!empty($instituicao['endereco'])): ?>
                    <p>
                        <i class="fas fa-map-marker-alt"><<?= BASE_URL ?>/i>
                        <?= htmlspecialchars($instituicao['endereco']) ?>
                    <<?= BASE_URL ?>/p>
                    <?php endif; ?>
                    <?php if (!empty($instituicao['telefone'])): ?>
                    <p>
                        <i class="fas fa-phone"><<?= BASE_URL ?>/i>
                        <?= htmlspecialchars($instituicao['telefone']) ?>
                    <<?= BASE_URL ?>/p>
                    <?php endif; ?>
                <<?= BASE_URL ?>/div>
                
                <!-- Informações do Nutricionista (se aplicável) -->
                <?php if ($tipo_cadastro === 'anestesista' && isset($anestesista)): ?>
                <div class="anestesista-info">
                    <h6>
                        <i class="fas fa-user-md"><<?= BASE_URL ?>/i>
                        Nutricionista Responsável
                    <<?= BASE_URL ?>/h6>
                    <p>
                        <strong><?= htmlspecialchars($anestesista['nome']) ?><<?= BASE_URL ?>/strong>
                        <?php if (!empty($anestesista['crm'])): ?>
                        <br><small>CRM: <?= htmlspecialchars($anestesista['crm']) ?><<?= BASE_URL ?>/small>
                        <?php endif; ?>
                    <<?= BASE_URL ?>/p>
                <<?= BASE_URL ?>/div>
                <?php endif; ?>
                
                <form method="POST" id="cadastroForm">
                    <!-- Dados Pessoais -->
                    <div class="section-title">
                        <i class="fas fa-user"><<?= BASE_URL ?>/i>
                        Dados Pessoais
                    <<?= BASE_URL ?>/div>
                    
                    <div class="form-group">
                        <label for="nome" class="form-label">Nome *<<?= BASE_URL ?>/label>
                        <input type="text" 
                               class="form-control" 
                               id="nome" 
                               name="nome" 
                               required 
                               placeholder="Seu nome completo"
                               autocomplete="given-name">
                    <<?= BASE_URL ?>/div>
                    
                    <div class="form-group">
                        <label for="sobrenome" class="form-label">Sobrenome<<?= BASE_URL ?>/label>
                        <input type="text" 
                               class="form-control" 
                               id="sobrenome" 
                               name="sobrenome" 
                               placeholder="Seu sobrenome"
                               autocomplete="family-name">
                    <<?= BASE_URL ?>/div>
                    
                    <div class="form-group">
                        <label for="cpf" class="form-label">CPF *<<?= BASE_URL ?>/label>
                        <input type="text" 
                               class="form-control" 
                               id="cpf" 
                               name="cpf" 
                               required 
                               placeholder="000.000.000-00"
                               maxlength="14"
                               autocomplete="off">
                    <<?= BASE_URL ?>/div>
                    
                    <div class="form-group">
                        <label for="data_nascimento" class="form-label">Data de Nascimento *<<?= BASE_URL ?>/label>
                        <input type="date" 
                               class="form-control" 
                               id="data_nascimento" 
                               name="data_nascimento" 
                               required
                               autocomplete="bday">
                    <<?= BASE_URL ?>/div>
                    
                    <div class="form-group">
                        <label for="sexo" class="form-label">Sexo *<<?= BASE_URL ?>/label>
                        <select class="form-select" id="sexo" name="sexo" required>
                            <option value="">Selecione...<<?= BASE_URL ?>/option>
                            <option value="M">Masculino<<?= BASE_URL ?>/option>
                            <option value="F">Feminino<<?= BASE_URL ?>/option>
                            <option value="O">Outro<<?= BASE_URL ?>/option>
                        <<?= BASE_URL ?>/select>
                    <<?= BASE_URL ?>/div>
                    
                    <div class="form-group">
                        <label for="telefone" class="form-label">Telefone *<<?= BASE_URL ?>/label>
                        <input type="tel" 
                               class="form-control" 
                               id="telefone" 
                               name="telefone" 
                               required 
                               placeholder="(00) 00000-0000"
                               maxlength="15"
                               autocomplete="tel">
                    <<?= BASE_URL ?>/div>
                    
                    <!-- Contato -->
                    <div class="section-title">
                        <i class="fas fa-envelope"><<?= BASE_URL ?>/i>
                        Contato
                    <<?= BASE_URL ?>/div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email<<?= BASE_URL ?>/label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               placeholder="seu@email.com"
                               autocomplete="email">
                        <small class="form-text">Opcional, mas recomendado para contato<<?= BASE_URL ?>/small>
                    <<?= BASE_URL ?>/div>
                    
                    <div class="form-group">
                        <label for="endereco" class="form-label">Endereço<<?= BASE_URL ?>/label>
                        <textarea class="form-control" 
                                  id="endereco" 
                                  name="endereco" 
                                  rows="3" 
                                  placeholder="Rua, número, bairro, cidade - UF"
                                  autocomplete="street-address"><<?= BASE_URL ?>/textarea>
                        <small class="form-text">Opcional<<?= BASE_URL ?>/small>
                    <<?= BASE_URL ?>/div>
                    
                    <!-- Procedimento -->
                    <?php if (isset($mostrar_data_procedimento) && $mostrar_data_procedimento): ?>
                    <div class="section-title">
                        <i class="fas fa-calendar-alt"><<?= BASE_URL ?>/i>
                        Procedimento
                    <<?= BASE_URL ?>/div>
                    
                    <div class="form-group">
                        <label for="data_procedimento" class="form-label">Data do Procedimento<<?= BASE_URL ?>/label>
                        <input type="date" 
                               class="form-control" 
                               id="data_procedimento" 
                               name="data_procedimento"
                               min="<?= date('Y-m-d') ?>">
                        <small class="form-text">
                            <i class="fas fa-info-circle"><<?= BASE_URL ?>/i>
                            Opcional. Se você já sabe a data do procedimento, informe aqui. 
                            Caso contrário, o anestesista fará o agendamento posteriormente.
                        <<?= BASE_URL ?>/small>
                    <<?= BASE_URL ?>/div>
                    <?php endif; ?>
                    
                    <!-- Informações Importantes -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"><<?= BASE_URL ?>/i>
                        <strong>Importante:<<?= BASE_URL ?>/strong>
                        <?php if ($tipo_cadastro === 'anestesista'): ?>
                            Após o cadastro, você será atribuído ao anestesista responsável indicado acima.
                            Sua equipe de anestesia será notificada e entrará em contato com você em breve.
                        <?php else: ?>
                            Após o cadastro, sua equipe de anestesia será notificada e entrará em contato 
                            com você em breve para definir o anestesista responsável.
                        <?php endif; ?>
                    <<?= BASE_URL ?>/div>
                    
                    <!-- Botão de Envio -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle"><<?= BASE_URL ?>/i> 
                            Realizar Cadastro
                        <<?= BASE_URL ?>/button>
                    <<?= BASE_URL ?>/div>
                <<?= BASE_URL ?>/form>
            <<?= BASE_URL ?>/div>
        <<?= BASE_URL ?>/div>
        
        <!-- Rodapé -->
        <div class="text-center mt-4">
            <small style="color: rgba(255,255,255,0.8);">
                <i class="fas fa-shield-alt"><<?= BASE_URL ?>/i>
                Seus dados estão protegidos e serão utilizados apenas para fins médicos, 
                conforme a Lei Geral de Proteção de Dados (LGPD)
            <<?= BASE_URL ?>/small>
        <<?= BASE_URL ?>/div>
    <<?= BASE_URL ?>/div>
    
    <script src="https:<?= BASE_URL ?>/<?= BASE_URL ?>/cdn.jsdelivr.net<?= BASE_URL ?>/npm<?= BASE_URL ?>/bootstrap@5.1.3<?= BASE_URL ?>/dist<?= BASE_URL ?>/js<?= BASE_URL ?>/bootstrap.bundle.min.js"><<?= BASE_URL ?>/script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?= BASE_URL ?>/<?= BASE_URL ?>/ Máscara para CPF (mobile-friendly)
            const cpfInput = document.getElementById('cpf');
            cpfInput.addEventListener('input', function() {
                let value = this.value.replace(<?= BASE_URL ?>/\D<?= BASE_URL ?>/g, '');
                if (value.length <= 11) {
                    value = value.replace(<?= BASE_URL ?>/^(\d{3})(\d)<?= BASE_URL ?>/, '$1.$2');
                    value = value.replace(<?= BASE_URL ?>/^(\d{3})\.(\d{3})(\d)<?= BASE_URL ?>/, '$1.$2.$3');
                    value = value.replace(<?= BASE_URL ?>/\.(\d{3})(\d)<?= BASE_URL ?>/, '.$1-$2');
                    this.value = value;
                }
            });
            
            <?= BASE_URL ?>/<?= BASE_URL ?>/ Máscara para telefone (mobile-friendly)
            const telefoneInput = document.getElementById('telefone');
            telefoneInput.addEventListener('input', function() {
                let value = this.value.replace(<?= BASE_URL ?>/\D<?= BASE_URL ?>/g, '');
                if (value.length <= 11) {
                    if (value.length <= 10) {
                        value = value.replace(<?= BASE_URL ?>/^(\d{2})(\d)<?= BASE_URL ?>/, '($1) $2');
                        value = value.replace(<?= BASE_URL ?>/(\d{4})(\d)<?= BASE_URL ?>/, '$1-$2');
                    } else {
                        value = value.replace(<?= BASE_URL ?>/^(\d{2})(\d)<?= BASE_URL ?>/, '($1) $2');
                        value = value.replace(<?= BASE_URL ?>/(\d{5})(\d)<?= BASE_URL ?>/, '$1-$2');
                    }
                    this.value = value;
                }
            });
            
            <?= BASE_URL ?>/<?= BASE_URL ?>/ Melhorar UX mobile
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                <?= BASE_URL ?>/<?= BASE_URL ?>/ Remover zoom no iOS ao focar
                input.addEventListener('focus', function() {
                    if (window.innerWidth <= 768) {
                        const viewport = document.querySelector('meta[name="viewport"]');
                        viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
                    }
                });
                
                <?= BASE_URL ?>/<?= BASE_URL ?>/ Restaurar zoom após sair do campo
                input.addEventListener('blur', function() {
                    if (window.innerWidth <= 768) {
                        const viewport = document.querySelector('meta[name="viewport"]');
                        viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, user-scalable=no');
                    }
                });
            });
            
            <?= BASE_URL ?>/<?= BASE_URL ?>/ Validação em tempo real (mobile-friendly)
            function validateField(field, validator) {
                const value = field.value.trim();
                const isValid = validator(value);
                
                if (isValid) {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                } else {
                    field.classList.remove('is-valid');
                    field.classList.add('is-invalid');
                }
                
                return isValid;
            }
            
            <?= BASE_URL ?>/<?= BASE_URL ?>/ Validadores
            const validators = {
                nome: (value) => value.length >= 2,
                cpf: (value) => {
                    const cpf = value.replace(<?= BASE_URL ?>/\D<?= BASE_URL ?>/g, '');
                    return cpf.length === 11;
                },
                telefone: (value) => {
                    const tel = value.replace(<?= BASE_URL ?>/\D<?= BASE_URL ?>/g, '');
                    return tel.length >= 10;
                },
                email: (value) => {
                    if (!value) return true; <?= BASE_URL ?>/<?= BASE_URL ?>/ Email é opcional
                    return <?= BASE_URL ?>/^[^\s@]+@[^\s@]+\.[^\s@]+$<?= BASE_URL ?>/.test(value);
                }
            };
            
            <?= BASE_URL ?>/<?= BASE_URL ?>/ Aplicar validação em tempo real
            Object.keys(validators).forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field) {
                    field.addEventListener('blur', function() {
                        validateField(this, validators[fieldName]);
                    });
                }
            });
            
            <?= BASE_URL ?>/<?= BASE_URL ?>/ Validação do formulário (mobile-friendly)
            const form = document.getElementById('cadastroForm');
            form.addEventListener('submit', function(e) {
                const nome = document.getElementById('nome').value.trim();
                const cpf = document.getElementById('cpf').value.replace(<?= BASE_URL ?>/\D<?= BASE_URL ?>/g, '');
                const dataNascimento = document.getElementById('data_nascimento').value;
                const sexo = document.getElementById('sexo').value;
                const telefone = document.getElementById('telefone').value.replace(<?= BASE_URL ?>/\D<?= BASE_URL ?>/g, '');
                const email = document.getElementById('email').value.trim();
                
                let errors = [];
                
                <?= BASE_URL ?>/<?= BASE_URL ?>/ Validar campos obrigatórios
                if (!nome) errors.push('Nome é obrigatório');
                if (!cpf) errors.push('CPF é obrigatório');
                if (!dataNascimento) errors.push('Data de nascimento é obrigatória');
                if (!sexo) errors.push('Sexo é obrigatório');
                if (!telefone) errors.push('Telefone é obrigatório');
                
                <?= BASE_URL ?>/<?= BASE_URL ?>/ Validar formato CPF
                if (cpf && cpf.length !== 11) {
                    errors.push('CPF deve ter 11 dígitos');
                }
                
                <?= BASE_URL ?>/<?= BASE_URL ?>/ Validar formato telefone
                if (telefone && telefone.length < 10) {
                    errors.push('Telefone deve ter pelo menos 10 dígitos');
                }
                
                <?= BASE_URL ?>/<?= BASE_URL ?>/ Validar email se preenchido
                if (email && !<?= BASE_URL ?>/^[^\s@]+@[^\s@]+\.[^\s@]+$<?= BASE_URL ?>/.test(email)) {
                    errors.push('Email inválido');
                }
                
                <?= BASE_URL ?>/<?= BASE_URL ?>/ Validar data de nascimento
                if (dataNascimento) {
                    const hoje = new Date();
                    const nascimento = new Date(dataNascimento);
                    
                    if (nascimento > hoje) {
                        errors.push('Data de nascimento não pode ser no futuro');
                    }
                    
                    const idade = hoje.getFullYear() - nascimento.getFullYear();
                    if (idade > 150) {
                        errors.push('Data de nascimento inválida');
                    }
                }
                
                if (errors.length > 0) {
                    e.preventDefault();
                    
                    <?= BASE_URL ?>/<?= BASE_URL ?>/ Mostrar erros de forma mobile-friendly
                    let errorMessage = 'Por favor, corrija os seguintes erros:\n\n';
                    errors.forEach((error, index) => {
                        errorMessage += `${index + 1}. ${error}\n`;
                    });
                    
                    alert(errorMessage);
                    return false;
                }
                
                <?= BASE_URL ?>/<?= BASE_URL ?>/ Mostrar loading no botão
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"><<?= BASE_URL ?>/i> Processando...';
                submitBtn.disabled = true;
                
                <?= BASE_URL ?>/<?= BASE_URL ?>/ Reabilitar botão após 10 segundos (fallback)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 10000);
            });
            
            <?= BASE_URL ?>/<?= BASE_URL ?>/ Melhorar acessibilidade mobile
            const labels = document.querySelectorAll('label');
            labels.forEach(label => {
                label.addEventListener('click', function() {
                    const input = document.getElementById(this.getAttribute('for'));
                    if (input) {
                        input.focus();
                    }
                });
            });
            
            <?= BASE_URL ?>/<?= BASE_URL ?>/ Prevenir double-tap zoom em botões
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('touchend', function(e) {
                    e.preventDefault();
                });
            });
        });
    <<?= BASE_URL ?>/script>
<<?= BASE_URL ?>/body>
<<?= BASE_URL ?>/html>

