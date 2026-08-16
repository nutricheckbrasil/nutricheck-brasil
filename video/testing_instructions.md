# Instruções para Teste da Plataforma de Vídeo Interativo

## 1. Acesso à Plataforma

Para acessar a plataforma de demonstração, utilize o seguinte link:

**[https://8080-imdrtndjlp549no9v13tq-6501e467.manusvm.computer](https://8080-imdrtndjlp549no9v13tq-6501e467.manusvm.computer)**

## 2. Como Testar

Siga os passos abaixo para testar as funcionalidades da plataforma:

### 2.1. Iniciar o Vídeo

1.  Na página inicial, você verá uma lista de vídeos disponíveis. Clique no botão **"Assistir"** do "Vídeo de Demonstração - Matemática Básica".
2.  O player de vídeo será carregado. Clique no botão de **play** para iniciar a reprodução.

### 2.2. Responder às Perguntas Interativas

1.  **Pergunta 1 (Múltipla Escolha):** Aos 30 segundos, o vídeo pausará e uma pergunta de múltipla escolha será exibida. Selecione uma das opções e clique em **"Responder"**.
2.  **Pergunta 2 (Verdadeiro/Falso):** Aos 1 minuto e 30 segundos, uma pergunta de verdadeiro/falso aparecerá. Escolha uma das opções e responda.
3.  **Pergunta 3 (Texto Livre):** Aos 2 minutos e 30 segundos, uma pergunta de texto livre será apresentada. Digite sua resposta e clique em **"Responder"**.

### 2.3. Testar o Consentimento

1.  **Fim do Vídeo:** Ao final do vídeo (3 minutos), um modal de consentimento será exibido automaticamente.
2.  **Preencher Informações:** Você pode preencher seu nome e email (opcional).
3.  **Dar Consentimento:** Marque a caixa de seleção **"Eu concordo..."** e clique no botão **"Confirmar Consentimento"**.
4.  **Verificar Notificação:** Uma mensagem de sucesso será exibida, confirmando que o consentimento foi registrado. Como esta é uma demonstração, o email de notificação não é enviado de verdade, mas a simulação do envio é registrada nos logs do servidor.

## 3. Funcionalidades Adicionais

-   **Dashboard:** Explore a aba "Dashboard" para ver as estatísticas em tempo real (simuladas para esta demonstração).
-   **Editor de Perguntas:** A aba "Editor" permite visualizar como as perguntas são adicionadas e gerenciadas (funcionalidade completa na versão de produção).

## 4. Observações

-   Esta é uma versão de **demonstração** e utiliza um banco de dados SQLite em memória. Os dados não são persistidos entre as sessões.
-   O vídeo utilizado é uma simulação em HTML5 para agilizar o carregamento e o teste.

Se encontrar qualquer problema ou tiver alguma dúvida, por favor, me informe!

