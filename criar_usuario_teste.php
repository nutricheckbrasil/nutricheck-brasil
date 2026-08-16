<?php
/**
 * Cria um usuário de teste para o NutriCheck.
 * Execute uma vez: http://localhost/nutricheck/public/../criar_usuario_teste.php
 * ou: php criar_usuario_teste.php
 *
 * Após usar, você pode remover ou restringir o acesso a este arquivo.
 */

require_once __DIR__ . '/app/config/constants.php';
require_once __DIR__ . '/app/config/database.php';

$email_instituicao = 'admin@nutricheck.local';
$senha_comum = 'NutriCheck123';
$email_nutricionista = 'nutricionista@nutricheck.local';

header('Content-Type: text/html; charset=utf-8');
echo "<h1>NutriCheck – Criar usuário de teste</h1>\n";

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();

    // 1) Instituição
    $existe = $db->fetch("SELECT id FROM instituicoes WHERE email = ?", [$email_instituicao]);
    if ($existe) {
        $instituicao_id = (int) $existe['id'];
        echo "<p>✓ Instituição já existe (id = $instituicao_id).</p>\n";
    } else {
        $senha_hash = password_hash($senha_comum, PASSWORD_DEFAULT);
        $sql = "INSERT INTO instituicoes (nome, email, senha_hash, cnpj, endereco, telefone, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'ativo')";
        $db->query($sql, [
            'NutriCheck Demo',
            $email_instituicao,
            $senha_hash,
            '00000000000191',
            'Av. Exemplo, 1000',
            '(11) 99999-9999'
        ]);
        $instituicao_id = (int) $db->lastInsertId();
        echo "<p>✓ Instituição criada (id = $instituicao_id).</p>\n";
    }

    // 2) Perfil nutricionista (id 3 = mesmo que anestesista no banco)
    $perfil = $db->fetch("SELECT id FROM perfis WHERE id = 3 OR nome = 'anestesista' LIMIT 1");
    $perfil_id = $perfil ? (int) $perfil['id'] : 3;

    // 3) Usuário nutricionista
    $existe_user = $db->fetch("SELECT id FROM usuarios WHERE email = ?", [$email_nutricionista]);
    if ($existe_user) {
        echo "<p>✓ Nutricionista já existe.</p>\n";
    } else {
        $senha_hash = password_hash($senha_comum, PASSWORD_DEFAULT);
        $db->query(
            "INSERT INTO usuarios (instituicao_id, perfil_id, nome, email, senha_hash, crm, telefone, status) 
             VALUES (?, ?, ?, ?, ?, ?, ?, 'ativo')",
            [
                $instituicao_id,
                $perfil_id,
                'Nutricionista Teste',
                $email_nutricionista,
                $senha_hash,
                'CRN 12345',
                '(11) 98888-8888'
            ]
        );
        echo "<p>✓ Nutricionista criado.</p>\n";
    }

    echo "<hr><p><strong>Use um dos logins abaixo:</strong></p>\n";
    echo "<ul>\n";
    echo "<li><strong>Instituição:</strong> <code>$email_instituicao</code> / <code>$senha_comum</code></li>\n";
    echo "<li><strong>Nutricionista:</strong> <code>$email_nutricionista</code> / <code>$senha_comum</code></li>\n";
    echo "</ul>\n";
    echo "<p><a href='" . (defined('BASE_URL') ? BASE_URL : '/nutricheck/public') . "/auth/login'>Ir para o login</a></p>\n";

} catch (Exception $e) {
    echo "<p style='color:red'>Erro: " . htmlspecialchars($e->getMessage()) . "</p>\n";
    echo "<p>Verifique se o banco <code>nutricheck</code> existe e se as tabelas <code>instituicoes</code>, <code>usuarios</code> e <code>perfis</code> estão criadas (execute as migrations/estrutura do projeto).</p>\n";
}
