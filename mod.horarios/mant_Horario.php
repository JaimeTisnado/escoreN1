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
fndb_getHorariobyId($ID);
}

if ($Submit == 1) {

$idUsuario		= $_SESSION[_NameSession_idUser];
$cmbDia			= $_POST['cmbDia'];
$txtHoraInicio	= $_POST['txtHoraInicio'];
$txtHoraFinal	= $_POST['txtHoraFinal'];
$rbPub			= $_POST['rbPub'];

if ( $cmbDia == 0 ){
	$s_eDia		= 1;
	$eDia 		= "Requerido";
}

if ( strlen(trim($txtHoraInicio)) == 0 ){
	$s_eHoraInicio	= 1;
	$eHoraInicio 	= "Requerido";
}

if ( strlen(trim($txtHoraFinal)) == 0 ){
	$s_eHoraFinal	= 1;
	$eHoraFinal 	= "Requerido";
}

	if($s_eDia != 1 && $s_eHoraInicio != 1 && $s_eHoraFinal != 1 ){
	
		if(isset($ID)){
			$nExiste	= fndb_existeHorario($cmbDia);
			if ($nExiste == 0 || $cmbDia == $sArray[strtolower('diaSemana')]){
				fndb_editarHorario($idUsuario, $ID, $cmbDia, $txtHoraInicio, $txtHoraFinal, $rbPub);
				$cssMensaje	= fn_getCssMensaje(1);
				$lblMensaje	= "Horario Modificado Correctamente";
				echo '<script type="text/javascript">
					window.history.go(-2);
					</script>';
			}else{
				$cssMensaje	= fn_getCssMensaje(2);
				$lblMensaje	= "El día de semana ya se encuentra registrado";
			}
		}
		else {
			$nExiste	= fndb_existeHorario($cmbDia);
			if ($nExiste == 0){
				fndb_nuevoHorario($idUsuario, $cmbDia, $txtHoraInicio, $txtHoraFinal, $rbPub);
				header("Location: ".pUrl.""); 
			}else{
				$cssMensaje	= fn_getCssMensaje(2);
				$lblMensaje	= "El día de semana ya se encuentra registrado";
			}
		}
	
	} // end validacion

} //end if Submit

// definicion de post o get 
$cmbDia 		= ( (isset($cmbDia)) ? $cmbDia : $sArray[strtolower('diaSemana')] ); 
$txtHoraInicio 	= ( (isset($txtHoraInicio)) ? $txtHoraInicio : $sArray[strtolower('horaInicio')] );
$txtHoraFinal 	= ( (isset($txtHoraFinal)) ? $txtHoraFinal : $sArray[strtolower('horaFinal')] ); 
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
            <i class="icon-widget fa-list"></i><h3><?php echo $mantNombre ?> Horario</h3>
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
                            <td class="row_form">Dia:</td>
                            <td><select name="cmbDia" id="cmbDia" class="Requerido">
                              <option value="0">-Seleccione Día Semana-</option>
                              <?php
							$diaMin = 1;
	  						$diaMax = 7;
						 	while ($diaMin <= $diaMax)
							{
							 
							 $idDia 	= $diaMin;
							 $nomDia	= fn_getNombreSemana($idDia);
							 							
							 if ($idDia == $cmbDia ) {
							  	$value = 	"value=$idDia selected=\"selected\" ";
							 } else {
								$value = 	"value=$idDia"; 
							 }
							 $diaMin++;
						?>
                              <option <?php echo $value; ?>><?php echo $nomDia;?></option>
                              <?php
                            }
                        ?>
                            </select>
                            <?php echo '<span class="letraError">'.$eDia.'</span>'; ?></td>
                          </tr>
                          <tr>
                            <td class="row_form">Hora Inicio:</td>
                            <td><input name="txtHoraInicio" type="text" class="Requerido" id="txtHoraInicio" value="<?php echo $txtHoraInicio; ?>" size="15" maxlength="10" readonly="readonly" />
                            <?php echo '<span class="letraError">'.$eHoraInicio.'</span>'; ?></td>
                          </tr>
                          <tr>
                            <td class="row_form">Hora Final:</td>
                            <td><input name="txtHoraFinal" type="text" class="Requerido" id="txtHoraFinal" value="<?php echo $txtHoraFinal; ?>" size="15" maxlength="80" />
                            <?php echo '<span class="letraError">'.$eHoraFinal.'</span>'; ?></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>
                            El formato de la hora debe ser a 24H.
                            <input name="Submit" type="hidden" id="Submit" value="1" /></td>
                          </tr>
                          <tr>
                            <td class="row_form">Activo:</td>
                            <td><div class="radio">
                              <input  <?php if (!(strcmp($rbPub,"1"))) {echo "checked=\"checked\"";} ?> name="rbPub" type="radio" id="radio3" value="1" checked="checked" />
                              <label>Si</label>
                            </div>
                              <div class="radio">
                                <input  <?php if (!(strcmp($rbPub,"0"))) {echo "checked=\"checked\"";} ?> type="radio" name="rbPub" id="radio4" value="0" />
                                <label>No</label>
                              </div></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
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

<link href="<?php echo JPATH_BASE_WEB.DSW; ?>css/jquery.ui.timepicker.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo JPATH_BASE_WEB.DSW; ?>js/jquery.ui.timepicker.js"></script>
<script type="text/javascript">
$(document).ready(function(){ 
	$("#txtHoraInicio" ).timepicker({
		timeSeparator: ':',
		hourText: 'Hora',             // Define the locale text for "Hours"
		minuteText: 'Minuto',         // Define the locale text for "Minute"
		amPmText: ['AM', 'PM'],
	});
	$("#txtHoraFinal" ).timepicker({
		timeSeparator: ':',
		hourText: 'Hora',             // Define the locale text for "Hours"
		minuteText: 'Minuto',         // Define the locale text for "Minute"
		amPmText: ['AM', 'PM'],
	});
});
</script>

<?php include_once(JPATH_BASE.DS."mod.includes/footerBar.php"); ?>

</body>
</html>