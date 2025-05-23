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
define('pUrl', "index.php");
$PAGINA				= $_SERVER['PHP_SELF'];
$Submit				= $_POST['Submit'];

if ($Submit == 1) {
$idUsuario			= $_SESSION[_NameSession_idUser];
$txtNombre			= $_POST['txtNombre'];
$txtAsunto			= $_POST['txtAsunto'];
$txtMensaje 		= $_POST['TEditor'];

$barraInvertida		=	"\\";
$txtMensaje			= htmlspecialchars_decode(str_replace($barraInvertida,'',$txtMensaje));

if ( strlen(trim($txtNombre)) == 0 ){
	$s_eNombre	= 1;
	$eNombre 	= "Requerido";
}

if ( strlen(trim($txtAsunto)) == 0 ){
	$s_eAsunto	= 1;
	$eAsunto 	= "Requerido";
}

	if($s_eNombre != 1 && $s_eAsunto != 1){
	
		fndb_nuevoMensaje($idUsuario, $idUsuario, $txtNombre, $txtAsunto, $txtMensaje);
		header("Location: ".pUrl.""); 

	} // end validacion

} //end if Submit

$txtMensaje 	= htmlspecialchars($txtMensaje);
$barraInvertida	= "\\";
$txtEditor		= htmlspecialchars_decode(str_replace($barraInvertida,'',$txtMensaje));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once(JPATH_BASE.DS."mod.includes/metaHeader.php"); ?>

<script type="text/javascript" src="<?php echo JPATH_BASE_WEB.DSW?>tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
		// General options
		language: "es",
		mode : "exact",
		elements : "TEditor",
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",
		
		// marketing
		theme_advanced_buttons1 : "code,|,preview,fullscreen,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect,",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,undo,redo,|,removeformat,|,bullist,numlist,|,outdent,indent,blockquote",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		
		// propiedades
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		// path CSS Styles
		content_css : "<?php echo JPATH_BASE_WEB.DSW?>css/editor.css",		
    	relative_urls : false, 
		remove_script_host : false
		
});

</script>
</head>
<body>
<?php include_once(JPATH_BASE.DS."mod.includes/noscript.php"); ?>
<?php include_once(JPATH_BASE.DS."mod.includes/headerBar.php"); ?>

<div id="content">

<div class="row">
<div class="container">
<div class="column100">
    
    <div class="widget">
    	<div class="widget_header">
            <i class="icon-widget fa-edit"></i><h3>Escribir Mensaje</h3>
        </div>
        <div class="widget_content">
            
            <div id="PanelMantenimiento">
            	<form id="form1" name="form1" method="post" action="">
                        <table width="650" cellpadding="0" cellspacing="1">
                          <tr>
                            <td class="width150">&nbsp;</td>
                            <td class="width500">&nbsp;</td>
                          </tr>
                          <tr>
                          	<td colspan="2"><div class="divContentMsg"><?php echo '<span class="'.$cssMensaje.'">'.$lblMensaje.'</span>';?></div></td>
                          </tr>
                          <tr>
                            <td class="row_form">Para:</td>
                            <td><input name="txtNombre" type="text" class="Requerido" id="txtNombre" value="<?php echo $txtNombre; ?>" size="40" maxlength="80" />
                            <?php echo '<span class="letraError">'.$eNombre.'</span>'; ?></td>
                          </tr>
                          <tr>
                            <td class="row_form">Asunto:</td>
                            <td><input name="txtAsunto" type="text" class="Requerido" id="txtAsunto" value="<?php echo $txtAsunto; ?>" size="60" maxlength="100" />
                            <?php echo '<span class="letraError">'.$eAsunto.'</span>'; ?></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2"><textarea id="TEditor" name="TEditor" cols="100" rows="15">
							<?php
                            	echo $txtEditor;
                            ?>
                            </textarea></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td><input name="Submit" type="hidden" id="Submit" value="1" /></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>
                            <a href="javascript:;" id="frmGuardar" class="btn btn_succes">Enviar</a>
                            <a href="javascript:;" class="btn" onclick="jsRegresar('index.php')">Cancelar</a>
                            </td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                        </table>
                      </form>
            		</div><!-- PanelMantenimiento -->
                
        </div><!-- content -->
    </div><!-- widget -->
            

   	          
        </div> <!-- column100 -->
    </div> <!-- container -->
</div> <!-- row -->
</div> <!-- content -->

<?php include_once(JPATH_BASE.DS."mod.includes/footerBar.php"); ?>
</body>
</html>