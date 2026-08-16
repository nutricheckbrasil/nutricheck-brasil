<?php
$rotaAtual = $_SERVER['REQUEST_URI'] ?? '';
$baseUrl = defined('BASE_URL') ? BASE_URL : '/nutricheck/public';

// Incluir RBAC para verificar permissões
require_once __DIR__ . '/../../classes/RBAC.php';
$rbac = new RBAC();

// Obter páginas permitidas para o usuário atual
$allowedPages = $rbac->getSidebarPages();

// Mapear ícones para as páginas
$pageIcons = [
    'dashboard' => 'bi-speedometer2',
    'instituicoes' => 'bi-hospital',
    'permissionamento' => 'bi-shield-check',
    'equipe_anestesistas' => 'bi-people-fill',
    'pacientes' => 'bi-people',
    'gestao_pacientes' => 'bi-robot',
    'classificacao_ia' => 'bi-robot',
    'anestesistas' => 'bi-person-plus',
    'agendamentos' => 'bi-calendar-event',
    'ajuda' => 'bi-question-circle',
    'financeiro' => 'bi-credit-card'
];

// Mapear nomes amigáveis para as páginas
$pageNames = [
    'dashboard' => 'Dashboard',
    'instituicoes' => 'Instituições',
    'permissionamento' => 'Permissionamento',
    'equipe_anestesistas' => 'Equipe Nutricionistas',
    'pacientes' => 'Pacientes',
    'gestao_pacientes' => 'Gestão de Pacientes',
    'classificacao_ia' => 'Classificação IA',
    'anestesistas' => 'Nutricionistas',
    'agendamentos' => 'Agendamentos',
    'ajuda' => 'Ajuda e Suporte',
    'financeiro' => 'Financeiro'
];

if (!function_exists('sidebarActive')) {
    function sidebarActive($rota) {
        global $rotaAtual;
        // Garantir que $rotaAtual seja uma string para evitar avisos de deprecated no PHP 8.1+
        $rotaAtual = $rotaAtual ?? '';
        return strpos($rotaAtual, $rota) !== false ? 'active' : '';
    }
}

if (!function_exists('hasPermission')) {
    function hasPermission($pageName) {
        global $allowedPages;
        foreach ($allowedPages as $page) {
            if ($page['nome'] === $pageName) {
                return true;
            }
        }
        return false;
    }
}
?>
<!-- Offcanvas Sidebar para mobile -->
<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarMedicoOffcanvas" aria-labelledby="sidebarMedicoLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarMedicoLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-white border-end" style="width: 100%; min-height: 100vh;">
            <a href="<?= $baseUrl ?>/pacientes" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-primary text-decoration-none fs-4 fw-bold">
                <i class="bi bi-clipboard2-pulse me-2" style="font-size: 2.2rem;"></i> NutriCheck
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <?php foreach ($allowedPages as $page): ?>
                <?php if (($page['nome'] ?? '') === 'classificacao_ia') continue; ?>
                <li class="nav-item">
                    <a href="<?= $baseUrl ?><?= $page['rota'] ?>" class="nav-link <?= sidebarActive($page['rota']) ?> sidebar-link-lg">
                        <i class="bi <?= $pageIcons[$page['nome']] ?? 'bi-file-earmark' ?> me-2" style="font-size: 1.3rem;"></i> 
                        <span class="sidebar-text-lg"><?= $pageNames[$page['nome']] ?? ucfirst($page['nome']) ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
                <li>
                    <a href="#" class="nav-link text-danger sidebar-link-lg" data-bs-toggle="modal" data-bs-target="#modalConfirmarSair">
                        <i class="bi bi-box-arrow-right me-2" style="font-size: 1.3rem;"></i> <span class="sidebar-text-lg">Sair</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Sidebar fixo para desktop -->
<div class="d-none d-md-flex flex-column flex-shrink-0 p-2 bg-white border-end" style="width: 225px; min-height: 100vh; position: fixed; left: 0; top: 0; z-index: 100;">
    <a href="<?= $baseUrl ?>/pacientes" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-primary text-decoration-none fs-5 fw-bold">
        <i class="bi bi-clipboard2-pulse me-2" style="font-size: 1.8rem;"></i> NutriCheck
    </a>
    <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                <?php foreach ($allowedPages as $page): ?>
                <?php if (($page['nome'] ?? '') === 'classificacao_ia') continue; ?>
                <li class="nav-item">
                    <a href="<?= $baseUrl ?><?= $page['rota'] ?>" class="nav-link <?= sidebarActive($page['rota']) ?> sidebar-link-lg">
                        <i class="bi <?= $pageIcons[$page['nome']] ?? 'bi-file-earmark' ?> me-2" style="font-size: 1.3rem;"></i> 
                        <span class="sidebar-text-lg"><?= $pageNames[$page['nome']] ?? ucfirst($page['nome']) ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
                <?php 
                // Adicionar Financeiro manualmente para instituições
                if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'instituicao'): 
                    $financeiroExists = false;
                    foreach ($allowedPages as $page) {
                        if ($page['nome'] === 'financeiro') {
                            $financeiroExists = true;
                            break;
                        }
                    }
                    if (!$financeiroExists):
                ?>
                <li class="nav-item">
                    <a href="<?= $baseUrl ?>/financeiro" class="nav-link <?= sidebarActive('/financeiro') ?> sidebar-link-lg">
                        <i class="bi bi-credit-card me-2" style="font-size: 1.3rem;"></i> 
                        <span class="sidebar-text-lg">Financeiro</span>
                    </a>
                </li>
                <?php endif; endif; ?>
                <li>
                    <a href="#" class="nav-link text-danger sidebar-link-lg" data-bs-toggle="modal" data-bs-target="#modalConfirmarSair">
                        <i class="bi bi-box-arrow-right me-2" style="font-size: 1.3rem;"></i> <span class="sidebar-text-lg">Sair</span>
                    </a>
                </li>
    </ul>
</div>

<style>
.sidebar-link-lg {
    font-size: 1rem;
    padding-top: 0.6rem;
    padding-bottom: 0.6rem;
}
.sidebar-text-lg {
    font-size: 0.95rem;
    font-weight: 500;
}
/* Corrigir cor do texto dos links inativos */
.nav-link:not(.active) {
    color: #333 !important;
}
.nav-link:not(.active):hover {
    color: #000 !important;
}
@media (max-width: 767.98px) {
    .d-none.d-md-flex {
        position: relative !important;
        left: auto !important;
        top: auto !important;
    }
}
</style>