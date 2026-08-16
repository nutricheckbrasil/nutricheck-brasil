<div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Editar Anestesista</h2>
                <div>
                    <a href="<?= BASE_URL ?>/anestesistas/view/<?= $anestesista['id'] ?>" class="btn btn-outline-info">
                        <i class="bi bi-eye"></i> Visualizar
                    </a>
                    <a href="<?= BASE_URL ?>/anestesistas" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
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
                            <input type="text" class="form-control" id="nome" name="nome" required value="<?= htmlspecialchars($dados['nome'] ?? $anestesista['nome']) ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($dados['email'] ?? $anestesista['email']) ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="crm" class="form-label">CRM *</label>
                            <input type="text" class="form-control" id="crm" name="crm" required value="<?= htmlspecialchars($dados['crm'] ?? $anestesista['crm']) ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($dados['telefone'] ?? $anestesista['telefone']) ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="ativo" <?= ($dados['status'] ?? $anestesista['status']) === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                                <option value="inativo" <?= ($dados['status'] ?? $anestesista['status']) === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            <div class="form-text">Formatos aceitos: JPG, PNG, GIF (máx. 2MB)</div>
                            
                            <?php if (!empty($anestesista['foto_path'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted">Foto atual:</small><br>
                                    <img src="<?= BASE_URL ?>/<?= $anestesista['foto_path'] ?>" alt="Foto atual" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <h6><i class="bi bi-exclamation-triangle"></i> Aviso sobre Senha</h6>
                                <p class="mb-0">
                                    Para alterar a senha, o anestesista deve usar a funcionalidade "Esqueci minha senha" no login.
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save"></i> Salvar Alterações
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
