<?php
$approot = dirname($_SERVER['SCRIPT_NAME']);
$BFolder = explode('/', $approot);
define('BASE_FOLDER',$BFolder[1]);
define('DS', DIRECTORY_SEPARATOR);
//Defines.
define('FBASE', ( BASE_FOLDER == 'panel' ? NULL : BASE_FOLDER ) );
define('JPATH_BASE',$_SERVER['DOCUMENT_ROOT'].'/'.FBASE);
define('JPATH_BASE_PANEL', JPATH_BASE.'/panel');

//--->Configuracion General
require_once(JPATH_BASE.DS.'mod.config/config.php');
include_once(JPATH_BASE.DS."mod.includes/sessions.php"); 
?>
<?php

#======== ASIGNACION DE IMAGEN ========
#1. Revisar si tiene imagen asignada y no califico, si tiene entonces la eliminamos
$idUsuario	= $_SESSION[_NameSession_idUser];
$existeAsig	= fndb_existeAsignacionImagenbyUsuario($idUsuario);#, $idImagen, $anioLectivo);
if ($existeAsig != 0){ #existen asignaciones
	#Existe asignacion previa, eliminar.
	fndb_desasignarImagenbyUsuario($idUsuario);#, $idImagen, $anioLectivo);	
}

#======== ASIGNACION DE IMAGEN ========

session_destroy();
session_write_close();
header ("Location: index.php");
?>