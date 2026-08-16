<style>
@media (max-width: 991.98px) {
  .sidebar-link-lg, .sidebar-text-lg {
    font-size: 1rem;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
  }
  .flex-grow-1.p-4 {
    padding: 1rem !important;
  }
  .btn.btn-lg {
    width: 100%;
    margin-top: 1rem;
  }
}
@media (max-width: 767.98px) {
  .d-flex > .flex-shrink-0 {
    display: none !important;
  }
  .d-flex > .flex-grow-1 {
    width: 100% !important;
    padding: 0.5rem !important;
  }
}
</style>

<div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <h4 class="mb-0">
                    <i class="bi bi-question-circle text-primary me-2"></i>Ajuda e Suporte
                </h4>
                <p class="text-muted mb-0">Como podemos ajudá-lo hoje?</p>
            </div>
        </div>

        <!-- Mensagem de boas-vindas -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Bem-vindo ao Suporte!</strong> Nossa equipe está pronta para ajudá-lo. Tempo médio de resposta: <?= getTempoResposta(URGENCIA_NORMAL) ?> horas.
                </div>
            </div>
        </div>

        <!-- Botão principal -->
        <div class="row mb-3">
            <div class="col-12 text-center">
                <a href="<?= BASE_URL ?>/ajuda/abrir-chamado" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Preciso de Ajuda
                </a>
                <p class="text-muted mt-2">Clique aqui para abrir um novo chamado de suporte</p>
            </div>
        </div>

        <!-- Linha separadora -->
        <hr class="my-4">

        <!-- Ações Rápidas -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="mb-3">
                    <i class="bi bi-lightning text-warning me-2"></i>Ações Rápidas
                </h4>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card bg-light h-100">
                    <div class="card-body text-center d-flex flex-column">
                        <i class="bi bi-list-ul text-primary mb-2" style="font-size: 2rem;"></i>
                        <h5>Meus Chamados</h5>
                        <p class="text-muted flex-grow-1">Acompanhe o status dos seus chamados abertos</p>
                        <a href="<?= BASE_URL ?>/ajuda/meus-chamados" class="btn btn-outline-primary">Ver Chamados</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card bg-light h-100">
                    <div class="card-body text-center d-flex flex-column">
                        <i class="bi bi-question-circle text-info mb-2" style="font-size: 2rem;"></i>
                        <h5>Perguntas Frequentes</h5>
                        <p class="text-muted flex-grow-1">Respostas rápidas para dúvidas comuns</p>
                        <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#faqModal">Ver FAQs</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card bg-light h-100">
                    <div class="card-body text-center d-flex flex-column">
                        <i class="bi bi-telephone text-success mb-2" style="font-size: 2rem;"></i>
                        <h5>Emergência Técnica</h5>
                        <p class="text-muted flex-grow-1">Para problemas que impedem o uso do sistema</p>
                        <a href="tel:+5511999999999" class="btn btn-outline-success">Ligar Agora</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQs -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-question-circle me-2"></i>Perguntas Frequentes</h5>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        Como abro um chamado de suporte?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Clique no botão "Preciso de Ajuda" no topo da página. Preencha o formulário com detalhes do seu problema e nossa equipe entrará em contato.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        Qual o tempo de resposta?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Depende da urgência: Urgente (<?= getTempoResposta(URGENCIA_URGENTE) ?>h), Alta (<?= getTempoResposta(URGENCIA_ALTA) ?>h), Normal (<?= getTempoResposta(URGENCIA_NORMAL) ?>h), Baixa (<?= getTempoResposta(URGENCIA_BAIXA) ?>h).
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        Posso acompanhar meus chamados?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Sim! Acesse "Meus Chamados" para ver o histórico e status de todos os seus chamados abertos.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                        E se for uma emergência técnica?
                                    </button>
                                </h2>
                                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Para problemas que impedem seu trabalho, ligue diretamente: (11) 99999-9999. Atendimento 24h para emergências.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                        Como escolher a categoria correta?
                                    </button>
                                </h2>
                                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <strong>Problema Técnico:</strong> Erros do sistema, bugs, problemas de acesso.<br>
                                        <strong>Dúvida Clínica:</strong> Questões sobre protocolos, procedimentos médicos.<br>
                                        <strong>Dúvida Sistema:</strong> Como usar funcionalidades, navegação.<br>
                                        <strong>Sugestão:</strong> Melhorias, novas funcionalidades.<br>
                                        <strong>Outros:</strong> Assuntos que não se encaixam nas categorias acima.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
 