<?php
/**
 * Configurações do Mercado Pago
 * 
 * Para usar em produção, defina estas variáveis no seu arquivo .env ou config.php
 */

// Access Token do Mercado Pago (obtenha em: https://www.mercadopago.com.br/developers/panel/credentials)
define('MP_ACCESS_TOKEN', getenv('MP_ACCESS_TOKEN') ?: 'SEU_ACCESS_TOKEN_AQUI');

// Public Key do Mercado Pago
define('MP_PUBLIC_KEY', getenv('MP_PUBLIC_KEY') ?: 'SEU_PUBLIC_KEY_AQUI');

// Ambiente (sandbox ou production)
define('MP_ENVIRONMENT', getenv('MP_ENVIRONMENT') ?: 'sandbox'); // 'sandbox' ou 'production'

// URLs de retorno
define('MP_SUCCESS_URL', BASE_URL . '/financeiro/pagamento-sucesso');
define('MP_FAILURE_URL', BASE_URL . '/financeiro/pagamento-falha');
define('MP_PENDING_URL', BASE_URL . '/financeiro/pagamento-pendente');

// Webhook URL
define('MP_WEBHOOK_URL', BASE_URL . '/financeiro/webhook-mercado-pago');

// Configurações de notificação
define('MP_NOTIFICATION_URL', getenv('MP_NOTIFICATION_URL') ?: MP_WEBHOOK_URL);

