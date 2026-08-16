<div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="mb-0">Cadastrar Anestesista</h4>
                <a href="<?= BASE_URL ?>/anestesistas" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <h6><i class="bi bi-exclamation-triangle"></i> Erros encontrados:</h6>
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" class="row g-3">
                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome Completo *</label>
                            <input type="text" class="form-control" id="nome" name="nome" required value="<?= htmlspecialchars($dados['nome'] ?? '') ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($dados['email'] ?? '') ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="senha" class="form-label">Senha *</label>
                            <input type="password" class="form-control" id="senha" name="senha" required minlength="6">
                            <div class="form-text">Mínimo de 6 caracteres</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="crm" class="form-label">CRM *</label>
                            <input type="text" class="form-control" id="crm" name="crm" required value="<?= htmlspecialchars($dados['crm'] ?? '') ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($dados['telefone'] ?? '') ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            <div class="form-text">Formatos aceitos: JPG, PNG, GIF (máx. 2MB)</div>
                        </div>
                        
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="bi bi-info-circle"></i> Informações sobre QR Code</h6>
                                <p class="mb-0">
                                    Após o cadastro, será gerado automaticamente um QR Code único para este anestesista. 
                                    Este QR Code permitirá que pacientes se cadastrem diretamente associados a este anestesista.
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save"></i> Cadastrar Anestesista
                            </button>
                        </div>
                    </form>
                </div>
            </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(function() {
    $('#telefone').mask('(00) 00000-0000');
    $('#crm').mask('000000');
});
</script>