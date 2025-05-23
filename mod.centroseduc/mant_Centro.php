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
$PAGINA				= $_SERVER['PHP_SELF'];
$IDDepto			= $_GET['idDepto'];
$ID					= $_GET['id'];
$Submit				= $_POST['Submit'];
$mantNombre			= "Agregar";
define('pUrl', "index.php");

if(isset($ID)){
$mantNombre	= "Editar";
fndb_getCentroEducativobyId($ID);
}

if ($Submit == 1) {

$idUsuario			= $_SESSION[_NameSession_idUser];
$cmbDepto			= $_POST['cmbDepto'];
$cmbMuni			= $_POST['cmbMuni'];
$txtNombre			= $_POST['txtNombre'];
$txtCodigo			= $_POST['txtCodigo'];
$rbPub				= $_POST['rbPub'];

if ( strlen(trim($txtNombre)) == 0 ){
	$s_eNombre	= 1;
	$eNombre 	= "Requerido";
}

if ( strlen(trim($txtCodigo)) == 0 ){
	$s_eCodigo	= 1;
	$eCodigo 	= "Requerido";
}

if ( $cmbMuni == 0 ){
	$s_eMuni	= 1;
	$eMuni 		= "Requerido";
}

	if($s_eNombre != 1 && $s_eCodigo != 1 && $s_eMuni != 1 ){
	
		if(isset($ID)){
			
			$nExiste	= fndb_existeCentroEducativo($txtCodigo);
			if ($nExiste == 0 || $txtCodigo == $sArray[strtolower('codigoCentro')]){
				fndb_editarCentroEducativo($idUsuario, $ID, $cmbMuni, $txtNombre, $txtCodigo, $rbPub);
				$cssMensaje	= fn_getCssMensaje(1);
				$lblMensaje	= "Centro Educativo Modificado Correctamente";
				echo '<script type="text/javascript">
				window.history.go(-2);
				</script>';
			}else{
				$cssMensaje	= fn_getCssMensaje(2);
				$lblMensaje	= "El Código del Centro Educativo ya se encuentra registrado";
			}
		}
		else {
			
			$nExiste	= fndb_existeCentroEducativo($txtCodigo);
			if ($nExiste == 0){
				fndb_nuevoCentroEducativo($idUsuario, $cmbMuni, $txtNombre, $txtCodigo, $rbPub);
				header("Location: ".pUrl.""); 
			}else{
				$cssMensaje	= fn_getCssMensaje(2);
				$lblMensaje	= "El Código del Centro Educativo ya se encuentra registrado";
			}
		}
	
	} // end validacion

} //end if Submit

// definicion de post o get 
$cmbDepto			= ( (isset($cmbDepto)) ? $cmbDepto : $sArray[strtolower('idDepartamento')] ); 
$cmbMuni 			= ( (isset($cmbMuni)) ? $cmbMuni : $sArray[strtolower('idMunicipio')] ); 
$txtNombre 			= ( (isset($txtNombre)) ? $txtNombre : $sArray[strtolower('nomCentro')] ); 
$txtCodigo 			= ( (isset($txtCodigo)) ? $txtCodigo : $sArray[strtolower('codigoCentro')] ); 
$rbPub 				= ( (isset($rbPub)) ? $rbPub : $sArray[strtolower('isActivo')] );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once(JPATH_BASE.DS."mod.includes/metaHeader.php"); ?>
<script type="application/javascript">
$(function(){
	$("#cmbDepto").change(function(){
		$.post("cmbMunicipios.php",{ idDepartamento:$(this).val()},
		function(data){
			$("#cmbMuni").html(data);
		})
	});
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
            <i class="icon-widget fa-list"></i><h3><?php echo $mantNombre ?> Centro Educativo</h3>
        </div>
        <div class="widget_content">
                   
            <div id="PanelMantenimiento">
            	<form id="form1" name="form1" method="post" action="">
                        <table cellpadding="0" cellspacing="1">
                          <tr>
                            <td class="width150">&nbsp;</td>
                            <td class="width500">&nbsp;</td>
                          </tr>
                          <tr>
                          	<td colspan="2"><div class="divContentMsg"><?php echo '<span class="'.$cssMensaje.'">'.$lblMensaje.'</span>';?></div></td>
                          </tr>
                          <tr>
                            <td class="row_form">Departamento:</td>
                            <td><select name="cmbDepto" id="cmbDepto">
                              <option value="0">-Seleccione Departamento-</option>
                              <?php
	  			
						 while ($rOW = fn_ExtraerQuery($sQL_getDepartamentos))
							{
							 
							 $idDepartamento 	= $rOW[strtolower('idDepartamento')];
							 $nomDepartamento	= $rOW[strtolower('nomDepartamento')];
							 							
							 if ($idDepartamento == $cmbDepto ) {
							  	$value = 	"value=$idDepartamento selected=\"selected\" ";
							 } else {
								$value = 	"value=$idDepartamento"; 
							 }
						?>
                              <option <?php echo $value; ?>><?php echo $nomDepartamento;?></option>
                              <?php
                            }
                        ?>
                            </select></td>
                          </tr>
                          <tr>
                            <td class="row_form">Municipio:</td>
                            <td><select name="cmbMuni" id="cmbMuni" class="Requerido">
                              <option value="0">-Seleccione Municipio-</option>
                              <?php
	  			
						 while ($rOW = fn_ExtraerQuery($sQL_getMunicipios))
							{
							 
							 $idMunicipio 		= $rOW[strtolower('idMunicipio')];
							 $nomMunicipio		= $rOW[strtolower('nomMunicipio')];
							 							
							 if ($idMunicipio == $cmbMuni ) {
							  	$value = 	"value=$idMunicipio selected=\"selected\" ";
							 } else {
								$value = 	"value=$idMunicipio"; 
							 }
						?>
                              <option <?php echo $value; ?>><?php echo $nomMunicipio;?></option>
                              <?php
                            }
                        ?>
                            </select>
                            <?php echo '<span class="letraError">'.$eMuni.'</span>'; ?></td>
                          </tr>
                          <tr>
                            <td class="row_form">Nombre:</td>
                            <td><input name="txtNombre" type="text" class="Requerido" id="txtNombre" value="<?php echo $txtNombre; ?>" size="40" maxlength="80" />
                            <?php echo '<span class="letraError">'.$eNombre.'</span>'; ?></td>
                          </tr>
                          <tr>
                            <td class="row_form">Código:</td>
                            <td><input name="txtCodigo" type="text" class="Requerido" id="txtCodigo" value="<?php echo $txtCodigo; ?>" size="12" maxlength="9" />
                            <?php echo '<span class="letraError">'.$eCodigo.'</span>'; ?></td>
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
                            <a href="javascript:;" class="btn" onclick="jsRegresar('index.php')">Cancelar</a>
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