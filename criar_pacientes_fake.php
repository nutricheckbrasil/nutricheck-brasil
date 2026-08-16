<?php
/**
 * Cria 20 pacientes fake para testes – NutriCheck.
 * Uso: http://localhost/nutricheck/criar_pacientes_fake.php ou php criar_pacientes_fake.php
 * Pacientes são criados para a instituição id = 19.
 */

require_once __DIR__ . '/app/config/constants.php';
require_once __DIR__ . '/app/config/database.php';

$instituicao_id = 19;

header('Content-Type: text/html; charset=utf-8');
echo "<h1>NutriCheck – Criar 20 pacientes fake</h1>\n";

$nomes = [
    ['Maria', 'Silva'], ['João', 'Santos'], ['Ana', 'Oliveira'], ['Pedro', 'Souza'],
    ['Carla', 'Lima'], ['Ricardo', 'Costa'], ['Fernanda', 'Alves'], ['Marcos', 'Pereira'],
    ['Juliana', 'Ribeiro'], ['Paulo', 'Martins'], ['Luciana', 'Carvalho'], ['Roberto', 'Rocha'],
    ['Patricia', 'Barbosa'], ['Daniel', 'Dias'], ['Camila', 'Nascimento'], ['Bruno', 'Araújo'],
    ['Amanda', 'Fernandes'], ['Lucas', 'Gomes'], ['Larissa', 'Mendes'], ['Thiago', 'Castro']
];

// CPF base para gerar válidos (apenas formato; dígitos verificadores aproximados)
$cpf_base = ['111.444.777', '222.555.888', '333.666.999', '123.456.789', '987.654.321'];
$sufixos = ['-35', '-04', '-60', '-12', '-88', '-01', '-55', '-22', '-77', '-09'];

$emails = [
    'maria.silva', 'joao.santos', 'ana.oliveira', 'pedro.souza', 'carla.lima',
    'ricardo.costa', 'fernanda.alves', 'marcos.pereira', 'juliana.ribeiro', 'paulo.martins',
    'luciana.carvalho', 'roberto.rocha', 'patricia.barbosa', 'daniel.dias', 'camila.nascimento',
    'bruno.araujo', 'amanda.fernandes', 'lucas.gomes', 'larissa.mendes', 'thiago.castro'
];

try {
    $db = Database::getInstance();

    // Nutricionista da instituição 19 (médico opcional – pode ser NULL)
    $medico = $db->fetch("SELECT id FROM usuarios WHERE instituicao_id = ? AND perfil_id = 2 AND status = 'ativo' LIMIT 1", [$instituicao_id]);
    $nutri = $db->fetch("SELECT id FROM usuarios WHERE instituicao_id = ? AND perfil_id = 3 AND status = 'ativo' LIMIT 1", [$instituicao_id]);
    $procedimento = $db->fetch("SELECT id FROM procedimentos WHERE 1=1 LIMIT 1");

    $medico_id = $medico ? (int) $medico['id'] : null;
    $nutricionista_id = $nutri ? (int) $nutri['id'] : null;
    $procedimento_id = $procedimento ? (int) $procedimento['id'] : null;

    if (!$procedimento_id) {
        echo "<p style='color:red'>Erro: Cadastre ao menos um procedimento antes.</p>\n";
        exit;
    }

    $hoje = new DateTime();
    $criados = 0;

    for ($i = 0; $i < 20; $i++) {
        $nome = $nomes[$i][0];
        $sobrenome = $nomes[$i][1];
        $cpf = $cpf_base[$i % count($cpf_base)] . $sufixos[$i % count($sufixos)];
        $idade_anos = 25 + ($i % 45);
        $nasc = (clone $hoje)->modify("-$idade_anos years");
        $data_nascimento = $nasc->format('Y-m-d');
        $sexo = ($i % 3 === 0) ? 'F' : 'M';
        $ddd = ['11', '21', '31', '19', '48'][$i % 5];
        $telefone = "($ddd) 9" . str_pad((string)(8000 + $i), 4, '0') . '-' . str_pad((string)(1000 + $i * 2), 4, '0');
        $email = $emails[$i] . '@email.com';

        $token = bin2hex(random_bytes(32));
        $link_acesso = '/paciente/acesso/' . $token;

        $data_procedimento = (clone $hoje)->modify('+' . (7 + $i) . ' days')->format('Y-m-d');

        $sql = "INSERT INTO pacientes (
            instituicao_id, medico_id, anestesista_id, nome, sobrenome, cpf, data_nascimento, sexo,
            telefone, email, procedimento_id, data_procedimento, necessita_orientacao_pre_anestesica,
            paciente_alto_risco, link_acesso, token_acesso
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0, ?, ?)";

        $db->query($sql, [
            $instituicao_id,
            $medico_id,
            $nutricionista_id,
            $nome,
            $sobrenome,
            $cpf,
            $data_nascimento,
            $sexo,
            $telefone,
            $email,
            $procedimento_id,
            $data_procedimento,
            $link_acesso,
            $token
        ]);
        $paciente_id = $db->lastInsertId();

        if ($nutricionista_id && $paciente_id) {
            $db->query(
                "INSERT INTO paciente_anestesistas (paciente_id, anestesista_id, data_atribuicao, status) VALUES (?, ?, NOW(), 'ativo')",
                [$paciente_id, $nutricionista_id]
            );
        }

        $criados++;
        echo "<p>✓ Paciente $criados: <strong>$nome $sobrenome</strong> – $idade_anos anos – $email</p>\n";
    }

    echo "<hr><p><strong>$criados pacientes criados</strong> para a instituição (id = $instituicao_id).</p>\n";
    echo "<p><a href='" . (defined('BASE_URL') ? BASE_URL : '/nutricheck/public') . "/pacientes'>Ir para Pacientes</a> | ";
    echo "<a href='" . (defined('BASE_URL') ? BASE_URL : '/nutricheck/public') . "/equipe-nutricionistas'>Equipe Nutricionistas</a></p>\n";

} catch (Exception $e) {
    echo "<p style='color:red'>Erro: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}
