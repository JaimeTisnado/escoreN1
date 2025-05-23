<?php
//Definir Base Folder
$approot = dirname($_SERVER['SCRIPT_NAME']);
$BFolder = explode('/', $approot);
echo '<pre>';
print_r($BFolder);
echo '</pre>';
define('BASE_FOLDER', $BFolder[1]);
define('DS', DIRECTORY_SEPARATOR);
define('DSW', '/' );

//Defines Path Bases
define('FBASE', ( BASE_FOLDER == 'panel' ? NULL : BASE_FOLDER ) );
define('JPORT', ( $_SERVER['SERVER_PORT'] == 80 ? NULL : ':'.$_SERVER['SERVER_PORT'] ) );
define(JPATH_ROOT,$_SERVER['DOCUMENT_ROOT'].DSW.FBASE);
define('JPATH_BASE', JPATH_ROOT);
define('JPATH_BASE_PANEL', JPATH_BASE.'/panel');

//path de pagina principal
$RutaWeb 	= $_SERVER['SERVER_NAME'].JPORT.DSW.FBASE;
$RutaWeb 	= ( (substr($RutaWeb, -1) == DSW) ? substr($RutaWeb,0,strlen($RutaWeb) - 1) : $RutaWeb ); 
define('basePATHWeb', $RutaWeb);
define('basePATHDir', JPATH_BASE);
define('JPATH_BASE_WEB', 'http://'.basePATHWeb );

//path del panel administrador
define('basePATHWebPanel', $RutaWeb.'/panel');
define('basePATHDirPanel', JPATH_BASE_PANEL);
define('JPATH_BASE_WEB_PANEL', 'http://'.basePATHWebPanel );

echo "FBASE: ".FBASE."<br/>";
echo "JPATH_ROOT: ".JPATH_ROOT."<br/>";
echo "JPATH_BASE: ".JPATH_BASE."<br/>";
echo "JPATH_BASE_PANEL: ".JPATH_BASE_PANEL."<br/>";

echo "JPATH_BASE_WEB: ".JPATH_BASE_WEB."<br/>";
echo "JPATH_BASE_WEB_PANEL: ".JPATH_BASE_WEB_PANEL."<br/>";

?>