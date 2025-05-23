<?php
/*
Configuracion de Conexion.
*/

class MySqlConnection {

    private $databaseServer 	= "localhost";
    private $databaseUserName 	= "root";
    private $databasePassWord 	= "";
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

     private $databaseServer 	= "localhost";
    private $databaseUserName 	= "postgres";
    private $databasePassWord 	= "eLibro123";
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


?>