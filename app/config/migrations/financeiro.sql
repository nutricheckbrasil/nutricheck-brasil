-- ============================================
-- MÓDULO FINANCEIRO - TABELAS
-- ============================================

-- Tabela de Planos
CREATE TABLE IF NOT EXISTS planos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco_mensal DECIMAL(10, 2) NOT NULL,
    pacientes_incluidos INT NOT NULL DEFAULT 0,
    recursos TEXT, -- JSON com recursos do plano
    ativo BOOLEAN DEFAULT TRUE,
    ordem INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Assinaturas
CREATE TABLE IF NOT EXISTS assinaturas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    instituicao_id INT NOT NULL,
    plano_id INT NOT NULL,
    status ENUM('ativa', 'expirada', 'cancelada', 'suspensa') DEFAULT 'ativa',
    data_inicio DATE NOT NULL,
    data_expiracao DATE NOT NULL,
    pacientes_usados INT DEFAULT 0,
    pacientes_gratis_usados INT DEFAULT 0, -- Contador dos 10 pacientes free
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instituicao_id) REFERENCES instituicoes(id) ON DELETE CASCADE,
    FOREIGN KEY (plano_id) REFERENCES planos(id) ON DELETE RESTRICT,
    INDEX idx_instituicao (instituicao_id),
    INDEX idx_status (status),
    INDEX idx_expiracao (data_expiracao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Pagamentos
CREATE TABLE IF NOT EXISTS pagamentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    assinatura_id INT NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    metodo_pagamento ENUM('pix', 'cartao_credito', 'cartao_debito', 'boleto') DEFAULT 'pix',
    status ENUM('pendente', 'processando', 'aprovado', 'rejeitado', 'cancelado', 'reembolsado') DEFAULT 'pendente',
    mercado_pago_id VARCHAR(255) NULL, -- ID do pagamento no Mercado Pago
    mercado_pago_status VARCHAR(50) NULL, -- Status retornado pelo Mercado Pago
    data_pagamento DATETIME NULL,
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assinatura_id) REFERENCES assinaturas(id) ON DELETE CASCADE,
    INDEX idx_assinatura (assinatura_id),
    INDEX idx_status (status),
    INDEX idx_mercado_pago (mercado_pago_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Adicionar campos na tabela instituicoes (verificar se já existem antes)
ALTER TABLE instituicoes 
ADD COLUMN pacientes_gratis_usados INT DEFAULT 0,
ADD COLUMN plano_atual_id INT NULL,
ADD COLUMN assinatura_atual_id INT NULL;

-- Adicionar foreign keys (se não existirem)
-- Nota: MySQL não suporta IF NOT EXISTS em ALTER TABLE, então execute manualmente se necessário
-- ALTER TABLE instituicoes ADD FOREIGN KEY (plano_atual_id) REFERENCES planos(id) ON DELETE SET NULL;
-- ALTER TABLE instituicoes ADD FOREIGN KEY (assinatura_atual_id) REFERENCES assinaturas(id) ON DELETE SET NULL;

-- Inserir planos padrão
INSERT INTO planos (nome, descricao, preco_mensal, pacientes_incluidos, recursos, ordem) VALUES
('Básico', 'Plano ideal para começar', 99.90, 50, '["Pacientes ilimitados após os 10 free", "Suporte por email", "Relatórios básicos"]', 1),
('Profissional', 'Para instituições em crescimento', 199.90, 200, '["Pacientes ilimitados após os 10 free", "Suporte prioritário", "Relatórios avançados", "API de integração"]', 2),
('Enterprise', 'Solução completa para grandes instituições', 399.90, 1000, '["Pacientes ilimitados após os 10 free", "Suporte 24/7", "Relatórios personalizados", "API completa", "Treinamento dedicado"]', 3)
ON DUPLICATE KEY UPDATE nome=nome;

