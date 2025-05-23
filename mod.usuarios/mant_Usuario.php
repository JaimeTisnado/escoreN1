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
fndb_getUsuariobyId($ID);
}

if ($Submit == 1) {

$idUsuario			= $_SESSION[_NameSession_idUser];
$txtNombre			= $_POST['txtNombre'];
$txtUsuario			= $_POST['txtUsuario'];
$cmbPerfil			= $_POST['cmbPerfil'];
$cmbItem			= $_POST['cmbItem'];
$txtPassword		= $_POST['txtPassword'];
$txtPasswordConf	= $_POST['txtPasswordConf'];
$rbPub				= $_POST['rbPub'];

if ( strlen(trim($txtNombre)) == 0 ){
	$s_eNombre	= 1;
	$eNombre 	= "Requerido";
}

if ( strlen(trim($txtUsuario)) == 0 ){
	$s_eUsuario	= 1;
	$eUsuario 	= "Requerido";
}

if ( $cmbPerfil == 0 ){
	$s_ePerfil	= 1;
	$ePerfil 	= "Requerido";
}

if ( $cmbItem == 0 ){
	$s_eItem	= 1;
	$eItem		= "Requerido";
}

if ( strlen(trim($txtPassword)) == 0 ){
	$s_ePassword	= 1;
	$ePassword 	= "Requerido";
}

if ( strlen(trim($txtPasswordConf)) == 0 ){
	$s_ePasswordConf	= 1;
	$ePasswordConf 	= "Requerido";
}

if ( $txtPassword != $txtPasswordConf ){ 
	$s_ePasswordConf	= 1;
	$cssMensaje			= fn_getCssMensaje(2);
	$lblMensaje			= "Confirmación de Contraseña es Incorrecta";
}

	if( $s_eNombre != 1 && $s_eUsuario != 1 && $s_ePerfil != 1 && $s_eItem != 1 && $s_ePassword != 1 && $s_ePasswordConf != 1){
		
		$wp_hasher = new PasswordHash(8, true);
		$Password = $txtPassword; #$wp_hasher->HashPassword($txtPassword);
		
		if(isset($ID)){
			
			$nExiste = fndb_existeUsuario($txtUsuario);
			if ($nExiste == 0 || $txtUsuario == $sArray[strtolower('nickUsuario')]){
				fndb_editarUsuario($ID, $cmbPerfil, $cmbItem, $txtNombre, $txtUsuario, $Password, $rbPub);
				$cssMensaje	= fn_getCssMensaje(1);
				$lblMensaje	= "Usuario Modificado Correctamente";
				#header("Location: ".pUrl."");
				echo '<script type="text/javascript">
				window.history.go(-2);
				</script>';
			}
			else{
				$cssMensaje	= fn_getCssMensaje(2);
				$lblMensaje	= "Usuario ya se encuentra Registrado";
			}
		}
		else {
			
			$nExiste = fndb_existeUsuario($txtUsuario);
			if ($nExiste == 0){
				fndb_nuevoUsuario($cmbPerfil, $cmbItem, $txtNombre, $txtUsuario, $Password, $rbPub);
				header("Location: ".pUrl.""); 
			}
			else{
				$cssMensaje	= fn_getCssMensaje(2);
				$lblMensaje	= "Usuario ya se encuentra Registrado";
			}
		}
	
	} // end validacion

} //end if Submit

// definicion de post o get 
$txtNombre 		= ( (isset($txtNombre)) ? $txtNombre : $sArray[strtolower('nomUsuario')] ); 
$txtUsuario 	= ( (isset($txtUsuario)) ? $txtUsuario : $sArray[strtolower('nickUsuario')] );
$cmbPerfil 		= ( (isset($cmbPerfil)) ? $cmbPerfil : $sArray[strtolower('idPerfil')] );
$cmbItem 		= ( (isset($cmbItem)) ? $cmbItem : $sArray[strtolower('idItem')] );
$txtPassword 	= ( (isset($txtPassword)) ? $txtPassword : $sArray[strtolower('passUsuario')] );
$txtPasswordConf 	= ( (isset($txtPasswordConf)) ? $txtPasswordConf : $sArray[strtolower('passUsuario')] );
$rbPub 			= ( (isset($rbPub)) ? $rbPub : $sArray[strtolower('isActivo')] );

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
            	      <td><input name="txtNombre" type="text" class="Requerido" id="txtNombre" value="<?php echo $txtNombre; ?>" size="40" maxlength="100" />
            	        <?php echo '<span class="letraError">'.$eNombre.'</span>'; ?></td>
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
            	      <td class="row_form">Usuario:</td>
            	      <td><input name="txtUsuario" type="text" class="Requerido" id="txtUsuario" value="<?php echo $txtUsuario; ?>" size="30" maxlength="40" />
           	          <?php echo '<span class="letraError">'.$eUsuario.'</span>'; ?></td>
            	      <td valign="top" class="row_form">Perfil:</td>
            	      <td valign="top"><select name="cmbPerfil" class="Requerido" id="cmbPerfil">
            	        <option value="0">-Seleccione Perfil-</option>
            	        <?php
	  			
						 while ($rOW = fn_ExtraerQuery($sQL_getPerfiles))
							{
							 
							 $idPerfil 	= $rOW[strtolower('idPerfil')];
							 $nomPerfil	= $rOW[strtolower('nomPerfil')];
							
							 if ($idPerfil == $cmbPerfil ) {
							  	$value = 	"value=$idPerfil selected=\"selected\" ";
							 } else {
								$value = 	"value=$idPerfil"; 
							 }
						?>
            	        <option <?php echo $value; ?>><?php echo $nomPerfil;?></option>
            	        <?php
                            }
                        ?>
          	        </select>            	        <?php echo '<span class="letraError">'.$ePerfil.'</span>'; ?></td>
          	      </tr>
            	    <tr>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
            	      <td class="row_form">Item:</td>
            	      <td><select name="cmbItem" class="Requerido" id="cmbItem">
            	        <option value="0">-Seleccione Item-</option>
            	        <?php
	  			
						 while ($rOW = fn_ExtraerQuery($sQL_getItems))
							{
							 
							 $idItem 	= $rOW[strtolower('idItem')];
							 $nomItem	= $rOW[strtolower('nomItem')];
							
							 if ($idItem == $cmbItem ) {
							  	$value = 	"value=$idItem selected=\"selected\" ";
							 } else {
								$value = 	"value=$idItem"; 
							 }
						?>
            	        <option <?php echo $value; ?>><?php echo $nomItem;?></option>
            	        <?php
                            }
                        ?>
          	        </select>
           	          <?php echo '<span class="letraError">'.$eItem.'</span>'; ?></td>
          	      </tr>
            	    <tr>
            	      <td class="row_form">Contraseña</td>
            	      <td><input name="txtPassword" type="password" class="Requerido" id="txtPassword" value="<?php echo $txtPassword; ?>" size="30" maxlength="20" />
           	          <?php echo '<span class="letraError">'.$ePassword.'</span>'; ?></td>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
          	      </tr>
            	    <tr>
            	      <td class="row_form">Confirmar:</td>
            	      <td><input name="txtPasswordConf" type="password" class="Requerido" id="txtPasswordConf" value="<?php echo $txtPasswordConf; ?>" size="30" maxlength="20" />
           	          <?php echo '<span class="letraError">'.$ePasswordConf.'</span>'; ?></td>
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
            	      <td><input name="Submit" type="hidden" id="Submit" value="1" /></td>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
          	      </tr>
            	    <tr>
            	      <td>&nbsp;</td>
            	      <td>
                      	 <a href="javascript:;" id="frmGuardar" class="btn btn_succes">Guardar</a>
                         <a href="javascript:;" class="btn" onclick="jsRegresar('index.php')">Cancelar</a>
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