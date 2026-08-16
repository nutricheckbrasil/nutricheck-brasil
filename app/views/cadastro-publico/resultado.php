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
        
        .resultado-card {
            text-align: center;
            padding: 40px 20px;
        }
        
        .success-icon {
            color: #28a745;
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        .error-icon {
            color: #dc3545;
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        .success-message {
            color: #28a745;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        
        .error-message {
            color: #dc3545;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        
        .institution-info {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
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
                            <i class="fas fa-<?= $tipo === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                            <?= $tipo === 'success' ? 'Cadastro Realizado!' : 'Erro no Cadastro' ?>
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <div class="resultado-card">
                            <?php if ($tipo === 'success'): ?>
                                <div class="success-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="success-message">
                                    <strong><?= htmlspecialchars($mensagem) ?></strong>
                                </div>
                                
                                <?php if (isset($instituicao_nome)): ?>
                                <div class="institution-info">
                                    <h5 class="text-primary mb-2">
                                        <i class="fas fa-hospital"></i>
                                        <?= htmlspecialchars($instituicao_nome) ?>
                                    </h5>
                                    <p class="mb-0">
                                        <small class="text-muted">
                                            Sua instituição entrará em contato em breve para mais informações.
                                        </small>
                                    </p>
                                </div>
                                <?php endif; ?>
                                
                                <div class="alert alert-info mb-3">
                                    <h6><i class="fas fa-envelope"></i> Email de Validação Enviado</h6>
                                    <p class="mb-0">
                                        <strong>Um email foi enviado para o endereço cadastrado.</strong><br>
                                        Verifique sua caixa de entrada (e também a pasta de spam) e siga as instruções para validar seu cadastro e preencher informações adicionais.
                                    </p>
                                </div>
                                
                                <div class="alert alert-success">
                                    <h6><i class="fas fa-info-circle"></i> Próximos Passos:</h6>
                                    <ul class="text-start mb-0">
                                        <li><strong>Aguarde o contato:</strong> Sua equipe de anestesia será notificada e entrará em contato com você em breve</li>
                                        <li><strong>Agendamento:</strong> Você receberá informações sobre data e horário do procedimento</li>
                                        <li><strong>Orientações:</strong> Siga todas as orientações pré-anestésicas fornecidas</li>
                                    </ul>
                                </div>
                                
                            <?php else: ?>
                                <div class="error-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="error-message">
                                    <strong><?= htmlspecialchars($mensagem) ?></strong>
                                </div>
                                
                                <div class="alert alert-danger">
                                    <h6><i class="fas fa-exclamation-circle"></i> O que fazer:</h6>
                                    <ul class="text-start mb-0">
                                        <li>Verifique se todos os dados foram preenchidos corretamente</li>
                                        <li>Certifique-se de que o CPF é válido</li>
                                        <li>Se o erro persistir, entre em contato com a instituição</li>
                                        <li>Você pode tentar novamente clicando no botão abaixo</li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mt-4">
                                <?php if ($tipo === 'success'): ?>
                                    <button type="button" class="btn btn-primary" onclick="window.close()">
                                        <i class="fas fa-times"></i> Fechar
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-primary" onclick="history.back()">
                                        <i class="fas fa-arrow-left"></i> Tentar Novamente
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <small class="text-white-50">
                        <i class="fas fa-shield-alt"></i>
                        Seus dados estão protegidos e serão utilizados apenas para fins médicos
                    </small>
                </div>
                
                <?php if ($tipo === 'success'): ?>
                <div class="text-center mt-3">
                    <small class="text-white-50">
                        <i class="fas fa-print"></i>
                        <a href="javascript:window.print()" class="text-white-50 text-decoration-none">
                            Imprimir esta confirmação
                        </a>
                    </small>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-close após 30 segundos se for sucesso
        <?php if ($tipo === 'success'): ?>
        setTimeout(function() {
            if (confirm('Deseja fechar esta janela?')) {
                window.close();
            }
        }, 30000);
        <?php endif; ?>
        
        // Adicionar efeito de impressão
        function printConfirmation() {
            window.print();
        }
        
        // Adicionar animação de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(function() {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>
