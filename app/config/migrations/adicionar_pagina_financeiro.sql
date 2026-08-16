-- Adicionar página Financeiro no sistema de permissões
INSERT INTO paginas_sistema (nome, rota, descricao, modulo, ativo, ordem) 
VALUES ('financeiro', '/financeiro', 'Módulo Financeiro - Assinaturas e Pagamentos', 'financeiro', TRUE, 10)
ON DUPLICATE KEY UPDATE 
    rota = '/financeiro',
    descricao = 'Módulo Financeiro - Assinaturas e Pagamentos',
    modulo = 'financeiro',
    ativo = TRUE,
    updated_at = CURRENT_TIMESTAMP;

-- Dar permissão para instituições (perfil_id = 1)
INSERT INTO permissoes_paginas (pagina_id, perfil_id, permitido)
SELECT ps.id, 1, 1
FROM paginas_sistema ps
WHERE ps.nome = 'financeiro'
ON DUPLICATE KEY UPDATE permitido = 1;

