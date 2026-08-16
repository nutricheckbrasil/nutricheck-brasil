<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Editar Instituição</h2>
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
                    <input type="text" class="form-control" id="nome" name="nome" required value="<?= htmlspecialchars($instituicao['nome']) ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="cnpj" class="form-label">CNPJ *</label>
                    <input type="text" class="form-control" id="cnpj" name="cnpj" required value="<?= htmlspecialchars($instituicao['cnpj']) ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($instituicao['email']) ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="senha" class="form-label">Nova Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" minlength="6">
                    <div class="form-text">Deixe em branco para manter a senha atual</div>
                </div>
                
                <div class="col-md-6">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($instituicao['telefone'] ?? '') ?>">
                </div>
                
                <!-- Seção de Endereço -->
                <div class="col-12">
                    <hr>
                    <h5 class="mb-3"><i class="bi bi-geo-alt"></i> Endereço</h5>
                </div>
                
                <div class="col-md-3">
                    <label for="cep" class="form-label">CEP</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000">
                        <button type="button" class="btn btn-outline-primary" onclick="buscarCep()">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <div class="form-text">Clique no botão de busca para preencher automaticamente</div>
                </div>
                
                <div class="col-md-9">
                    <label for="logradouro" class="form-label">Logradouro</label>
                    <input type="text" class="form-control" id="logradouro" name="logradouro" placeholder="Rua, Avenida, etc.">
                </div>
                
                <div class="col-md-3">
                    <label for="numero" class="form-label">Número</label>
                    <input type="text" class="form-control" id="numero" name="numero" placeholder="123">
                </div>
                
                <div class="col-md-5">
                    <label for="complemento" class="form-label">Complemento</label>
                    <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Apartamento, sala, etc.">
                </div>
                
                <div class="col-md-4">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" class="form-control" id="bairro" name="bairro">
                </div>
                
                <div class="col-md-6">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="cidade" name="cidade">
                </div>
                
                <div class="col-md-6">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="">Selecione o estado</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>
                    </select>
                </div>
                
                <!-- Campo oculto para armazenar o endereço completo -->
                <input type="hidden" id="endereco" name="endereco" value="<?= htmlspecialchars($instituicao['endereco'] ?? '') ?>">
                
                <div class="col-md-6">
                    <label for="responsavel" class="form-label">Responsável pela Instituição *</label>
                    <input type="text" class="form-control" id="responsavel" name="responsavel" required value="<?= htmlspecialchars($instituicao['responsavel'] ?? '') ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="cargo" class="form-label">Cargo/Função *</label>
                    <input type="text" class="form-control" id="cargo" name="cargo" required value="<?= htmlspecialchars($instituicao['cargo'] ?? '') ?>">
                </div>
                
                <div class="col-md-12">
                    <label for="foto" class="form-label">Foto da Instituição</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                    <?php if (!empty($instituicao['foto_path'])): ?>
                        <div class="mt-2">
                            <img src="<?= BASE_URL ?>/<?= $instituicao['foto_path'] ?>" 
                                 alt="Foto atual" class="img-thumbnail" style="max-width: 150px;">
                            <small class="text-muted d-block">Foto atual</small>
                        </div>
                    <?php endif; ?>
                    <div class="form-text">Deixe em branco para manter a foto atual (Formatos aceitos: JPG, PNG, GIF - máx. 2MB)</div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="ativo" name="ativo" 
                               <?= $instituicao['ativo'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="ativo">
                            Instituição Ativa
                        </label>
                    </div>
                    <div class="form-text">
                        Instituições inativas não podem ser usadas no sistema
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="alert alert-warning">
                        <h6><i class="bi bi-exclamation-triangle"></i> Atenção:</h6>
                        <ul class="mb-0">
                            <li>Alterar o <strong>CNPJ</strong> pode afetar integrações existentes</li>
                            <li>Alterar o <strong>email</strong> afetará as comunicações</li>
                            <li>Desativar a instituição impedirá novos cadastros</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= BASE_URL ?>/instituicoes" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" onclick="montarEndereco()">
                            <i class="bi bi-check-circle"></i> Salvar Alterações
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

// Adicionar função de montar endereço ao submit
document.querySelector('form').addEventListener('submit', function(e) {
    montarEndereco();
});
</script>