# Módulo Financeiro - AnestesiaCheck

## Instalação

### 1. Executar Migrations SQL

Execute os scripts SQL na seguinte ordem:

```bash
# 1. Criar tabelas do módulo financeiro
mysql -u root -p anestesiocheck < app/config/migrations/financeiro.sql

# 2. Adicionar página no sistema de permissões
mysql -u root -p anestesiocheck < app/config/migrations/adicionar_pagina_financeiro.sql
```

### 2. Configurar Mercado Pago

1. Acesse https://www.mercadopago.com.br/developers/panel/credentials
2. Obtenha seu Access Token e Public Key
3. Edite `app/config/mercado_pago_config.php` e configure:
   - `MP_ACCESS_TOKEN`: Seu Access Token
   - `MP_PUBLIC_KEY`: Sua Public Key
   - `MP_ENVIRONMENT`: 'sandbox' para testes ou 'production' para produção

### 3. Configurar Webhook do Mercado Pago

No painel do Mercado Pago, configure a URL do webhook:
```
https://seudominio.com/anestesiocheck/public/financeiro/webhook-mercado-pago
```

## Funcionalidades

### Para Instituições

1. **Período Gratuito**: 10 pacientes gratuitos ao criar a conta
2. **Planos Disponíveis**:
   - Básico: R$ 99,90/mês - 50 pacientes
   - Profissional: R$ 199,90/mês - 200 pacientes
   - Enterprise: R$ 399,90/mês - 1000 pacientes

3. **Métodos de Pagamento**:
   - PIX (implementado)
   - Cartão de Crédito (em breve)
   - Boleto (em breve)

4. **Funcionalidades**:
   - Visualizar assinatura atual
   - Renovar assinatura
   - Histórico de pagamentos
   - Gerenciar métodos de pagamento

### Controle de Limites

- O sistema verifica automaticamente o limite de pacientes ao cadastrar
- Se atingir 10 pacientes gratuitos, bloqueia novos cadastros até escolher um plano
- Se atingir o limite do plano, bloqueia novos cadastros até renovar ou fazer upgrade

## Estrutura de Arquivos

```
app/
├── config/
│   ├── migrations/
│   │   ├── financeiro.sql
│   │   └── adicionar_pagina_financeiro.sql
│   └── mercado_pago_config.php
├── controllers/
│   └── FinanceiroController.php
├── classes/
│   └── MercadoPagoHelper.php
└── views/
    └── financeiro/
        ├── index.php
        ├── assinatura.php
        ├── historico.php
        └── metodos-pagamento.php
```

## Rotas

- `/financeiro` - Página principal do módulo
- `/financeiro/assinatura` - Detalhes da assinatura
- `/financeiro/historico` - Histórico de pagamentos
- `/financeiro/metodos-pagamento?plano_id=X` - Escolher método de pagamento
- `/financeiro/processar-pagamento` - Processar pagamento (POST)
- `/financeiro/webhook-mercado-pago` - Webhook do Mercado Pago

## Banco de Dados

### Tabelas Criadas

1. **planos**: Planos disponíveis
2. **assinaturas**: Assinaturas das instituições
3. **pagamentos**: Histórico de pagamentos

### Campos Adicionados em `instituicoes`

- `pacientes_gratis_usados`: Contador de pacientes gratuitos usados
- `plano_atual_id`: ID do plano atual
- `assinatura_atual_id`: ID da assinatura atual

## Próximos Passos

1. Implementar integração completa com Mercado Pago
2. Adicionar suporte a cartão de crédito
3. Adicionar suporte a boleto
4. Criar relatórios financeiros
5. Adicionar notificações por email

