<?php
/*
  Configuración de Conexión usando variables de entorno.
*/

class MySqlConnection {

    private $databaseServer;
    private $databaseUserName;
    private $databasePassWord;
    private $databaseName;
    private $databasePort;

    public function __construct() {
        $this->databaseServer   = getenv('DB_HOST') ?: 'localhost';
        $this->databaseUserName = getenv('DB_USER_MYSQL') ?: 'root';
        $this->databasePassWord = getenv('DB_PASSWORD_MYSQL') ?: '';
        $this->databaseName     = getenv('DB_NAME_MYSQL') ?: 'escore';
        $this->databasePort     = getenv('DB_PORT_MYSQL') ?: 3306;
    }

    public function getDatabaseServer() {
        return $this->databaseServer;
    }

    public function getDatabaseUserName() {
        return $this->databaseUserName;
    }

    public function getDatabasePassWord() {
        return $this->databasePassWord;
    }

    public function getDatabaseName() {
        return $this->databaseName;
    }

    public function getDatabasePort() {
        return $this->databasePort;
    }
}

class PgSqlConnection {

    private $databaseServer;
    private $databaseUserName;
    private $databasePassWord;
    private $databaseName;
    private $databasePort;

    public function __construct() {
        $this->databaseServer   = getenv('DB_HOST') ?: 'localhost';
        $this->databaseUserName = getenv('DB_USER') ?: 'postgres';
        $this->databasePassWord = getenv('DB_PASSWORD') ?: '';
        $this->databaseName     = getenv('DB_NAME') ?: 'escore';
        $this->databasePort     = getenv('DB_PORT') ?: 5432;
    }

    public function getDatabaseServer() {
        return $this->databaseServer;
    }

    public function getDatabaseUserName() {
        return $this->databaseUserName;
    }

    public function getDatabasePassWord() {
        return $this->databasePassWord;
    }

    public function getDatabaseName() {
        return $this->databaseName;
    }

    public function getDatabasePort() {
        return $this->databasePort;
    }

    /**
     * Crea y retorna una conexión pg_pconnect con los parámetros configurados.
     * Lanza una excepción si no se puede conectar.
     */
    public function connect() {
        $connString = sprintf(
            "host=%s port=%d dbname=%s user=%s password=%s",
            $this->getDatabaseServer(),
            $this->getDatabasePort(),
            $this->getDatabaseName(),
            $this->getDatabaseUserName(),
            $this->getDatabasePassWord()
        );

        $conn = @pg_pconnect($connString);
        if (!$conn) {
            throw new Exception("No se logró establecer la conexión a la base de datos PostgreSQL con: $connString");
        }
        return $conn;
    }
}
?>
