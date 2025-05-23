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
define('pUrl', JPATH_BASE_WEB."/mod.rubricas/");
$PAGINA				= $_SERVER['PHP_SELF'];
$ID					= $_GET['id'];
$Submit				= $_POST['Submit'];
$mantNombre			= "Agregar";

if(isset($ID)){
$mantNombre	= "Editar";
fndb_getRubricabyId($ID);
}

if ($Submit == 1) {

$idUsuario			= $_SESSION[_NameSession_idUser];
$cmbItem			= $_POST['cmbItem'];
$txtNombre			= $_POST['txtNombre'];
$txtMemo			= $_POST['txtMemo'];
$rbPub				= $_POST['rbPub'];

$txtArchivo	 		= "txtArchivo";

$Archivo 			= $_FILES[$txtArchivo]['tmp_name'];
$Nombre_Archivo 	= $_FILES[$txtArchivo]['name']; 
$Tipo_Archivo 		= $_FILES[$txtArchivo]['type'];
$Tamano_Archivo 	= $_FILES[$txtArchivo]['size']; 
$Extension			= pathinfo($Nombre_Archivo); //substr($Nombre_Archivo,-4,4);
$ExtImage			= strtolower($Extension['extension']);
$txtNombreArchivo	= $sArray[strtolower('rutaRubrica')];


if ( $cmbItem == 0 ){
	$s_eItem	= 1;
	$eItem 		= "Requerido";
}

if ( strlen(trim($txtNombre)) == 0 ){
	$s_eNombre	= 1;
	$eNombre 	= "Requerido";
}


if ( strlen(trim($Archivo)) != 0 ){
	$s_eArchivo	= 0;
	$infoItem			= fndb_getItembyId($cmbItem);
	$txtNombreArchivo 	= $infoItem[strtolower('idItem')].'_'.$infoItem[strtolower('nomItem')].".".$ExtImage;
	
	#($ExtImage != "doc") && ($ExtImage != "docx") &&
	if ( ($ExtImage != "txt") && ($ExtImage != "pdf") ){
		$s_eArchivo	= 1;
		$eArchivo 	= "Extension permitida: txt|pdf";
	}
	
	#Subida del archivo
	$dirFiles 	= realpath(JPATH_BASE.DS._pathRubricasItem);
	$newFile	= $dirFiles.DS.$txtNombreArchivo;

	if ( !is_dir($dirFiles) ) mkdir($dirFiles);
	move_uploaded_file($Archivo,$newFile);

}
	if($s_eNombre != 1 && $s_eItem != 1 && $s_eArchivo != 1){
	
		if(isset($ID)){
			$nExiste	= fndb_existeRubricaItem($cmbItem);
			if ($nExiste == 0 || $cmbItem == $sArray[strtolower('idItem')]){
				fndb_editarRubrica($idUsuario, $ID, $cmbItem, $txtNombre, $txtMemo, $txtNombreArchivo, $rbPub);
				$cssMensaje	= fn_getCssMensaje(1);
				$lblMensaje	= "Rubrica Modificada Correctamente";
				#header("Location: ".pUrl."");
				echo '<script type="text/javascript">
				window.history.go(-2);
				</script>';
			}else{
				$cssMensaje	= fn_getCssMensaje(2);
				$lblMensaje	= "El Item seleccionado ya tiene una rubrica registrada";
			}
		}
		else {
			$nExiste	= fndb_existeRubricaItem($cmbItem);
			if ($nExiste == 0){
				fndb_nuevaRubrica($idUsuario, $cmbItem, $txtNombre, $txtMemo, $txtNombreArchivo, $rbPub);
				header("Location: ".pUrl."");
			}else{
				$cssMensaje	= fn_getCssMensaje(2);
				$lblMensaje	= "El Item seleccionado ya tiene una rubrica registrada";
			}
		}
		
	
	} // end validacion

} //end if Submit

// definicion de post o get 
$cmbItem 			= ( (isset($cmbItem)) ? $cmbItem : $sArray[strtolower('idItem')] ); 
$txtNombre 			= ( (isset($txtNombre)) ? $txtNombre : $sArray[strtolower('nomRubrica')] ); 
$txtMemo 			= ( (isset($txtMemo)) ? $txtMemo : $sArray[strtolower('memoRubrica')] ); 
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
            <i class="icon-widget fa-list"></i><h3><?php echo $mantNombre ?> Rubrica</h3>
        </div>
        <div class="widget_content">
                   
            <div id="PanelMantenimiento">
            	<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
                        <table cellpadding="0" cellspacing="1">
                          <tr>
                            <td class="width150">&nbsp;</td>
                            <td class="width500">&nbsp;</td>
                          </tr>
                          <tr>
                          	<td colspan="2"><div class="divContentMsg"><?php echo '<span class="'.$cssMensaje.'">'.$lblMensaje.'</span>';?></div></td>
                          </tr>
                          <tr>
                            <td class="row_form">Item:</td>
                            <td><select name="cmbItem" id="cmbItem" class="Requerido">
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
                            <td class="row_form">Nombre:</td>
                            <td><input name="txtNombre" type="text" class="Requerido" id="txtNombre" value="<?php echo $txtNombre; ?>" size="40" maxlength="80" />
                            <?php echo '<span class="letraError">'.$eNombre.'</span>'; ?></td>
                          </tr>
                          <tr>
                            <td class="row_form">Descripción:</td>
                            <td><label for="txtMemo"></label>
                            <textarea name="txtMemo" id="txtMemo" cols="50" rows="6"><?php echo $txtMemo; ?></textarea>                             </td>
                          </tr>
                          <tr>
                            <td class="row_form">Archivo:</td>
                            <td><input type="file" name="txtArchivo" id="txtArchivo" />
                            txt|pdf</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td><?php echo '<span class="letraError">'.$eArchivo.'</span>'; ?></td>
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