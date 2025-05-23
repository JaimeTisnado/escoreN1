<?php

function fn_getIP() {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
    if (isset($_SERVER['HTTP_VIA'])) return $_SERVER['HTTP_VIA'];
    if (isset($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];
    return null;
}

function fn_ObjectToArray($mixed) {
    if (is_object($mixed)) $mixed = (array)$mixed;
    if (is_array($mixed)) {
        $new = array();
        foreach ($mixed as $key => $val) {
            $key = preg_replace("/^\0(.*)\0/", "", $key);
            $new[$key] = fn_ObjectToArray($val);
        }
    } else {
        $new = $mixed;
    }
    return $new;
}

function OpenConection() {
    global $_CurrentConexion;

    if (_baseDatos == 1) { // MySQL
        $_Conect = new MySqlConnection();
        $_CurrentConexion = mysqli_connect(
            $_Conect->get_databaseServer(),
            $_Conect->get_databaseUserName(),
            $_Conect->get_databasePassWord(),
            $_Conect->get_databaseName()
        );
        if (!$_CurrentConexion) {
            trigger_error(mysqli_connect_error(), E_USER_ERROR);
        }
    }

    if (_baseDatos == 2) { // pgSQL
        $_Conect = new PgSqlConnection();
        $_CurrentConexion = pg_connect(
            "host=" . $_Conect->get_databaseServer() .
            " dbname=" . $_Conect->get_databaseName() .
            " user=" . $_Conect->get_databaseUserName() .
            " password=" . $_Conect->get_databasePassWord()
        );
        if (!$_CurrentConexion) {
            trigger_error("No se logró establecer la conexión a la base de datos PostgreSQL", E_USER_ERROR);
        }
    }

    return $_CurrentConexion;
}

function fn_EjecutarQuery($iQuery) {
    if (_baseDatos == 1) {
        $conn = OpenConection();
        $sQL = mysqli_query($conn, $iQuery);
        if (!$sQL) trigger_error(mysqli_error($conn));
        return $sQL;
    }
    if (_baseDatos == 2) {
        $sQL = pg_query($iQuery);
        if (!$sQL) trigger_error(pg_last_error());
        return $sQL;
    }
}

function fn_ExtraerQuery($iResultado) {
    if (_baseDatos == 1) {
        return mysqli_fetch_array($iResultado, MYSQLI_ASSOC);
    }
    if (_baseDatos == 2) {
        return pg_fetch_array($iResultado, null, PGSQL_ASSOC);
    }
}

function fn_NumeroRegistros($iResultado) {
    if (_baseDatos == 1) return mysqli_num_rows($iResultado);
    if (_baseDatos == 2) return pg_num_rows($iResultado);
}

function closeDB($iConnection) {
    if (_baseDatos == 1) mysqli_close($iConnection);
    if (_baseDatos == 2) pg_close($iConnection);
}

function fn_getFechaDefault() {
    return date("Y-m-d");
}

function fn_getHoraDefault() {
    return date("H:i:s");
}

function fn_getFechaHoraDefault() {
    return date("Y-m-d H:i:s");
}

function fn_formatoString($pString, $pTamanio, $pFormato) {
    return str_pad($pString, $pTamanio, $pFormato, STR_PAD_LEFT);
}

function fn_msgAlert($pMensaje) {
    return "<script>alert('$pMensaje'); history.go(-1); </script>\n";
}

function fn_msgAlertCloseFancyBox($pMensaje) {
    return "<script>alert('$pMensaje'); parent.jQuery.fancybox.close();</script>\n";
}

function fn_CloseFancyBox($pMensaje) {
    return "<script>parent.jQuery.fancybox.close();</script>\n";
}

function fn_mostrarMsgErrorLogin($pMensaje) {
    echo "<h4>$pMensaje<p><a href='index.php'>Regresar</a></p></h4>";
}

function fn_getSizeArchivo($size) {
    $Acronimo = ['b', 'Kb', 'Mb', 'Gb', 'Tb'];
    if ($size < 1024) return $size . ' ' . $Acronimo[0];
    if ($size >= pow(1024, 4)) return round($size / pow(1024, 4), 2) . ' ' . $Acronimo[4];
    if ($size >= pow(1024, 3)) return round($size / pow(1024, 3), 2) . ' ' . $Acronimo[3];
    if ($size >= pow(1024, 2)) return round($size / pow(1024, 2), 2) . ' ' . $Acronimo[2];
    return round($size / 1024, 2) . ' ' . $Acronimo[1];
}

function fn_mostrarContentMsgError($pMensaje, $pHref, $pHrefTit) {
    echo "<table width='550' align='center'><tr><td class='ContentMsgError'><h4>$pMensaje<p><a href='$pHref'>$pHrefTit</a></p></h4></td></tr></table>";
}

function fn_mostrarContentMsgWarning($pMensaje, $pHref, $pHrefTit) {
    echo "<table width='550' align='center'><tr><td class='ContentMsgWarning'><h4>$pMensaje<p><a href='$pHref'>$pHrefTit</a></p></h4></td></tr></table>";
}

function fn_mostrarContentMsgInformation($pMensaje, $pHref, $pHrefTit) {
    echo "<table width='550' align='center'><tr><td class='ContentMsgInformation'><h4>$pMensaje<p><a href='$pHref'>$pHrefTit</a></p></h4></td></tr></table>";
}

function fn_obtenerBeginHTMLMail($pTitle) {
    return "<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<title>::. $pTitle .::</title>
<style>
.bodyMail { font-family: Arial; color: #666; background-color: #FFF; margin: 0 auto; }
.content { padding: 30px 20px; }
.content h2 { font-size: 25px; color: #F58634; border-bottom: 1px dotted #F58634; }
.tableConfReserva { font-size: 12px; background: #FFF; border: 1px solid #A4B4CA; }
.tableConfReserva .Titulos { font-weight: bold; font-size: 16px; padding: 5px 0 5px 10px; border-bottom: 1px solid #C1D1D1; }
.tableConfReserva img.Thumb { width: 160px; }
.letraNumerosRenta { color: #F58634; font-size: 14px; font-weight: bold; padding: 2px; }
ul.list_reserva_conf { font-size: 12px; margin: 2px; padding: 3px; }
ul.list_reserva_conf li { padding: 0 0 2px 8px; margin-bottom: 5px; }
</style>
</head>
<body class='bodyMail'>
<div class='content'>";
}
