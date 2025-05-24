<?php
/*
Configuración de Conexión.
*/

class MySqlConnection {

    private $databaseServer   = "localhost";
    private $databaseUserName = "root";
    private $databasePassWord = "";
    private $databaseName     = "escore";

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

    private $databaseServer   = "switchback.proxy.rlwy.net";
    private $databaseUserName = "postgres";
    private $databasePassWord = "VVFSjknzEbQwXjeSxjpafCliGeTAIvgA";
    private $databaseName     = "railway";
    private $databasePort     = 30265;

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

    function get_databasePort() {
        return $this->databasePort;
    }
}
?>
