<?php
/*
Configuración de Conexión usando variables de entorno.
*/

class MySqlConnection {

    private $databaseServer;
    private $databaseUserName;
    private $databasePassWord;
    private $databaseName;

    public function __construct() {
        $this->databaseServer   = getenv('DB_HOST') ?: 'localhost';
        $this->databaseUserName = getenv('DB_USER_MYSQL') ?: 'root';
        $this->databasePassWord = getenv('DB_PASSWORD_MYSQL') ?: '';
        $this->databaseName     = getenv('DB_NAME_MYSQL') ?: 'escore';
    }

    function get_databaseServer() {
        return $this->databaseServer;
    }

    function get_databaseUserName() {
        return $this->databaseUserName;
    }

    function get_databasePassWord() {
        return $this->databasePassWord;
    }

    function get_databaseName() {
        return $this->databaseName;
    }

}

class PgSqlConnection {

    private $databaseServer;
    private $databaseUserName;
    private $databasePassWord;
    private $databaseName;

    public function __construct() {
        $this->databaseServer   = getenv('DB_HOST') ?: 'localhost';
        $this->databaseUserName = getenv('DB_USER') ?: 'postgres';
        $this->databasePassWord = getenv('DB_PASSWORD') ?: '';
        $this->databaseName     = getenv('DB_NAME') ?: 'escore';
    }

    function get_databaseServer() {
        return $this->databaseServer;
    }

    function get_databaseUserName() {
        return $this->databaseUserName;
    }

    function get_databasePassWord() {
        return $this->databasePassWord;
    }

    function get_databaseName() {
        return $this->databaseName;
    }

}

?>
