<?php
/**
 * Configuração do banco para Hostinger (produção).
 * Copie este arquivo para database_hostinger.php e preencha com os dados
 * do banco criado no painel (Bancos de Dados MySQL).
 * 
 * NÃO commite database_hostinger.php no Git (contém senha).
 */

// Dados do banco criado no painel Hostinger (ex.: u633289092_nutricheck)
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');        // Hostinger usa 3306 (pode omitir)
define('DB_NAME', 'u501175608_nutricheck');
define('DB_USER', 'u501175608_nutricheck');
define('DB_PASS', 'Texbr2007*/');  // A mesma senha definida ao criar o banco
