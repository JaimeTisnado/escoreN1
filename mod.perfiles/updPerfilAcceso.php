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
$isMostrarAlert = true;
include_once(JPATH_BASE.DS."mod.includes/sessions.php"); 
?>
<?php

$tipo			 	= $_POST['tipo'];
$idPerfilAcceso	 	= $_POST['idPerfilAcceso'];

if ( $tipo == 'del' ){
fndb_deletePerfilAcceso ($idPerfilAcceso);	
} else if ( $tipo == 'pub' ) {
fndb_editarEstadoPerfilAcceso ($idPerfilAcceso);	
}


?>