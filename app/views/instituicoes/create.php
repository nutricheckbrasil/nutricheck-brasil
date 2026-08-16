<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Cadastrar Instituição</h2>
        <a href="<?= BASE_URL ?>/instituicoes" class="btn btn-outline-secondary">
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
                    <label for="nome" class="form-label">Nome da Instituição *</label>
                    <input type="text" class="form-control" id="nome" name="nome" required value="<?= htmlspecialchars($dados['nome'] ?? '') ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="cnpj" class="form-label">CNPJ *</label>
                    <input type="text" class="form-control" id="cnpj" name="cnpj" required value="<?= htmlspecialchars($dados['cnpj'] ?? '') ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($dados['email'] ?? '') ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="senha" class="form-label">Senha Inicial *</label>
                    <input type="password" class="form-control" id="senha" name="senha" required minlength="6">
                    <div class="form-text">Mínimo de 6 caracteres</div>
                </div>
                
                <div class="col-md-6">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($dados['telefone'] ?? '') ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="responsavel" class="form-label">Responsável pela Instituição *</label>
                    <input type="text" class="form-control" id="responsavel" name="responsavel" required value="<?= htmlspecialchars($dados['responsavel'] ?? '') ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="cargo" class="form-label">Cargo/Função *</label>
                    <input type="text" class="form-control" id="cargo" name="cargo" required value="<?= htmlspecialchars($dados['cargo'] ?? '') ?>">
                </div>
                
                <!-- Seção de Endereço -->
                <div class="col-12">
                    <hr>
                    <h5 class="mb-3"><i class="bi bi-geo-alt"></i> Endereço</h5>
                </div>
                
                <div class="col-md-3">
                    <label for="cep" class="form-label">CEP</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="cep" name="cep" value="<?= htmlspecialchars($dados['cep'] ?? '') ?>" placeholder="00000-000">
                        <button type="button" class="btn btn-outline-primary" onclick="buscarCep()">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <div class="form-text">Clique no botão de busca para preencher automaticamente</div>
                </div>
                
                <div class="col-md-9">
                    <label for="logradouro" class="form-label">Logradouro</label>
                    <input type="text" class="form-control" id="logradouro" name="logradouro" value="<?= htmlspecialchars($dados['logradouro'] ?? '') ?>" placeholder="Rua, Avenida, etc.">
                </div>
                
                <div class="col-md-3">
                    <label for="numero" class="form-label">Número</label>
                    <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($dados['numero'] ?? '') ?>" placeholder="123">
                </div>
                
                <div class="col-md-5">
                    <label for="complemento" class="form-label">Complemento</label>
                    <input type="text" class="form-control" id="complemento" name="complemento" value="<?= htmlspecialchars($dados['complemento'] ?? '') ?>" placeholder="Apartamento, sala, etc.">
                </div>
                
                <div class="col-md-4">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" class="form-control" id="bairro" name="bairro" value="<?= htmlspecialchars($dados['bairro'] ?? '') ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="cidade" name="cidade" value="<?= htmlspecialchars($dados['cidade'] ?? '') ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="">Selecione o estado</option>
                        <option value="AC" <?= ($dados['estado'] ?? '') === 'AC' ? 'selected' : '' ?>>Acre</option>
                        <option value="AL" <?= ($dados['estado'] ?? '') === 'AL' ? 'selected' : '' ?>>Alagoas</option>
                        <option value="AP" <?= ($dados['estado'] ?? '') === 'AP' ? 'selected' : '' ?>>Amapá</option>
                        <option value="AM" <?= ($dados['estado'] ?? '') === 'AM' ? 'selected' : '' ?>>Amazonas</option>
                        <option value="BA" <?= ($dados['estado'] ?? '') === 'BA' ? 'selected' : '' ?>>Bahia</option>
                        <option value="CE" <?= ($dados['estado'] ?? '') === 'CE' ? 'selected' : '' ?>>Ceará</option>
                        <option value="DF" <?= ($dados['estado'] ?? '') === 'DF' ? 'selected' : '' ?>>Distrito Federal</option>
                        <option value="ES" <?= ($dados['estado'] ?? '') === 'ES' ? 'selected' : '' ?>>Espírito Santo</option>
                        <option value="GO" <?= ($dados['estado'] ?? '') === 'GO' ? 'selected' : '' ?>>Goiás</option>
                        <option value="MA" <?= ($dados['estado'] ?? '') === 'MA' ? 'selected' : '' ?>>Maranhão</option>
                        <option value="MT" <?= ($dados['estado'] ?? '') === 'MT' ? 'selected' : '' ?>>Mato Grosso</option>
                        <option value="MS" <?= ($dados['estado'] ?? '') === 'MS' ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                        <option value="MG" <?= ($dados['estado'] ?? '') === 'MG' ? 'selected' : '' ?>>Minas Gerais</option>
                        <option value="PA" <?= ($dados['estado'] ?? '') === 'PA' ? 'selected' : '' ?>>Pará</option>
                        <option value="PB" <?= ($dados['estado'] ?? '') === 'PB' ? 'selected' : '' ?>>Paraíba</option>
                        <option value="PR" <?= ($dados['estado'] ?? '') === 'PR' ? 'selected' : '' ?>>Paraná</option>
                        <option value="PE" <?= ($dados['estado'] ?? '') === 'PE' ? 'selected' : '' ?>>Pernambuco</option>
                        <option value="PI" <?= ($dados['estado'] ?? '') === 'PI' ? 'selected' : '' ?>>Piauí</option>
                        <option value="RJ" <?= ($dados['estado'] ?? '') === 'RJ' ? 'selected' : '' ?>>Rio de Janeiro</option>
                        <option value="RN" <?= ($dados['estado'] ?? '') === 'RN' ? 'selected' : '' ?>>Rio Grande do Norte</option>
                        <option value="RS" <?= ($dados['estado'] ?? '') === 'RS' ? 'selected' : '' ?>>Rio Grande do Sul</option>
                        <option value="RO" <?= ($dados['estado'] ?? '') === 'RO' ? 'selected' : '' ?>>Rondônia</option>
                        <option value="RR" <?= ($dados['estado'] ?? '') === 'RR' ? 'selected' : '' ?>>Roraima</option>
                        <option value="SC" <?= ($dados['estado'] ?? '') === 'SC' ? 'selected' : '' ?>>Santa Catarina</option>
                        <option value="SP" <?= ($dados['estado'] ?? '') === 'SP' ? 'selected' : '' ?>>São Paulo</option>
                        <option value="SE" <?= ($dados['estado'] ?? '') === 'SE' ? 'selected' : '' ?>>Sergipe</option>
                        <option value="TO" <?= ($dados['estado'] ?? '') === 'TO' ? 'selected' : '' ?>>Tocantins</option>
                    </select>
                </div>
                
                <!-- Campo oculto para armazenar o endereço completo -->
                <input type="hidden" id="endereco" name="endereco" value="<?= htmlspecialchars($dados['endereco'] ?? '') ?>">
                
                <div class="col-md-12">
                    <label for="foto" class="form-label">Foto da Instituição</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                    <div class="form-text">Foto principal da instituição (Formatos aceitos: JPG, PNG, GIF - máx. 2MB)</div>
                </div>
                
                <div class="col-12">
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Informações importantes:</h6>
                        <ul class="mb-0">
                            <li>O <strong>CNPJ</strong> deve ser único no sistema</li>
                            <li>O <strong>email</strong> será usado para comunicação</li>
                            <li>Um <strong>QR Code</strong> será gerado automaticamente</li>
                            <li>A instituição será criada como <strong>ativa</strong></li>
                            <li>Use a <strong>busca por CEP</strong> para preencher automaticamente os dados de endereço</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Seção de QR Code -->
                <div class="col-12">
                    <hr>
                    <h5 class="mb-3"><i class="bi bi-qr-code"></i> QR Code da Instituição</h5>
                    <div class="alert alert-success">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle text-success me-3 fs-4"></i>
                            <div>
                                <h6 class="text-success mb-2">
                                    <strong>QR Code será gerado automaticamente</strong>
                                </h6>
                                <p class="text-muted mb-0">
                                    Após o cadastro da instituição, um QR Code público será gerado automaticamente 
                                    para permitir que pacientes se cadastrem diretamente via link público. 
                                    O QR Code ficará disponível na página de visualização da instituição.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= BASE_URL ?>/instituicoes" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" onclick="montarEndereco()">
                            <i class="bi bi-check-circle"></i> Cadastrar Instituição
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Máscara para CNPJ
document.getElementById('cnpj').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/^(\d{2})(\d)/, '$1.$2');
    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
    value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
    value = value.replace(/(\d{4})(\d)/, '$1-$2');
    e.target.value = value;
});

// Máscara para telefone
document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 10) {
        value = value.replace(/^(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{4})(\d)/, '$1-$2');
    } else {
        value = value.replace(/^(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
    }
    e.target.value = value;
});

// Máscara para CEP
document.getElementById('cep').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/^(\d{5})(\d)/, '$1-$2');
    e.target.value = value;
});

// Função para buscar CEP
async function buscarCep() {
    const cep = document.getElementById('cep').value.replace(/\D/g, '');
    
    if (cep.length !== 8) {
        alert('CEP deve ter 8 dígitos');
        return;
    }
    
    // Mostrar loading
    const btn = event.target;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
    btn.disabled = true;
    
    try {
        // Usar a API ViaCEP
        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        const data = await response.json();
        
        if (data.erro) {
            alert('CEP não encontrado');
            return;
        }
        
        // Preencher os campos
        document.getElementById('logradouro').value = data.logradouro || '';
        document.getElementById('bairro').value = data.bairro || '';
        document.getElementById('cidade').value = data.localidade || '';
        document.getElementById('estado').value = data.uf || '';
        
        // Focar no campo número
        document.getElementById('numero').focus();
        
    } catch (error) {
        console.error('Erro ao buscar CEP:', error);
        alert('Erro ao buscar CEP. Tente novamente.');
    } finally {
        // Restaurar botão
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
}

// Buscar CEP automaticamente quando digitar 8 dígitos
document.getElementById('cep').addEventListener('input', function(e) {
    const cep = e.target.value.replace(/\D/g, '');
    if (cep.length === 8) {
        // Aguardar um pouco antes de buscar para não sobrecarregar a API
        setTimeout(() => {
            if (e.target.value.replace(/\D/g, '').length === 8) {
                buscarCep();
            }
        }, 500);
    }
});

// Função para montar o endereço completo antes de enviar
function montarEndereco() {
    const logradouro = document.getElementById('logradouro').value;
    const numero = document.getElementById('numero').value;
    const complemento = document.getElementById('complemento').value;
    const bairro = document.getElementById('bairro').value;
    const cidade = document.getElementById('cidade').value;
    const estado = document.getElementById('estado').value;
    const cep = document.getElementById('cep').value;
    
    let enderecoCompleto = '';
    
    if (logradouro) {
        enderecoCompleto = logradouro;
        if (numero) {
            enderecoCompleto += ', ' + numero;
        }
        if (complemento) {
            enderecoCompleto += ', ' + complemento;
        }
        if (bairro) {
            enderecoCompleto += ', ' + bairro;
        }
        if (cidade && estado) {
            enderecoCompleto += ', ' + cidade + '/' + estado;
        }
        if (cep) {
            enderecoCompleto += ', CEP: ' + cep;
        }
    }
    
    document.getElementById('endereco').value = enderecoCompleto;
}

// Validação de arquivos
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file && file.size > 2 * 1024 * 1024) {
        alert('O arquivo de foto deve ter no máximo 2MB');
        e.target.value = '';
    }
});
</script>