<div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Detalhes do Anestesista</h2>
                <div>
                    <a href="<?= BASE_URL ?>/anestesistas/edit/<?= $anestesista['id'] ?>" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="<?= BASE_URL ?>/anestesistas" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
            
            <div class="row">
                <!-- Informações do Anestesista -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <?php if (!empty($anestesista['foto_path'])): ?>
                                <img src="<?= BASE_URL ?>/<?= $anestesista['foto_path'] ?>" alt="Foto" class="rounded-circle mb-3" style="width: 200px; height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 200px; height: 200px;">
                                    <i class="bi bi-person text-white" style="font-size: 4rem;"></i>
                                </div>
                            <?php endif; ?>
                            
                            <h4><?= htmlspecialchars($anestesista['nome']) ?></h4>
                            <p class="text-muted"><?= htmlspecialchars($anestesista['email']) ?></p>
                            
                            <div class="mb-3">
                                <span class="badge bg-<?= $anestesista['status'] === 'ativo' ? 'success' : 'secondary' ?> fs-6">
                                    <?= ucfirst($anestesista['status']) ?>
                                </span>
                            </div>
                            
                            <hr>
                            
                            <div class="text-start">
                                <p><strong>CRM:</strong> <?= htmlspecialchars($anestesista['crm']) ?></p>
                                <p><strong>Telefone:</strong> <?= htmlspecialchars($anestesista['telefone']) ?></p>
                                <p><strong>Instituição:</strong> <?= htmlspecialchars($anestesista['instituicao_nome'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- QR Code -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">
                                <i class="bi bi-qr-code"></i> QR Code
                            </h5>
                            
                            <?php if (!empty($anestesista['qr_code_path'])): ?>
                                <div class="mb-3">
                                    <img src="<?= BASE_URL ?>/<?= $anestesista['qr_code_path'] ?>" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted">Código: <?= htmlspecialchars($anestesista['qr_code']) ?></small>
                                </div>
                                
                                <div class="mb-3">
                                    <a href="<?= BASE_URL ?>/<?= $anestesista['qr_code_path'] ?>" download class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-download"></i> Baixar QR Code
                                    </a>
                                </div>
                                
                                <div class="alert alert-info">
                                    <small>
                                        <strong>URL de Cadastro:</strong><br>
                                        <code><?= BASE_URL ?>/pacientes/create?anestesista_id=<?= $anestesista['id'] ?></code>
                                    </small>
                                </div>
                                
                                <a href="<?= BASE_URL ?>/anestesistas/regenerate-qr/<?= $anestesista['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-arrow-clockwise"></i> Regenerar QR Code
                                </a>
                            <?php else: ?>
                                <div class="text-muted mb-3">
                                    <i class="bi bi-qr-code" style="font-size: 4rem;"></i>
                                    <p>QR Code não gerado</p>
                                </div>
                                
                                <a href="<?= BASE_URL ?>/anestesistas/regenerate-qr/<?= $anestesista['id'] ?>" class="btn btn-primary">
                                    <i class="bi bi-qr-code"></i> Gerar QR Code
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Estatísticas -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-graph-up"></i> Estatísticas
                            </h5>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <h3 class="text-primary"><?= count($pacientes) ?></h3>
                                    <small class="text-muted">Pacientes</small>
                                </div>
                                <div class="col-6">
                                    <h3 class="text-success"><?= count(array_filter($pacientes, function($p) { return $p['status'] === 'finalizado'; })) ?></h3>
                                    <small class="text-muted">Finalizados</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Lista de Pacientes -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-people"></i> Pacientes Associados
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($pacientes)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Procedimento</th>
                                                <th>Médico</th>
                                                <th>Data do Procedimento</th>
                                                <th>Status</th>
                                                <th>Classificação IA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pacientes as $paciente): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($paciente['nome'] . ' ' . $paciente['sobrenome']) ?></td>
                                                    <td><?= htmlspecialchars($paciente['procedimento_nome'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars($paciente['medico_nome'] ?? 'N/A') ?></td>
                                                    <td><?= $paciente['data_procedimento'] ? date('d/m/Y', strtotime($paciente['data_procedimento'])) : 'N/A' ?></td>
                                                    <td>
                                                        <span class="badge bg-<?= $paciente['status'] === 'finalizado' ? 'success' : 'warning' ?>">
                                                            <?= ucfirst($paciente['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($paciente['classificacao_ia']): ?>
                                                            <span class="badge bg-<?= $paciente['classificacao_ia'] === 'baixo_risco' ? 'success' : ($paciente['classificacao_ia'] === 'alto_risco' ? 'danger' : 'warning') ?>">
                                                                <?= ucfirst(str_replace('_', ' ', $paciente['classificacao_ia'])) ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted">Pendente</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted mt-3">Nenhum paciente associado</h5>
                                    <p class="text-muted">Este anestesista ainda não possui pacientes cadastrados.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
</div>
