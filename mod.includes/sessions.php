<?php
session_start();

$sSesion =	(isset($_SESSION[_NameSession_id]) ? 1 : 0);
if ( ($sSesion) != 0 ) #(session_is_registered(_NameSession_idUser) ) #_NameSession_id
{ 
	
	#Verificacion Disponibilidad del Sistema
	$dHoraActual 	= date("Y-m-d H:i:s");
	$dDiaActual	= date("w");
	$sHoraInfo	= fndb_getHorariobyDiaSemana($dDiaActual);
	$dHoraInicio 	= date("Y-m-d")." ".$sHoraInfo[strtolower('horaInicio')]; #_horaInicio;
	$dHoraFinal 	= date("Y-m-d")." ".$sHoraInfo[strtolower('horaFinal')]; #_horaFinal;

	if ( ($dHoraActual >= $dHoraInicio) && ($dHoraActual <= $dHoraFinal) ){
	}else{
		if ($_SESSION[_NameSession_nickUsuario] != 'admin'){
		$isAcceso = false;
		session_destroy();
		header("Location: ".JPATH_BASE_WEB.'?me=0');
		exit();
		}
	}
		
	$isAcceso = true;
	$currentTime 					= time();
	if ( fn_checkSessionExpire_Int($currentTime) ){
		$isAcceso = false;
		$pMensajesLogin = "La sesión ha caducado, por favor ingrese nuevamente";
		session_destroy();
		$_SESSION[_NameSession_mensaje] = $pMensajesLogin;
		header("Location: ".JPATH_BASE_WEB);	
	}
	else {
		$_MinutosExpira					= (_MinExpireSession+1);
		$_SESSION[_NameSession_expire] 	= (time()+60*$_MinutosExpira);
		$isAcceso = true;
	}
	
}
else
{
	$pMensajesLogin = "No puedes acceder, debes loguearte";
	if ($isMostrarAlert) {
		$_SESSION[_NameSession_mensaje] = $pMensajesLogin;
		header("Location: ".JPATH_BASE_WEB);
	}
}

// revisa si expiro la Session_expire
function fn_checkSessionExpire_Int($time){ 
	if($time > $_SESSION[_NameSession_expire])  {	
		return true;
	}	
	else {
		return false;
	}
}
?>