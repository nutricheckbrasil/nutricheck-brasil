<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="mb-0">
            <i class="bi bi-shield-check text-primary"></i> Permissionamento de Páginas
        </h2>
        <div class="mt-3 mt-md-0">
            <button type="button" class="btn btn-outline-info me-2" onclick="sincronizarPaginas()">
                <i class="bi bi-arrow-clockwise"></i> Sincronizar Páginas
            </button>
            <button type="button" class="btn btn-primary" onclick="salvarPermissoes()">
                <i class="bi bi-save"></i> Salvar Permissões
            </button>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_paginas'] ?></h4>
                            <small>Total de Páginas</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-file-earmark" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_permissoes'] ?></h4>
                            <small>Permissões Ativas</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['total_perfis'] ?></h4>
                            <small>Perfis de Usuário</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $stats['paginas_sem_permissoes'] ?></h4>
                            <small>Sem Permissões</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Permissões -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-table me-2"></i>Matriz de Permissões
            </h5>
        </div>
        <div class="card-body">
            <?php 
            // Debug temporário
            echo "<!-- DEBUG: Páginas vazias? " . (empty($paginas) ? 'SIM' : 'NÃO') . " -->";
            echo "<!-- DEBUG: Perfis vazios? " . (empty($perfis) ? 'SIM' : 'NÃO') . " -->";
            echo "<!-- DEBUG: Páginas count: " . (is_array($paginas) ? count($paginas) : 'NÃO É ARRAY') . " -->";
            echo "<!-- DEBUG: Perfis count: " . (is_array($perfis) ? count($perfis) : 'NÃO É ARRAY') . " -->";
            ?>
            <?php if (!empty($paginas) && !empty($perfis)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 30%;">Página</th>
                                <?php foreach ($perfis as $perfil): ?>
                                    <th class="text-center" style="width: <?= 70 / count($perfis) ?>%;">
                                        <?= htmlspecialchars($perfil['nome']) ?>
                                        <br>
                                        <small class="text-muted">(<?= htmlspecialchars($perfil['descricao']) ?>)</small>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($paginas as $pagina): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi <?= $pagina['icone'] ?? 'bi-file-earmark' ?> text-primary me-2"></i>
                                            <div>
                                                <strong><?= htmlspecialchars($pagina['nome']) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= htmlspecialchars($pagina['rota']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <?php foreach ($perfis as $perfil): ?>
                                        <td class="text-center">
                                            <?php
                                            $temPermissao = false;
                                            foreach ($permissoes as $permissao) {
                                                if ($permissao['pagina_id'] == $pagina['id'] && $permissao['perfil_id'] == $perfil['id']) {
                                                    $temPermissao = true;
                                                    break;
                                                }
                                            }
                                            ?>
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="permissao_<?= $pagina['id'] ?>_<?= $perfil['id'] ?>"
                                                       <?= $temPermissao ? 'checked' : '' ?>
                                                       data-pagina-id="<?= $pagina['id'] ?>"
                                                       data-perfil-id="<?= $perfil['id'] ?>">
                                            </div>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-shield-exclamation text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">Nenhuma página encontrada</h5>
                    <p class="text-muted">Clique em "Sincronizar Páginas" para registrar as páginas do sistema.</p>
                    <button type="button" class="btn btn-primary" onclick="sincronizarPaginas()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Sincronizar Páginas
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Informações -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6><i class="bi bi-info-circle me-2"></i>Como usar o Permissionamento</h6>
                <ul class="mb-0">
                    <li><strong>Admin:</strong> Acesso total ao sistema</li>
                    <li><strong>Instituição Admin:</strong> Gerencia sua instituição</li>
                    <li><strong>Nutricionista:</strong> Acesso aos seus pacientes</li>
                    <li><strong>Atendente:</strong> Cadastro e gestão de pacientes</li>
                    <li><strong>Paciente:</strong> Acesso apenas ao próprio perfil</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function sincronizarPaginas() {
    if (!confirm('Isso irá sincronizar todas as páginas do sistema. Continuar?')) {
        return;
    }
    
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-arrow-clockwise spin me-2"></i>Sincronizando...';
    btn.disabled = true;
    
    fetch('<?= BASE_URL ?>/permissionamento-paginas/sincronizar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erro ao sincronizar: ' + (data.message || 'Erro desconhecido'));
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao sincronizar páginas');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function salvarPermissoes() {
    const permissoes = [];
    const checkboxes = document.querySelectorAll('input[type="checkbox"][data-pagina-id]');
    
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            permissoes.push({
                pagina_id: checkbox.dataset.paginaId,
                perfil_id: checkbox.dataset.perfilId
            });
        }
    });
    
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-save spin me-2"></i>Salvando...';
    btn.disabled = true;
    
    fetch('<?= BASE_URL ?>/permissionamento-paginas/salvar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            permissoes: permissoes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Permissões salvas com sucesso!');
            location.reload();
        } else {
            alert('Erro ao salvar permissões: ' + (data.message || 'Erro desconhecido'));
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao salvar permissões');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// Adicionar classe de rotação para ícones
const style = document.createElement('style');
style.textContent = `
    .spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>