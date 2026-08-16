# Guia de Teste - Módulo Financeiro

## 📋 Passo a Passo para Testar

### 1. Acessar o Sistema

A URL correta é:
```
http://localhost/anestesiocheck/public
```

**NOTA:** A URL está configurada como `/anestesiocheck/public` (sem hífen), não `/anestesia-check/public`

### 2. Fazer Login como Instituição

1. Acesse: `http://localhost/anestesiocheck/public/auth/login`
2. Use as credenciais de uma instituição cadastrada no sistema

**Se não tiver uma instituição cadastrada**, você pode criar uma pelo painel admin ou executar este SQL:

```sql
-- Criar uma instituição de teste
INSERT INTO instituicoes (nome, email, senha_hash, status) 
VALUES (
    'Instituição Teste', 
    'teste@instituicao.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- senha: "password"
    'ativo'
);
```

**Credenciais de teste:**
- Email: `teste@instituicao.com`
- Senha: `password`

### 3. Acessar o Módulo Financeiro

Após fazer login como instituição:

1. No menu lateral, clique em **"Financeiro"** (ícone de cartão de crédito)
2. Ou acesse diretamente: `http://localhost/anestesiocheck/public/financeiro`

### 4. O que Você Verá

#### Página Principal (`/financeiro`)
- **Card de Assinatura Atual** (se já tiver uma)
- **3 Planos Disponíveis:**
  - Básico: R$ 99,90/mês - 50 pacientes
  - Profissional: R$ 199,90/mês - 200 pacientes
  - Enterprise: R$ 399,90/mês - 1000 pacientes
- **Estatísticas:**
  - Total pago
  - Número de pagamentos
  - Pacientes usados
  - Pacientes grátis usados (0/10)
- **Histórico de Pagamentos** (se houver)

#### Se Não Tiver Assinatura
- Você verá um aviso sobre os **10 pacientes gratuitos**
- Poderá escolher um dos 3 planos

### 5. Testar Fluxo de Pagamento

1. Clique em **"Escolher Plano"** em qualquer um dos 3 planos
2. Você será redirecionado para a página de **Métodos de Pagamento**
3. Selecione **PIX** (único método disponível no momento)
4. Clique em **"Confirmar Pagamento"**
5. Um QR Code será gerado (simulado, pois o Mercado Pago não está configurado)

### 6. Verificar Funcionalidades

#### Ver Detalhes da Assinatura
- Clique em **"Ver Detalhes"** no card da assinatura
- Ou acesse: `http://localhost/anestesiocheck/public/financeiro/assinatura`

#### Ver Histórico de Pagamentos
- Clique em **"Ver Todos"** no card de histórico
- Ou acesse: `http://localhost/anestesiocheck/public/financeiro/historico`

### 7. Testar Limite de Pacientes

1. Vá para **Cadastro de Pacientes**
2. Tente cadastrar pacientes
3. Após 10 pacientes gratuitos, você verá um aviso:
   - "Limite de pacientes gratuitos atingido. Escolha um plano para continuar."
4. O sistema bloqueará novos cadastros até escolher um plano

## 🔧 Solução de Problemas

### Erro 404 - Not Found

**Problema:** A URL não está funcionando

**Soluções:**
1. Verifique se está usando: `localhost/anestesiocheck/public` (sem hífen)
2. Verifique se o Apache está rodando no XAMPP
3. Verifique se o arquivo `public/index.php` existe

### Menu Financeiro Não Aparece

**Problema:** O item "Financeiro" não aparece no menu

**Solução:**
1. Verifique se você está logado como **instituição** (não como usuário)
2. Execute novamente o script de migrations:
   ```bash
   C:\xampp\php\php.exe executar_migrations_financeiro.php
   ```

### Erro ao Acessar /financeiro

**Problema:** Erro ao acessar a rota

**Solução:**
1. Verifique se o `FinanceiroController.php` existe em `app/controllers/`
2. Verifique se a rota está mapeada em `public/index.php`
3. Verifique os logs de erro do PHP

## 📝 Checklist de Teste

- [ ] Consegui acessar `http://localhost/anestesiocheck/public`
- [ ] Consegui fazer login como instituição
- [ ] O menu "Financeiro" aparece no sidebar
- [ ] Consigo ver os 3 planos na página principal
- [ ] Consigo ver o aviso sobre 10 pacientes gratuitos
- [ ] Consigo clicar em "Escolher Plano"
- [ ] A página de métodos de pagamento carrega
- [ ] Consigo ver o histórico de pagamentos (mesmo vazio)
- [ ] Consigo cadastrar pacientes (até 10 gratuitos)
- [ ] O sistema bloqueia após 10 pacientes gratuitos

## 🎯 Próximos Passos

1. **Configurar Mercado Pago:**
   - Edite `app/config/mercado_pago_config.php`
   - Adicione suas credenciais do Mercado Pago
   - Configure o webhook

2. **Testar Pagamento Real:**
   - Após configurar o Mercado Pago
   - Teste com valores pequenos primeiro
   - Verifique se o webhook está funcionando

3. **Personalizar Planos:**
   - Edite os planos no banco de dados
   - Ajuste preços e limites conforme necessário

