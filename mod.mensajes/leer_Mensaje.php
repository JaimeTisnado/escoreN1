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
$ID					= $_GET['id'];
$Submit				= $_POST['Submit'];
$mantNombre			= "Agregar";

if(isset($ID)){
$mantNombre	= "Editar";
fndb_getMensajebyId($ID);
}

if ($Submit == 1) {

$idUsuario			= $_SESSION[_NameSession_idUser];
$txtNombre			= $_POST['txtNombre'];
$txtCodigo			= $_POST['txtCodigo'];
$txtMemo			= $_POST['txtMemo'];
$rbPub				= $_POST['rbPub'];

if ( strlen(trim($txtNombre)) == 0 ){
	$s_eNombre	= 1;
	$eNombre 	= "Requerido";
}

if ( strlen(trim($txtCodigo)) == 0 ){
	$s_eCodigo	= 1;
	$eCodigo 	= "Requerido";
}

	if($s_eNombre != 1 && $s_eCodigo != 1){
	
		
	
	} // end validacion

} //end if Submit

// definicion de post o get 
$txtNombre 			= ( (isset($txtNombre)) ? $txtNombre : $sArray[strtolower('nomCategoria')] ); 
$txtCodigo 			= ( (isset($txtCodigo)) ? $txtCodigo : $sArray[strtolower('codigoCategoria')] ); 
$txtMemo 			= ( (isset($txtMemo)) ? $txtMemo : $sArray[strtolower('memoCategoria')] ); 
$rbPub 				= ( (isset($rbPub)) ? $rbPub : $sArray[strtolower('isActivo')] );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once(JPATH_BASE.DS."mod.includes/metaHeader.php"); ?>
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
            <i class="icon-widget fa-envelope"></i><h3><?php echo $sArray[strtolower('asunto')]; ?></h3>
        </div>
        <div class="widget_content">
                   
            <div id="PanelMantenimiento">
           	  <a href="index.php" class="btn right"><i class="icon-fa fa-rotate-left"></i>Mensajes</a>
            	<form id="form1" name="form1" method="post" action="">
                        <table cellpadding="0" cellspacing="1">
                      	  <tr>
                            <td class="row_form width10">De:</td>
                            <td class="width90"><?php echo $sArray[strtolower('remitente')]; ?></td>
                          </tr>
                          <tr>
                            <td class="row_form">Fecha:</td>
                            <td>
							<?php 
								$fechaFormateada	= date("d/m/Y h:i:s a", strtotime($sArray[strtolower('fechaMensaje')]));
								echo $fechaFormateada; 
							?></td>
                          </tr>
                          <tr>
                            <td class="row_form" colspan="2"><div class="hr"></div></td>
                          </tr>
                          <tr>
                            <td colspan="2"><?php  echo $sArray[strtolower('mensaje')];?></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td><input name="Submit" type="hidden" id="Submit" value="1" /></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                        </table>
                    </form>
            	</div>
                
        </div><!-- content -->
    </div><!-- widget -->
            

   	          
        </div> <!-- column100 -->
    </div> <!-- container -->
</div> <!-- row -->
</div>

<?php include_once(JPATH_BASE.DS."mod.includes/footerBar.php"); ?>

</body>
</html>
<?php
#Actualizar a Leido el Mensaje
fndb_editarMensajeLeido($ID);
?>