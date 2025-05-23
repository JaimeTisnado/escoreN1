<?php
$approot = dirname($_SERVER['SCRIPT_NAME']);
$BFolder = explode('/', $approot);
$Folder = $approot;
if(isset($BFolder[1])) $Folder = $BFolder[1];
if (!defined('BASE_FOLDER')) define('BASE_FOLDER',$Folder);
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
//Defines.
if (!defined('FBASE')) define('FBASE', ( BASE_FOLDER == 'panel' ? NULL : BASE_FOLDER ) );
if (!defined('JPATH_BASE')) define('JPATH_BASE',$_SERVER['DOCUMENT_ROOT'].'/'.FBASE);
if (!defined('JPATH_BASE_PANEL')) define('JPATH_BASE_PANEL', JPATH_BASE.'/panel');

session_cache_limiter('nocache');
session_cache_expire(0);
session_start(); 							// inicia sessiones
session_regenerate_id();

#echo JPATH_BASE;
//--->Configuracion General
require_once(JPATH_BASE.DS.'mod.config/config.php');
?>
<?php
/* 
Capa : Login de Acceso
Archivo : _ajax_login.php
Funcion : Verifica el usuario y Password al entrar al sistema, y devuelve el tipo de perfil que tendrá en el sistema.
Dependencias Archivos : conf.php
Dependencias BD : Tablas relacionadas a Usuarios y niveles de permisos.
*/

$txtUsuario 	= $_POST["txtUsuario"];
$txtPassword 	= $_POST["txtPassword"];

$s_eUsuario 	= 0;
$eUsuario		= NULL;
$s_ePassword 	= 0;
$ePassword		= NULL;

if ( strlen(trim($txtUsuario)) == 0 ){
	$s_eUsuario	= 1;
	$eUsuario 	= "Requerido";
	echo '<span class="'.fn_getCssMensaje(2).'">El usuario es requerido</span>'.$txtUsuario;
	exit;
}

if ( strlen(trim($txtPassword)) == 0 ){
	$s_ePassword	= 1;
	$ePassword 	= "Requerido";
	echo '<span class="'.fn_getCssMensaje(2).'">La contraseña es requerida </span>';
	//echo '<span class="' . fn_getCssMensaje(2) . '">La contraseña es requerida para el usuario: ' . $txtUsuario . '</span>';

	exit;
}

if ($s_eUsuario != 1 && $s_ePassword != 1){
	
	if (fndb_existeUsuario($txtUsuario) == 0 ){
		echo '<span class="'.fn_getCssMensaje(2).'">Lo sentimos, usuario no encontrado</span>';
		exit;
	}
	$sArrayLogin	= fndb_loginUsuario($txtUsuario, $txtPassword);
	#echo json_encode($arr);*/
	//#$sArrayLogin = array("idUsuario" => 1,"nomUsuario" => 'Fernando',"nickUsuario" => 'admin',"idPerfil" => 1,"nomPerfil" => 'Revisor', "isActivo" => true);
	if ( $sArrayLogin['idusuario'] == 0 ){
		echo '<span class="'.fn_getCssMensaje(2).'">La contraseña es incorrecta</span>';
		exit;
	}else {
		if ( $sArrayLogin['isactivo'] == false ){
			echo '<span class="'.fn_getCssMensaje(2).'">Su usuario se encuentra inactivo</span>';
			exit;
		}else{
			$_SESSION[_NameSession_id] 			= session_id();
			$_SESSION[_NameSession_idUser] 		= $sArrayLogin[strtolower('idUsuario')];
			$_SESSION[_NameSession_nomUser] 	= $sArrayLogin[strtolower('nomUsuario')];
			$_SESSION[_NameSession_nickUsuario] = $sArrayLogin[strtolower('nickUsuario')];
			$_SESSION[_NameSession_idPerfil] 	= $sArrayLogin[strtolower('idPerfil')];
			$_SESSION[_NameSession_descPerfil] 	= $sArrayLogin[strtolower('nomPerfil')];
			$_MinutosExpira						= (_MinExpireSession+1);
			$_SESSION[_NameSession_expire] 		= time()+(60*$_MinutosExpira);
			
			#echo $_SESSION[_NameSession_Id];
			fndb_editarLoginUsuario($_SESSION[_NameSession_idUser],fn_getIP());
			echo "0";
			#header("Location: dashboard.php");
		}#Fin isActivo
	}#Fin idUsuario
	
}#Post Completo

?>