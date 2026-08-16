<?php
/**
 * Arquivo de Teste para Diagnóstico do Embed
 * Coloque este arquivo em: /video/test_embed.php
 * Acesse: https://seudominio.com/video/test_embed.php
 */

// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h1>Diagnóstico do Sistema de Vídeo</h1>";
echo "<hr>";

// 1. Testar PHP
echo "<h2>1. PHP</h2>";
echo "✓ PHP está funcionando!<br>";
echo "Versão do PHP: " . phpversion() . "<br>";
echo "<hr>";

// 2. Testar caminhos
echo "<h2>2. Caminhos</h2>";
$base_path = dirname(__DIR__);
$app_path = $base_path . '/app';

echo "Diretório atual: " . __DIR__ . "<br>";
echo "Base path: " . $base_path . "<br>";
echo "App path: " . $app_path . "<br>";
echo "<hr>";

// 3. Testar se arquivos existem
echo "<h2>3. Arquivos de Configuração</h2>";

$arquivos = [
    'constants.php' => $app_path . '/config/constants.php',
    'config.php' => $app_path . '/config/config.php',
    'database.php' => $app_path . '/config/database.php'
];

foreach ($arquivos as $nome => $caminho) {
    if (file_exists($caminho)) {
        echo "✓ $nome encontrado<br>";
    } else {
        echo "✗ $nome NÃO encontrado em: $caminho<br>";
    }
}
echo "<hr>";

// 4. Tentar carregar arquivos
echo "<h2>4. Carregar Arquivos</h2>";

try {
    if (file_exists($app_path . '/config/constants.php')) {
        require_once $app_path . '/config/constants.php';
        echo "✓ constants.php carregado<br>";
    }
    
    if (file_exists($app_path . '/config/config.php')) {
        require_once $app_path . '/config/config.php';
        echo "✓ config.php carregado<br>";
    }
    
    if (file_exists($app_path . '/config/database.php')) {
        require_once $app_path . '/config/database.php';
        echo "✓ database.php carregado<br>";
    }
} catch (Exception $e) {
    echo "✗ Erro ao carregar arquivos: " . $e->getMessage() . "<br>";
}
echo "<hr>";

// 5. Testar classe Database
echo "<h2>5. Classe Database</h2>";

if (class_exists('Database')) {
    echo "✓ Classe Database existe<br>";
    
    try {
        $db = Database::getInstance();
        echo "✓ Instância criada com sucesso<br>";
        
        // Testar conexão
        echo "<hr>";
        echo "<h2>6. Conexão com Banco de Dados</h2>";
        
        // Tentar query simples
        try {
            $result = $db->query("SELECT 1 as test");
            echo "✓ Conexão com banco OK<br>";
        } catch (Exception $e) {
            echo "✗ Erro na conexão: " . $e->getMessage() . "<br>";
        }
        
        // Verificar tabelas
        echo "<hr>";
        echo "<h2>7. Tabelas do Banco</h2>";
        
        $tabelas = ['videos_interativos', 'video_perguntas', 'video_sessoes', 'video_respostas', 'video_estatisticas'];
        
        foreach ($tabelas as $tabela) {
            try {
                $result = $db->query("SHOW TABLES LIKE '$tabela'");
                if (count($result) > 0) {
                    echo "✓ Tabela $tabela existe<br>";
                } else {
                    echo "✗ Tabela $tabela NÃO existe<br>";
                }
            } catch (Exception $e) {
                echo "✗ Erro ao verificar $tabela: " . $e->getMessage() . "<br>";
            }
        }
        
        // Buscar vídeo
        echo "<hr>";
        echo "<h2>8. Buscar Vídeo (ID=1)</h2>";
        
        try {
            $sql = "SELECT * FROM videos_interativos WHERE id = 1";
            $video = $db->fetch($sql, []);
            
            if ($video) {
                echo "✓ Vídeo encontrado!<br>";
                echo "Título: " . htmlspecialchars($video['titulo']) . "<br>";
                echo "URL: " . htmlspecialchars($video['url_video']) . "<br>";
                echo "Ativo: " . ($video['ativo'] ? 'Sim' : 'Não') . "<br>";
            } else {
                echo "✗ Vídeo com ID=1 não encontrado<br>";
                echo "Tentando buscar qualquer vídeo...<br>";
                
                $videos = $db->query("SELECT * FROM videos_interativos LIMIT 5");
                if (count($videos) > 0) {
                    echo "Vídeos encontrados:<br>";
                    foreach ($videos as $v) {
                        echo "- ID: " . $v['id'] . " - " . htmlspecialchars($v['titulo']) . "<br>";
                    }
                } else {
                    echo "✗ Nenhum vídeo cadastrado no banco<br>";
                }
            }
        } catch (Exception $e) {
            echo "✗ Erro ao buscar vídeo: " . $e->getMessage() . "<br>";
            
            // Tentar tabela antiga
            echo "Tentando tabela 'videos' (estrutura antiga)...<br>";
            try {
                $video = $db->fetch("SELECT * FROM videos WHERE id = 1");
                if ($video) {
                    echo "✓ Vídeo encontrado na tabela antiga!<br>";
                } else {
                    echo "✗ Vídeo não encontrado na tabela antiga<br>";
                }
            } catch (Exception $e2) {
                echo "✗ Tabela 'videos' também não existe: " . $e2->getMessage() . "<br>";
            }
        }
        
        // Buscar perguntas
        echo "<hr>";
        echo "<h2>9. Buscar Perguntas (vídeo ID=1)</h2>";
        
        try {
            $sql = "SELECT * FROM video_perguntas WHERE video_id = 1";
            $perguntas = $db->query($sql, []);
            
            if (count($perguntas) > 0) {
                echo "✓ " . count($perguntas) . " pergunta(s) encontrada(s)<br>";
                foreach ($perguntas as $p) {
                    echo "- " . htmlspecialchars($p['texto_pergunta']) . " (tempo: " . $p['tempo_exibicao'] . "s)<br>";
                }
            } else {
                echo "✗ Nenhuma pergunta cadastrada para este vídeo<br>";
            }
        } catch (Exception $e) {
            echo "✗ Erro ao buscar perguntas: " . $e->getMessage() . "<br>";
        }
        
    } catch (Exception $e) {
        echo "✗ Erro ao criar instância: " . $e->getMessage() . "<br>";
        echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
    }
    
} else {
    echo "✗ Classe Database NÃO existe<br>";
    echo "Isso significa que database.php não foi carregado corretamente<br>";
}

echo "<hr>";
echo "<h2>10. Resumo</h2>";
echo "<p>Se todos os testes acima passaram (✓), o sistema está OK.</p>";
echo "<p>Se algum teste falhou (✗), corrija o problema indicado.</p>";
echo "<hr>";
echo "<p><strong>Próximo passo:</strong> Se tudo estiver OK, acesse o embed.php diretamente:</p>";
echo "<p><a href='embed.php?video_id=1&paciente_id=59'>Testar embed.php</a></p>";
?>

