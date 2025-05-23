<?php

$cssMensaje = '';
$lblMensaje = '';



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
define('pUrl', JPATH_BASE_WEB."/mod.accesos/listado.php");
$PAGINA				= $_SERVER['PHP_SELF'];
$ID					= $_GET['id'];
$Submit				= $_POST['Submit'];
$mantNombre			= "Agregar";

/*if(isset($ID)){
$mantNombre	= "Editar";
fndb_getAccesobyId($ID);
}*/


if (isset($ID)) {
    $mantNombre = "Editar";
    $sArray = fndb_getAccesobyId($ID); // Aquí se define la variable correctamente
}




if ($Submit == 1) {
$idUsuario			= $_SESSION[_NameSession_idUser];
$txtNombre			= $_POST['txtNombre'];
$txtEnlace			= $_POST['txtEnlace'];
$cmbPadre			= $_POST['cmbPadre'];
$rbPub				= $_POST['rbPub'];

if ( strlen(trim($txtNombre)) == 0 ){
	$s_eNombre	= 1;
	$eNombre 	= "Requerido";
}

if ( strlen(trim($txtEnlace)) == 0 ){
	$s_eEnlace	= 1;
	$eEnlace 	= "Requerido";
}

	if($s_eNombre != 1 && $s_eEnlace != 1){
	
		if(isset($ID)){
		
			fndb_editarAcceso($ID, $txtNombre, $txtEnlace, $cmbPadre, $rbPub);
			$cssMensaje	= fn_getCssMensaje(1);
			$lblMensaje	= "Acceso Modificado Correctamente";
			echo '<script type="text/javascript">
				window.history.go(-2);
				</script>';
		}
		else {
			fndb_nuevoAcceso($txtNombre, $txtEnlace, $cmbPadre, $rbPub);
			header("Location: ".pUrl.""); 
		}
	
	} // end validacion

} //end if Submit

// definicion de post o get 
/*$txtNombre 			= ( (isset($txtNombre)) ? $txtNombre : $sArray['nomacceso'] ); 




$txtEnlace 			= ( (isset($txtEnlace)) ? $txtEnlace : $sArray['linkacceso'] );
$cmbPadre 			= ( (isset($cmbPadre)) ? $cmbPadre : $sArray['parentid'] );
$rbPub 				= ( (isset($rbPub)) ? $rbPub : $sArray['isactivo'] );*/

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
            <i class="icon-widget fa-list"></i><h3><?php echo $mantNombre ?> Acceso</h3>
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
                            <td class="row_form">Nombre:</td>
                            <td><input name="txtNombre" type="text" class="Requerido" id="txtNombre" value="<?php echo $txtNombre; ?>" size="40" maxlength="80" />
                            <?php echo '<span class="letraError">'.$eNombre.'</span>'; ?></td>
                          </tr>
                          <tr>
                            <td class="row_form">Enlace:</td>
                            <td><input name="txtEnlace" type="text" class="Requerido" id="txtEnlace" value="<?php echo $txtEnlace; ?>" size="60" maxlength="100" />
                            <?php echo '<span class="letraError">'.$eEnlace.'</span>'; ?></td>
                          </tr>
                          <tr>
                            <td class="row_form">Acceso Padre:</td>
                            <td><select name="cmbPadre" id="cmbPadre">
                              <option value="0">-Sin Acceso Padre-</option>
                              <?php
	  			
						 while ($rOW = fn_ExtraerQuery($sQL_getAccesosPadre))
							{
							 
							 $idAcceso 	= $rOW['idacceso'];
							 $nomAcceso	= $rOW['nomacceso'];
							
							 if ($idAcceso == $cmbPadre ) {
							  	$value = 	"value=$idAcceso selected=\"selected\" ";
							 } else {
								$value = 	"value=$idAcceso"; 
							 }
						?>
                              <option <?php echo $value; ?>><?php echo $nomAcceso;?></option>
                              <?php
                            }
                        ?>
                            </select></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="row_form">Activo:</td>
                            <td>
                            <div class="radio">
                            <input  <?php if (!(strcmp($rbPub,"1"))) {echo "checked=\"checked\"";} ?> name="rbPub" type="radio" id="radio3" value="1" checked="checked" /><label>Si</label></div>
                            <div class="radio">
                       <input  <?php if (!(strcmp($rbPub,"0"))) {echo "checked=\"checked\"";} ?> type="radio" name="rbPub" id="radio4" value="0" />												
                      <label>No</label>
                       		</div>
                            </td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td><input name="Submit" type="hidden" id="Submit" value="1" /></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>
                            <a href="javascript:;" id="frmGuardar" class="btn btn_succes">Guardar</a>
                            <a href="javascript:;" class="btn" onclick="jsRegresar('listado.php')">Cancelar</a>
                            </td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
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