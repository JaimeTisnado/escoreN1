<?php
$approot = dirname($_SERVER['SCRIPT_NAME']);
$BFolder = explode('/', $approot);
$Folder = $approot;
if(isset($BFolder[1])) $Folder = $BFolder[1];

if (!defined('BASE_FOLDER')) define('BASE_FOLDER',$Folder);
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if (!defined('DSW')) define('DSW', '/' );
//Defines.
if (!defined('FBASE')) define('FBASE', ( BASE_FOLDER == 'panel' ? NULL : BASE_FOLDER ) );
if (!defined('JPORT')) define('JPORT', ( $_SERVER['SERVER_PORT'] == 80 ? NULL : ':'.$_SERVER['SERVER_PORT'] ) );
if (!defined('JPATH_ROOT')) define('JPATH_ROOT',$_SERVER['DOCUMENT_ROOT'].DSW.FBASE);
if (!defined('JPATH_BASE')) define('JPATH_BASE', JPATH_ROOT);
if (!defined('JPATH_BASE_PANEL')) define('JPATH_BASE_PANEL', JPATH_ROOT.'/panel');

// --------- path de pagina principal
$RutaWeb 	= $_SERVER['SERVER_NAME'].JPORT.DSW.FBASE;
$RutaWeb 	= ( (substr($RutaWeb, -1) == DSW) ? substr($RutaWeb,0,strlen($RutaWeb) - 1) : $RutaWeb ); 
if (!defined('JPATH_BASE_WEB')) define('JPATH_BASE_WEB', 'http://'.$RutaWeb );
// --------- path del panel administrador
if (!defined('JPATH_BASE_WEB_PANEL')) define('JPATH_BASE_WEB_PANEL', 'http://'.JPATH_BASE_WEB.'/panel' );

date_default_timezone_set("America/Tegucigalpa");
setlocale(LC_TIME, 'es_HN');
error_reporting(E_ALL ^ E_NOTICE); //deshabilitar error type NOTICE

// ------------------- DEFINICIONES -----------------------
// Generales
define('_metaAutor','SE');
define('_nomApp','Sistema eScore');
define('_appVersion','v1.0');
define('_nomCompanyDev','');
define('_nomPanel','Dashboard eScore');
define('_baseDatos',2); #1: MySQL, 2: PostGre
define('_numRegistros',10); 
define('_pathImagenesItem','img.preguntas/');
define('_pathImagenesItemPNG','img.preguntas/png/'); 
define('_pathRubricasItem','files/rubricas/'); 
define('_horaInicio','07:30:00'); 
define('_horaFinal','17:00:00'); 

// Nombre de Sesiones
define('_MinExpireSession',5);
define('_NameSession_expire','Session_expire');
define('_NameSession_mensaje','Session_mensaje');
define('_NameSession_id','Session_id');
define('_NameSession_nomUser','Session_nomUser');
define('_NameSession_idUser','Session_idUser');
define('_NameSession_nickUsuario','Session_nickUsuario');
define('_NameSession_idPerfil','Session_idPerfil');
define('_NameSession_descPerfil','Session_descPerfil');	

// ------------------- FILES -----------------------
#require_once(JPATH_BASE.DS."mod.includes/sessions.php"); 
require_once(JPATH_BASE.DS."mod.config/connection.php");
require_once(JPATH_BASE.DS."mod.includes/funciones.php"); 
require_once(JPATH_BASE.DS."mod.datos/funcionesDb.php"); 
require_once(JPATH_BASE.DS."mod.datos/listas.php"); 
require_once(JPATH_BASE.DS.'mod.includes/class.phpass.php');
?>