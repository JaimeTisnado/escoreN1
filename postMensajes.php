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
fndb_getMensajesNoLeidosTop();
/*
$nMensajes = rand(0,10);
if ($nMensajes !=0){
echo '
<li><a href="javascript:;"><span class="msg">'.$nMensajes.'</span> Mensajes<i class="icon-right fa-caret-down"></i></a>
	<ul class="list_top_sub">
		<li><a href="javascript:;">Primer mensaje pendiente</a></li>
		<li><a href="javascript:;">Segundo mensaje pendiente</a></li>
		<li class="divider"></li>
		<li><a href="javascript:;">Todos los mensajes</a></li>
	</ul>
</li>
';
}else{
echo '
<li><a href="javascript:;"><span class="msg" id="msg">'.$nMensajes.'</span> Mensajes</a></li>
';
}
*/
?>