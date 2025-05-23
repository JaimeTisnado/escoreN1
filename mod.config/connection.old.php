<?php
/*
Configuracion de Conexion.
*/

class MySqlConnection {

    private $databaseServer 	= "localhost";
    private $databaseUserName 	= "root";
    private $databasePassWord 	= "jframos";
    private $databaseName 		= "escore";

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

     private $databaseServer 	= "1.pgsqlserver.com";
    private $databaseUserName 	= "eduprog_escore";
    private $databasePassWord 	= "escore";
    private $databaseName 	= "eduprog_escore";

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