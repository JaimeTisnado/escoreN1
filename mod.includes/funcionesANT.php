<?php
//class Funciones {
	
function fn_getIP(){
    if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] )) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if( isset( $_SERVER ['HTTP_VIA'] ))  $ip = $_SERVER['HTTP_VIA'];
    else if( isset( $_SERVER ['REMOTE_ADDR'] ))  $ip = $_SERVER['REMOTE_ADDR'];
    else $ip = null ;
    
	return $ip;
}

// Obtiene un array tipo objeto (clase) y lo convierte a Array.
function fn_ObjectToArray($mixed) {
    if(is_object($mixed)) {
		$mixed = (array) $mixed;
	}
	
    if(is_array($mixed)) {
		$new = array();
        foreach($mixed as $key => $val) {
            $key = preg_replace("/^\\0(.*)\\0/","",$key);
            $new[$key] = fn_ObjectToArray($val);
			
        }
    } 
    else {
		$new = $mixed;
	}
    return $new;        
}

//==== Abrir Conexion a BD ===
function OpenConection(){
global $_CurrentConexion;

if (_baseDatos == 1) { #MySQL
$_Conect = new MySqlConnection();
$_CurrentConexion = mysql_pconnect($_Conect->get_databaseServer(), $_Conect->get_databaseUserName(), $_Conect->get_databasePassWord()) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($_Conect->get_databaseName(), $_CurrentConexion) or trigger_error(mysql_error(),E_USER_ERROR);
}

if (_baseDatos == 2) { #pgSQL
$_Conect = new PgSqlConnection();
$_CurrentConexion = pg_pconnect("host=".$_Conect->get_databaseServer()." dbname=".$_Conect->get_databaseName()." user=".$_Conect->get_databaseUserName()." password=".$_Conect->get_databasePassWord()) or trigger_error("No se logro establecer la conexion a la base de datos postgre",E_USER_ERROR);
}

if ($_CurrentConexion) {
return $_CurrentConexion;
}


}
//==== Abrir Conexion a BD ===


//==== Ejecutar Query ===
function fn_EjecutarQuery($iQuery){
	#$iConnection = OpenConection();
	$sQL = NULL;
	if (_baseDatos == 1) { #MySQL
		$sQL = mysql_query($iQuery) or trigger_error(mysql_error());
		return $sQL; #print($sQL);
	}
    if (_baseDatos == 2) { #pgSQL
		$sQL = pg_query($iQuery) or trigger_error(pg_last_error());
		return $sQL;
	}
}
//==== Ejecutar Query ===


//==== Extraer Query ===
function fn_ExtraerQuery($iResultado){
	#$iConnection = OpenConection();
	$sQL = NULL;
	if (_baseDatos == 1) { #MySQL
		$sQL = mysql_fetch_array($iResultado,MYSQL_ASSOC);
		return $sQL;
	}
    if (_baseDatos == 2) { #pgSQL
		$sQL = pg_fetch_array($iResultado,NULL,PGSQL_ASSOC);
		return $sQL;
	}
}
//==== Extraer Query ===


//==== Obtener Numero Registros Query ===
function fn_NumeroRegistros($iResultado){
	#$iConnection = OpenConection();
	$sQL = NULL;
	if (_baseDatos == 1) { #MySQL
		$sQL = mysql_num_rows($iResultado);
		return $sQL;
	}
    if (_baseDatos == 2) { #pgSQL
		$sQL = pg_num_rows($iResultado);
		return $sQL;
	}
}
//==== Obtener Numero Registros Query ===

// cierra la base de datos
function closeDB($iConnection){
	if (_baseDatos == 1) { #MySQL
		mysql_close($iConnection);
	}
    if (_baseDatos == 2) { #pgSQL
		pg_close($iConnection);
	}
}

// Obtener fecha formato Año-Mes-Dia
function fn_getFechaDefault(){

$fecha = date("Y-m-d");
return $fecha;

}

// Obtener hora formato hora:minuto:segundo
function fn_getHoraDefault(){

$hora = date("H:i:s");
return $hora;

}

// Obtener hora formato Año-Mes-Dia hora:minuto:segundo
function fn_getFechaHoraDefault(){

$fechaHora = date("Y-m-d H:i:s");
return $fechaHora;

}

function fn_formatoString ($pString, $pTamanio, $pFormato){

$formato = str_pad($pString, $pTamanio, $pFormato, STR_PAD_LEFT);

return $formato;
	
}


// mensaje de alerta javascript
function fn_msgAlert($pMensaje){
return "<script>alert('$pMensaje'); history.go(-1); </script>\n";	
}

// mensaje de alerta javascript
function fn_msgAlertCloseFancyBox($pMensaje){
return "<script>alert('$pMensaje'); parent.jQuery.fancybox.close();  </script>\n";	
/* window.opener.location.reload(); */
}

// cerar ventana fancybox 
function fn_CloseFancyBox($pMensaje){
return "<script> parent.jQuery.fancybox.close(); </script>\n";	
/* window.opener.location.reload(); */
}

// mostrar mensaje de advertencia en Login
function fn_mostrarMsgErrorLogin($pMensaje){
echo "<h4>".$pMensaje."<p><a href='index.php'>Regresar</a></p></h4>"; 
}

function fn_getSizeArchivo ($size) {

$Acronimo = array('b','Kb','Mb','Gb','Tb'); 

if ($size < 1024) { 
        return $size . ' ' . $Acronimo[0]; 
} else { 
	
	if ($size >= pow(1024, 4)) { 
	return round($size/pow(1024, 4), 2) . ' ' . $Acronimo[4]; 
	
	} elseif ($size >= pow(1024, 3)) { 
	return round($size/pow(1024, 3), 2) . ' ' . $Acronimo[3]; 
	
	} elseif ($size >= pow(1024, 2)) { 
	return round($size/pow(1024, 2), 2) . ' ' . $Acronimo[2]; 
	
	} else { 
	return round($size/1024, 2) . ' ' . $Acronimo[1]; 
	} 
	
} // fin if
	
} // fin funcion tamaño archivo

function fn_mostrarContentMsgError($pMensaje, $pHref, $pHrefTit){
	
echo '<table width="550" border="0" align="center" cellpadding="0" cellspacing="0"><tr>';
echo "<td class='ContentMsgError'><h4>".$pMensaje."<p><a href='".$pHref."'>".$pHrefTit."</a></p></h4></td>"; 
echo '</tr></table>';

}

function fn_mostrarContentMsgWarning($pMensaje, $pHref, $pHrefTit){
	
echo '<table width="550" border="0" align="center" cellpadding="0" cellspacing="0"><tr>';
echo "<td class='ContentMsgWarning'><h4>".$pMensaje."<p><a href='".$pHref."'>".$pHrefTit."</a></p></h4></td>"; 
echo '</tr></table>';

}

function fn_mostrarContentMsgInformation($pMensaje, $pHref, $pHrefTit){
	
echo '<table width="550" border="0" align="center" cellpadding="0" cellspacing="0"><tr>';
echo "<td class='ContentMsgInformation'><h4>".$pMensaje."<p><a href='".$pHref."'>".$pHrefTit."</a></p></h4></td>"; 
echo '</tr></table>';

}

function fn_obtenerBeginHTMLMail($pTitle) {

$HTML = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>::. '.$pTitle.' .::</title>
<link rel="shortcut icon" href="'.JPATH_BASE_WEB.'/imagenes/favicon.ico" type="image/x-icon" />
<style>
ol,ul{
	list-style:none
}
.bodyMail
{
    font-family:Arial, Helvetica, sans-serif;
	color: #666;
    width:100%;
    background-color:#FFF; /* FFF DDDDDD*/
    margin: 0 auto;
    margin-top:0px;
	background:none;
}
.content{
	border-top:0;
	min-height:320px;
	clear:both;
	padding:30px 20px;
}
.content img{
	background-color:#EFEFEF;
	border:#D6D6D6 solid 1px;
	padding: 5px;
	margin-right: 5px;
}
.content h2{
	font-size:25px;	
	font-weight:bold;
	color:#F58634;
	border-bottom:1px dotted #F58634;	
	padding:10px;
	margin:5px 0px 20px 0px;
	line-height:20px;
	text-align:left;
}
table {
    margin: 0 auto;
	font-size: 12px;
}
/* --- tableConfReserva --- */
.tableConfReserva
{
    font-size: 12px;
    background: #FFF;/*#E9F8FE;*/
    border-collapse: separate;
	border:1px solid #A4B4CA;/*#BAEAFC;*/
	/*padding:8px;*/
	vertical-align:top;
}
.tableConfReserva td
{
	
}
.tableConfReserva .Titulos{
	font-weight: bold;
	font-size:16px;
	padding: 5px 0px 5px 10px;
	border-bottom: solid 1px #C1D1D1;
}
.tableConfReserva img.Thumb {
	width:160px;
	border:0;
	background-color:transparent;	
}
/* --- tableConfReserva --- */
td.Negrita {
    font-weight:bold;
}
.letraNumerosRenta{
	color:#F58634;
	font-size:14px;
    font-weight:bold;	
	padding:2px;
}
/*---- list_reserva_conf ----*/
ul.list_reserva_conf
{
    font-size: 12px;
    padding-top: 5px;
    margin:2px;
    padding:3px;
}
ul.list_reserva_conf li
{
    padding:0px 0px 2px 8px;
	margin-bottom:5px;
}
ul.list_reserva_conf li.noneBullet 
{
	background-image: none;
}
ul.list_reserva_conf li.Negrita
{
	font-weight:bold;
}
ul.list_reserva_conf img {
	border:0;
}
ul.list_reserva_conf p{
	font-size:11px;
	font-weight:normal;
	text-align:left;
	margin:0px;
	margin-top:1px;
	line-height:15px;
	padding-left:5px;
	text-decoration:none;
}
/*---- list_reserva_conf ----*/
ul.list_detalle_reserva{
	font-size: 12px;
    padding-top: 5px;
    margin:2px;
    padding:3px;
}
ul.list_detalle_reserva li{
  	padding:0px 0px 0px 18px;
}
</style>
</head>
<body class="bodyMail">
';	
/*
'.file_get_contents(JPATH_BASE.'/css/xplore.v3/master.css').'
'.file_get_contents(JPATH_BASE.'/css/xplore.v3/style.css').'
'.file_get_contents(JPATH_BASE.'/css/xplore.v3/letras.css').'
'.file_get_contents(JPATH_BASE.'/css/xplore.v3/links.css').'

<link href="'.JPATH_BASE_WEB.'/css/xplore.v3/master.css" rel="stylesheet" type="text/css" />
<link href="'.JPATH_BASE_WEB.'/css/xplore.v3/style.css" rel="stylesheet" type="text/css" />
<link href="'.JPATH_BASE_WEB.'/css/xplore.v3/letras.css" rel="stylesheet" type="text/css" />
<link href="'.JPATH_BASE_WEB.'/css/xplore.v3/links.css" rel="stylesheet" type="text/css" />
*/
return $HTML;
}


function fn_obtenerEndHTML() {
	
$HTML = '
</body>
</html>
';	

return $HTML;
}

function fn_mostrarPaginaHTMLReserva($IDRenta) {

$pHtml .= fn_obtenerBeginHTMLMail('Impresion Reserva');
$pHtml .= fndb_getReservaHTML($IDRenta);
$pHtml .= fn_obtenerEndHTML();

return $pHtml;

}


function fn_mostrarBeginHTMLContacto() {

return '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>::. Formulario de Contacto .::</title>
<style>
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #030303;
	margin: 10px;
}
table{
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
h3{
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
}
</style>

</head>

<body class="body">
';	

}

function fn_mostrarEndHTMLContacto() {
	
return '
</body>
</html>
';	

}

function fn_mostrarContentContacto($pMensaje){
	
return '
<table width="600" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td>'.$pMensaje.'</td> 
	</tr>
</table>';

}

function fn_mostrarPaginaHTMLContacto($pMensaje) {
	
$pHtml .= fn_mostrarBeginHTMLContacto();
$pHtml .= fn_mostrarContentContacto($pMensaje);
$pHtml .= fn_mostrarEndHTMLContacto();


return $pHtml;

}


// revisa si expiro la Session_expire
function fn_checkSessionExpire($time){ 

	if($time > $_SESSION['Session_expire'])  {	
		return true;
	}	
	else {
		return false;
	}

}


function fn_mostrarPaginacion($nomPagina, $numPags, $pag, $numRegistros)
{
	$maximoPags = 10;
	$Espacio	= "&nbsp";
	
	if ($numPags > 0){
	echo "<p class='info_pages'>página  <b> $pag  </b> de <b> $numPags</b>&nbsp;&nbsp; | $numRegistros registros </p>";
	}
	
	echo '<div class="pages">';
	
	//Primera Pagina
	if ($numPags > 1){
		if ( $pag > 1 ) {
			#($pag > 1 && $pag < $numPags) && ($pag = $numPags)
			echo "<a href='$nomPagina&pag=1'>&laquo;</a>".$Espacio;
		}
	}
	
	//Pagina Anterior
	if ( $numPags > 1 && $pag > 1) { // si tiene mas de una pagina y la pagina no sea la primera
		$anterior = $pag - 1;
		echo "<a href='$nomPagina&pag=$anterior'>&lsaquo;</a>".$Espacio;	
	}
	
	$divPaginas = round(($numPags / $maximoPags));
	
	if ( $numPags < $maximoPags ){ #( $divPaginas < 1 )
		#Paginacion Numerada hasta $maximoPags paginas
		for ($i = 1; $i <= $numPags; $i++) {
			if ($pag == $i) {
				#echo "<a href='$nomPagina&pag=$i'>$i</a>".$Espacio;
				echo "<span class='current'>$i</span>".$Espacio;
			}
			else { 
				echo "<a href='$nomPagina&pag=$i'>$i</a>".$Espacio;	
			} // if
			
		} // for
		
	}
	else {
		
		if ( $pag > $maximoPags ){
		$numFor 	= floor(($pag / $maximoPags));
		$iniPag 	= ($maximoPags * $numFor); // +1 para multiplos del maximo de paginas
		$maxFor		= $iniPag + ($maximoPags - 1);
		$maxFor		= ( $maxFor > $numPags ? $numPags : $maxFor );
		#echo $numFor." - ";
		#echo $iniPag." - ";
		#echo $maxFor;
		}else{
		$iniPag 	= 1;
		$maxFor 	= $maximoPags;
		}
		
			
		for ($i = $iniPag; $i <= $maxFor; $i++) {
			if ($pag == $i) {
				#echo "<a href='$nomPagina&pag=$i'>$i</a>".$Espacio;
				echo "<span class='current'>$i</span>".$Espacio;
			}
			else { 
				echo "<a href='$nomPagina&pag=$i'>$i</a>".$Espacio;	
			} // if
			
		} // for
		
	} // fin if divPaginas
	
	// Pagina Siguiente
	if ( $numPags > 1 && $pag < $numPags) { // si tiene mas de una pagina y la pagina no se la ultima
		$siguiente = $pag + 1;
		echo "<a href='$nomPagina&pag=$siguiente'>&rsaquo;</a>".$Espacio;
	}
	
	//Ultima Pagina
	if ($pag >= 1 && $pag < $numPags) {
		echo "<a href='$nomPagina&pag=$numPags'>&raquo;</a>".$Espacio;
	}
		
	echo '</div>'; // pages

} // paginas 


function fn_mostrarPaginacionSEO($nomPagina, $numPags, $pag, $numRegistros)
{
	$maximoPags = 10;
	$Espacio	= "&nbsp;";
	
	if ($numPags > 0){
	echo "<p class='info_pages'>página  <b> $pag  </b> de <b> $numPags</b>&nbsp;&nbsp; | $numRegistros registros </p>";
	}
	
	echo '<div class="pages">';
	
	//Primera Pagina
	if ($numPags > 1){
		if ( $pag > 1 ) {
			#($pag > 1 && $pag < $numPags) && ($pag = $numPags)
			echo "<a href='$nomPagina/1' title='Primero'>&laquo;</a>".$Espacio;
		}
	}
	
	//Pagina Anterior
	if ( $numPags > 1 && $pag > 1) { // si tiene mas de una pagina y la pagina no sea la primera
		$anterior = $pag - 1;
		echo "<a href='$nomPagina/$anterior' title='Anterior'>&lsaquo;</a>".$Espacio;	
	}
	
	$divPaginas = round(($numPags / $maximoPags));
	
	if ( $numPags < $maximoPags ){ #( $divPaginas < 1 )
		#Paginacion Numerada hasta $maximoPags paginas
		for ($i = 1; $i <= $numPags; $i++) {
			if ($pag == $i) {
				#echo "<a href='$nomPagina&pag=$i'>$i</a>".$Espacio;
				echo "<span class='current'>$i</span>".$Espacio;
			}
			else { 
				echo "<a href='$nomPagina/$i'>$i</a>".$Espacio;	
			} // if
			
		} // for
		
	}
	else {
		
		if ( $pag > $maximoPags ){
		$numFor 	= floor(($pag / $maximoPags));
		$iniPag 	= ($maximoPags * $numFor); // +1 para multiplos del maximo de paginas
		$maxFor		= $iniPag + ($maximoPags - 1);
		$maxFor		= ( $maxFor > $numPags ? $numPags : $maxFor );
		#echo $numFor." - ";
		#echo $iniPag." - ";
		#echo $maxFor;
		}else{
		$iniPag 	= 1;
		$maxFor 	= $maximoPags;
		}
		
			
		for ($i = $iniPag; $i <= $maxFor; $i++) {
			if ($pag == $i) {
				#echo "<a href='$nomPagina&pag=$i'>$i</a>".$Espacio;
				echo "<span class='current'>$i</span>".$Espacio;
			}
			else { 
				echo "<a href='$nomPagina/$i'>$i</a>".$Espacio;	
			} // if
			
		} // for
		
	} // fin if divPaginas
	
	// Pagina Siguiente
	if ( $numPags > 1 && $pag < $numPags) { // si tiene mas de una pagina y la pagina no se la ultima
		$siguiente = $pag + 1;
		echo "<a href='$nomPagina/$siguiente' title='Siguiente'>&rsaquo;</a>".$Espacio;
	}
	
	//Ultima Pagina
	if ($pag >= 1 && $pag < $numPags) {
		echo "<a href='$nomPagina/$numPags' title='Último'>&raquo;</a>".$Espacio;
	}
		
	echo '</div>'; // pages

} // paginacionSEO


function fn_getFormatoHora24($Hora, $Minuto, $Tiempo) {

$cadenaHora = $Hora.':'.$Minuto.':00'.$Tiempo;


$cadenaFormateada = strtotime($cadenaHora);
$cadenaFormateada = date("H:i:s", $cadenaFormateada);

return $cadenaFormateada;
	
} // fin funcion fn_getFormatoHora24


function fn_getFormatoHora12Array($Hora) {

$cadenaHora = $Hora;


$phora 		= date("g",strtotime($cadenaHora));
$pminutos 	= date("i",strtotime($cadenaHora));
$ptiempo 	= date("a",strtotime($cadenaHora));

$cadenaFormateada = array('Horas' => $phora, 'Minutos' => $pminutos*1, 'Tiempo' => $ptiempo);


return $cadenaFormateada;
	
} // fin funcion fn_getFormatoHora12Array


function fn_getFormatoHora12($Hora) {

return date("h:i:s a",strtotime($Hora));

} // fin funcion fn_getFormatoHora12

function fn_getFormatoFechaUS($fecha){
list($dia, $mes, $anio) = explode("/", $fecha);
$date =  $anio.'-'.$mes.'-'.$dia; 
	if (checkdate($mes,$dia,$anio) == true) {
		return date("Y-m-d",strtotime($date));
	}else {
		return 0;	
	}
} // fin fconvertirFechaUS

function fn_getDiferenciaEntreFechas($fecha_principal, $fecha_secundaria, $obtener = 'DIAS', $redondear = true){
   $f0 = strtotime($fecha_principal);
   $f1 = strtotime($fecha_secundaria);
   if ($f0 < $f1) { $tmp = $f1; $f1 = $f0; $f0 = $tmp; }
   $resultado = ($f0 - $f1);
   switch ($obtener) {
       default: break;
       case "MINUTOS"   :   $resultado = $resultado / 60;   break;
       case "HORAS"     :   $resultado = $resultado / 60 / 60;   break;
       case "DIAS"      :   $resultado = $resultado / 60 / 60 / 24;   break;
       case "SEMANAS"   :   $resultado = $resultado / 60 / 60 / 24 / 7;   break;
   }
   if($redondear) $resultado = ceil($resultado); //antes round
   return $resultado;
}

function fn_getNombreMes($mes){
	
switch($mes){
case "01":
$nombre	= "Enero";
break;	

case "02":
$nombre	= "Febrero";
break;

case "03":
$nombre	= "Marzo";
break;

case "04":
$nombre	= "Abril";
break;

case "05":
$nombre	= "Mayo";
break;

case "06":
$nombre	= "Junio";
break;

case "07":
$nombre	= "Julio";
break;

case "08":
$nombre	= "Agosto";
break;

case "09":
$nombre	= "Septiembre";
break;

case "10":
$nombre	= "Octubre";
break;

case "11":
$nombre	= "Noviembre";
break;

case "12":
$nombre	= "Diciembre";
break;
	
}

return $nombre;
}


function fn_getNombreSemana($dia){
	
switch($dia){
case 1:
$nombre	= "Lunes";
break;	

case 2:
$nombre	= "Martes";
break;

case 3:
$nombre	= "Miércoles";
break;

case 4:
$nombre	= "Jueves";
break;

case 5:
$nombre	= "Viernes";
break;

case 6:
$nombre	= "Sábado";
break;

case 7:
$nombre	= "Domingo";
break;
}

return $nombre;
}

function fn_getStringInicioFin($string){

$mes 	= substr($string, 0,2);
$anio 	= substr($string, 2,6);	

if ($anio == '9999'){
return "A la fecha";
} else{
return fn_getNombreMes($mes)." - ".$anio;
}


}


function fn_getStringFin($string){

if ($string == '9999'){
return "A la fecha";
} else{
return $string;
}


}


function fn_getEdad($fechaNac){

list($anio,$mes,$dia) = explode("-",$fechaNac);

$anio_dif 	= date("Y") - $anio;
$mes_dif 	= date("m") - $mes;
$dia_dif 	= date("d") - $dia;

if ($dia_dif < 0 || $mes_dif < 0) {
$anio_dif--;
}

return $anio_dif;

}
//} // end Class


function fn_getStringFechaLarga($fecha){

list($anio,$mes,$dia) = explode("-",$fecha);

$string = $dia.' '.fn_getNombreMes($mes).', '.$anio;


return $string;

}



function fn_mostrarStringGenero($genero){

switch($genero){

case 1:
$string = "Masculino";
break;	

case 0:
$string = "Femenino";
break;	

default:
$string = "Indistinto";
break;

}

return $string;

} // fin funcion fn_mostrarStringGenero


function fn_replaceCaracteres($texto){

$caracteres	= array("'");
$textoReemp	= str_replace($caracteres, "", $texto);

return $textoReemp;	

}

function fn_getTiempoxSegundos($segundos){
$minutos	= $segundos/60;
$horas		= floor($minutos/60);
$minutos2	= $minutos%60;
$segundos_2	= $segundos%60%60%60;

if ($minutos2 < 10)$minutos2		='0'.$minutos2;
if ($segundos_2 < 10)$segundos_2	='0'.$segundos_2;

if ($segundos < 60){ /* segundos */
$resultado= round($segundos).' Segundos';
}elseif ($segundos > 60 && $segundos < 3600){/* minutos */
$resultado= $minutos2.':'.$segundos_2.' Minutos';
}else {/* horas */
$resultado= $horas.':'.$minutos2.':'.$segundos_2.' Horas';
}

return $resultado;

}

function fn_getCssMensaje($tipo){
	
	switch($tipo){
	case 1: 
	return "ContentMsgSuccessful";
	break;
	
	case 2:
	return "ContentMsgWarning";
	break;
	
	case 3:
	return "ContentMsgError";
	break;
	
	}
	
}

function fn_getRandomString($length=15,$uc=TRUE,$n=TRUE,$sc=FALSE)
{
	$source = 'abcdefghijklmnopqrstuvwxyz';
	if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	if($n==1) $source .= '1234567890';
	if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
	if($length>0){
		$rstr = "";
		$source = str_split($source,1);
		for($i=1; $i<=$length; $i++){
			mt_srand((double)microtime() * 1000000);
			$num = mt_rand(1,count($source));
			$rstr .= $source[$num-1];
		}

	}
	return $rstr;
}

function fn_doLog($text)
{
	$filename = "log.txt";
	$fh = fopen($filename, "a");
	fwrite($fh, fn_getFechaHoraDefault()." - $text\r\n");
	fclose($fh);
}

?>