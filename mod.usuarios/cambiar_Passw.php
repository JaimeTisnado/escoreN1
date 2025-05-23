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
define('pUrl', JPATH_BASE_WEB."/dashboard.php");
$PAGINA				= $_SERVER['PHP_SELF'];
$ID					= $_SESSION[_NameSession_idUser];
$Submit				= $_POST['Submit'];
$mantNombre			= "Cambiar Contraseña";

if(isset($ID)){
fndb_getUsuariobyId($ID);
}

if ($Submit == 1) {

$idUsuario			= $_SESSION[_NameSession_idUser];
$txtPassword		= $_POST['txtPassword'];
$txtPassNuevo		= $_POST['txtPassNuevo'];
$txtPassNuevoConf	= $_POST['txtPassNuevoConf'];

if ( strlen(trim($txtPassword)) == 0 ){
	$s_ePassword	= 1;
	$ePassword 	= "Requerido";
}

if ( strlen(trim($txtPassNuevo)) == 0 ){
	$s_ePasswordNuevo	= 1;
	$ePasswordNuevo 	= "Requerido";
}

if ( strlen(trim($txtPassNuevoConf)) == 0 ){
	$s_ePasswordNuevoConf	= 1;
	$ePasswordNuevoConf 	= "Requerido";
}

	if( $s_ePassword != 1 && $s_ePasswordNuevo != 1 && $s_ePasswordNuevoConf != 1 ){
	
		if(isset($ID)){
			
			if ( (($sArray[strtolower('passUsuario')]) != $txtPassword) ){
				$cssMensaje	= fn_getCssMensaje(2);
				$lblMensaje	= "Contraseña Actual es Incorrecta";
			} elseif ( $txtPassNuevo != $txtPassNuevoConf ){ 
				$cssMensaje	= fn_getCssMensaje(2);
				$lblMensaje	= "Confirmación de Contraseña es Incorrecta";
			}else {
				fndb_cambiarPassword($ID, $txtPassNuevo);
				$cssMensaje	= fn_getCssMensaje(1);
				$lblMensaje	= "Contraseña Modificada Correctamente";
			}

				
		}
			
	} // end validacion

} //end if Submit

// definicion de post o get 
$txtPassword 	= ( (isset($txtPassword)) ? $txtPassword : $sArray[strtolower('passUsuario')] );
$txtPassNuevo 	= ( (isset($txtPassNuevo)) ? $txtPassNuevo : "" );
$txtPasswordNuevoConf 	= ( (isset($txtPasswordNuevoConf)) ? $txtPasswordNuevoConf : "" );

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
            <i class="icon-widget fa-list"></i><h3><?php echo $mantNombre ?> Usuario</h3>
        </div>
        <div class="widget_content">
        
        	<div id="PanelMantenimiento">
            	<form id="form1" name="form1" method="post" action="">
            	  <table width="760" cellpadding="0" cellspacing="1">
            	    <tr>
            	      <td class="width130">&nbsp;</td>
            	      <td class="width250">&nbsp;</td>
            	      <td class="width130">&nbsp;</td>
            	      <td class="width250">&nbsp;</td>
          	      </tr>
            	    <tr>
            	      <td colspan="4"><div class="divContentMsg"><?php echo '<span class="'.$cssMensaje.'">'.$lblMensaje.'</span>';?></div></td>
          	      </tr>
            	    <tr>
            	      <td class="row_form">Nombre Completo:</td>
            	      <td><?php echo $sArray[strtolower('nomUsuario')]; ?></td>
            	      <td class="row_form">&nbsp;</td>
            	      <td>&nbsp;</td>
          	      </tr>
            	    <tr>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
          	      </tr>
            	    <tr>
            	      <td class="row_form">Contraseña Actual:</td>
            	      <td><input name="txtPassword" type="password" class="Requerido" id="txtPassword" value="<?php echo $txtPassword; ?>" size="30" maxlength="20" />
           	          <?php echo '<span class="letraError">'.$ePassword.'</span>'; ?></td>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
          	      </tr>
            	    <tr>
            	      <td class="row_form">Nueva Contraseña:</td>
            	      <td><input name="txtPassNuevo" type="password" class="Requerido" id="txtPassNuevo" value="<?php echo $txtPassNuevo; ?>" size="30" maxlength="20" />
           	          <?php echo '<span class="letraError">'.$ePasswordNuevo.'</span>'; ?></td>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
          	      </tr>
            	    <tr>
            	      <td class="row_form">Confirmar:</td>
            	      <td><input name="txtPassNuevoConf" type="password" class="Requerido" id="txtPassNuevoConf" value="<?php echo $txtPassNuevoConf; ?>" size="30" maxlength="20" />
           	          <?php echo '<span class="letraError">'.$ePasswordNuevoConf.'</span>'; ?></td>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
          	      </tr>
            	    <tr>
            	      <td>&nbsp;</td>
            	      <td><input name="Submit" type="hidden" id="Submit" value="1" /></td>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
          	      </tr>
            	    <tr>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
          	      </tr>
            	    <tr>
            	      <td>&nbsp;</td>
            	      <td>
                      <a href="javascript:;" id="frmGuardar" class="btn btn_succes">Guardar</a>
                      <a href="javascript:;" class="btn" onclick="jsRegresar('<?php echo pUrl; ?>')">Cancelar</a>
                      </td>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
            	      <!--- onclick="jsSolCnsltaRemesa(this.form)" -->
          	      </tr>
            	    <tr>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
          	      </tr>
            	    <tr>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
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
</div>

<?php include_once(JPATH_BASE.DS."mod.includes/footerBar.php"); ?>
</body>
</html>