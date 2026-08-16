<?php

class Database {
    private static $instance = null;
    private $connection;

    /** Credenciais: uso local (XAMPP) ou carrega database_hostinger.php na Hostinger */
    private function getCredentials() {
        $local = [
            'host' => 'localhost',
            'port' => '3307',
            'dbname' => 'nutricheck',
            'username' => 'nutricheck',
            'password' => '',
        ];
        $configFile = __DIR__ . '/database_hostinger.php';
        if (file_exists($configFile)) {
            require_once $configFile;
            return [
                'host' => defined('DB_HOST') ? DB_HOST : $local['host'],
                'port' => defined('DB_PORT') ? DB_PORT : '3306',
                'dbname' => defined('DB_NAME') ? DB_NAME : $local['dbname'],
                'username' => defined('DB_USER') ? DB_USER : $local['username'],
                'password' => defined('DB_PASS') ? DB_PASS : $local['password'],
            ];
        }
        return $local;
    }

    private $charset = 'utf8mb4';

    private function __construct() {
        $c = $this->getCredentials();
        $host = $c['host'];
        $port = $c['port'];
        $dbname = $c['dbname'];
        $username = $c['username'];
        $password = $c['password'];

        try {
            $dsn_test = "mysql:host={$host};port={$port};charset={$this->charset}";
            $test_connection = new PDO($dsn_test, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];
            
            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            if ($e->getCode() == 1049) {
                throw new Exception("Banco de dados '{$dbname}' não existe. Crie o banco no painel ou importe o dump.");
            }
            if ($e->getCode() == 2002 || $e->getCode() == 2003) {
                throw new Exception("Não foi possível conectar ao MySQL (host: {$host}, porta: {$port}). Verifique as credenciais em app/config/database_hostinger.php.");
            }
            throw new Exception("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
}