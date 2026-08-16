<?php require_once APP_PATH . '/views/layouts/main.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
        <h4 class="mb-0">
            <i class="bi bi-ticket-detailed text-primary me-2"></i>Chamado #<?= htmlspecialchars($chamado['numero_chamado'] ?? 'N/A') ?>
        </h4>
        <a href="<?= BASE_URL ?>/ajuda/meus-chamados" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Voltar
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0 py-2">
                        <i class="bi bi-info-circle me-2 fs-4"></i>Detalhes do Chamado
                    </h6>
                </div>
                <div class="card-body py-2">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Categoria:</label>
                            <p class="mb-0">
                                <span class="badge bg-secondary">
                                    <?= ucfirst(str_replace('_', ' ', $chamado['categoria'] ?? 'geral')) ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Urgência:</label>
                            <p class="mb-0">
                                <?php
                                $urgencia = $chamado['urgencia'] ?? 'normal';
                                $urgenciaClass = '';
                                $urgenciaText = '';
                                switch ($urgencia) {
                                    case 'urgente':
                                        $urgenciaClass = 'bg-danger';
                                        $urgenciaText = 'Urgente';
                                        break;
                                    case 'alta':
                                        $urgenciaClass = 'bg-warning';
                                        $urgenciaText = 'Alta';
                                        break;
                                    case 'normal':
                                        $urgenciaClass = 'bg-info';
                                        $urgenciaText = 'Normal';
                                        break;
                                    case 'baixa':
                                        $urgenciaClass = 'bg-success';
                                        $urgenciaText = 'Baixa';
                                        break;
                                }
                                ?>
                                <span class="badge <?= $urgenciaClass ?>">
                                    <?= $urgenciaText ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Assunto:</label>
                        <p class="mb-0"><?= htmlspecialchars($chamado['assunto'] ?? 'Sem assunto') ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição:</label>
                        <div class="bg-light p-3 rounded">
                            <?= nl2br(htmlspecialchars($chamado['descricao'] ?? 'Sem descrição')) ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Última Atualização:</label>
                        <p class="mb-0 text-muted">
                            <?= date('d/m/Y H:i', strtotime($chamado['updated_at'])) ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0 py-2">
                        <i class="bi bi-chat-dots me-2 fs-4"></i>Respostas
                    </h6>
                </div>
                <div class="card-body py-2">
                    <?php if (empty($respostas)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-chat-dots text-muted" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0 text-muted">Nenhuma resposta ainda. Nossa equipe entrará em contato em breve.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($respostas as $resposta): ?>
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">
                                            <?php if ($resposta['tipo'] === 'admin'): ?>
                                                <i class="bi bi-person-badge text-primary me-1"></i>Suporte
                                            <?php else: ?>
                                                <i class="bi bi-person text-success me-1"></i>Você
                                            <?php endif; ?>
                                        </h6>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($resposta['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="bg-light p-3 rounded">
                                    <?= nl2br(htmlspecialchars($resposta['mensagem'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0 py-2">
                        <i class="bi bi-reply me-2 fs-4"></i>Adicionar Resposta
                    </h6>
                </div>
                <div class="card-body py-2">
                    <form method="POST" action="<?= BASE_URL ?>/ajuda/responder-chamado">
                        <input type="hidden" name="chamado_id" value="<?= $chamado['id'] ?>">
                        <div class="mb-3">
                            <label for="mensagem" class="form-label">Sua Resposta:</label>
                            <textarea class="form-control" id="mensagem" name="mensagem" rows="4" 
                                      placeholder="Digite sua resposta ou informação adicional..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-send me-2"></i>Enviar Resposta
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0 py-2">
                        <i class="bi bi-info-circle me-2 fs-4"></i>Informações
                    </h6>
                </div>
                <div class="card-body py-2">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tempo de Resposta:</label>
                        <p class="mb-0">
                            <?php
                            $urgencia = $chamado['urgencia'] ?? 'normal';
                            switch($urgencia) {
                                case 'urgente':
                                    echo '<span class="text-danger">2 horas</span>';
                                    break;
                                case 'alta':
                                    echo '<span class="text-warning">4 horas</span>';
                                    break;
                                case 'normal':
                                    echo '<span class="text-info">24 horas</span>';
                                    break;
                                case 'baixa':
                                    echo '<span class="text-muted">48 horas</span>';
                                    break;
                                default:
                                    echo '<span class="text-info">24 horas</span>';
                                    break;
                            }
                            ?>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Emergência Técnica:</label>
                        <p class="mb-0">
                            <a href="tel:+5551981066986" class="btn btn-warning btn-sm w-100">
                                <i class="bi bi-telephone me-2"></i>(51) 98106-6986
                            </a>
                        </p>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-lightbulb me-2"></i>
                        <strong>Dica:</strong> Se precisar de informações urgentes, ligue diretamente para nossa equipe.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>