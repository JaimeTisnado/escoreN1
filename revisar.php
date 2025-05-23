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
$Submit				= $_POST['Submit'];
$fechaInicio		= fn_getFechaHoraDefault();
$idUsuario			= $_SESSION[_NameSession_idUser];

#============ PROCESO DE CALIFICACIOn ==============
if ($Submit == 1) {

$fechaInicio		= $_POST['fechaInicio'];
$anioLectivo		= $_POST['anioLectivo'];
$idImagen			= $_POST['idImagen'];
$rbCalificacion		= $_POST['rbCalificar'];
$rMemoScore			= $_POST['rMemoScore'];
$nCalificacion		= $rbCalificacion;
$IDItemImagen		= $anioLectivo.str_pad($idImagen,6,'0',STR_PAD_LEFT);

if(isset($rbCalificacion)){
	
	#Validar no este revisado el item para otorgar la calificacion.
	$vRevisado		= fndb_validaRevision($anioLectivo, $idImagen);
	if ($vRevisado == 0){
		$rCalificacion	= fndb_calificarItem($fechaInicio, $idUsuario, $anioLectivo, $idImagen, $rbCalificacion, $rMemoScore);
		$rCalificacionFinal	= fndb_marcarCalificacionFinal($idImagen, $anioLectivo, $nCalificacion);
	}else{
		$rCalificacion	= 3;
		$rCalificacionDoble	= fndb_calificarItemDoble($fechaInicio, $idUsuario, $anioLectivo, $idImagen, $rbCalificacion, $rMemoScore);
	}#vRevisado
}

	if ($rCalificacion == 0){
		$lblMensaje	= '<div id="panelMensaje" class="alert alert-success">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		<b>La calificación ha sido ingresada correctamente.</b>
		</div>';	
	}else if ($rCalificacion == 1) {#Doble Intento Calificacion del mismo usuario.
		$lblMensaje	= '<div id="panelMensaje" class="alert alert-info">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		<b>Lo sentimos, usted ya realizo la calificación para el item.</b>
		</div>';			
	}else if ($rCalificacion == 3) {#Ya existe la revision.
		$lblMensaje	= '<div id="panelMensaje" class="alert alert-success">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		<b>La calificación ha sido ingresada correctamente.</b>
		</div>';
		#Lo sentimos, ya se realizo la revisión para el item.			
	}else{
		$lblMensaje	= '<div id="panelMensaje" class="alert alert-danger">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		<b>Lo sentimos, se presento un error en la calificación.</b>
		</div>';
	}
	$_SESSION['lblMensaje'] = $lblMensaje;
	header("location: ".$PAGINA);
	exit();
	
}#SUBMIT

#============ PROCESO DE CALIFICACIOn ==============



#============ OBTENER IMAGEN A CALIFICAR (COORDINADOR) ==============

normal:
#Imagen a Calificar
fndb_getImagenItemCalificarCoordinador();
$bFlagAnclaje	= true;
$idImagen 		= $sArray[strtolower('idImagen')];
$anioLectivo 	= $sArray[strtolower('anioLectivo')];

$nomImagen 		= $sArray[strtolower('nomImagen')];
$srcImagen		= JPATH_BASE_WEB.DSW._pathImagenesItemPNG.DSW.$nomImagen;
$IDItem			= $sArray[strtolower('idItem')];
$nomItem 		= $sArray[strtolower('nomItem')];
$nomCentro 		= $sArray[strtolower('nomCentro')];
$nomCategoria 	= $sArray[strtolower('nomCategoria')];
$codCategoria 	= $sArray[strtolower('codigoCategoria')];
$nomGrado 		= $sArray[strtolower('nomGrado')];
$codGrado 		= $sArray[strtolower('codigoGrado')];


if (isset($idImagen)){
	#Validacion de imagen previamente asignada
	#1. Revisar si la imagen ya fue asignada a otro usuario, si es asi, buscar otra imagen no asignada
	$existeAsigImagen	= fndb_existeAsignacionImagen($idUsuario, $idImagen, $anioLectivo);
	#2. Revisar si hay mas de 1 imagenes pendientes de revisar para que siga con la busqueda de imagen
	$nItemsPendientes	= fndb_obtenerItemsPendientesRevisar($idImagen, $anioLectivo, $IDItem);
	#echo $existeAsigImagen.'-'.$nItemsPendientes;
	#exit();
	if ($existeAsigImagen != 0){# esta asignada la imagen y no se ha calificado.
		if ($nItemsPendientes == 1){
			#Es la ultima y esta asignada, no hay imagenes
			$bImagenesDisponibles = false;
			unset($idImagen);
		}else{
			if ($nItemsPendientes == 0){# no hay imagenes pendientes, todas estan asignadas
				$bImagenesDisponibles = false;
				unset($idImagen);
			}else{
				#hay imagenes pendientes de revisar, se busca otra
				goto normal;	
			}
		}
	}
}#isset

#============ OBTENER IMAGEN A CALIFICAR (COORDINADOR) ==============



#============ VALIDACIONES GENERALES ==============

#Informacion Máximo de Calificacion
$sRowConf		= fndb_getConfiguracionbyId(1); #Maximo Calificación
$maximoCalif	= $sRowConf[strtolower('valorConfiguracion')];
$IDItemImagen	= $anioLectivo.str_pad($idImagen,6,'0',STR_PAD_LEFT);

#Revision de Maximo Total de Calificacion por Usuario
$bValidaMaxCalif	= fndb_validarTotalCalificacionesbyUsuario($idUsuario);
if ($bValidaMaxCalif == true) {#Supera el máximo permitido de calificacion del usuario
	$notAccess = true;
	$IDItemImagen	= $anioLectivo.str_pad(0,6,'0',STR_PAD_LEFT);
	$lblMensaje	= '<div id="panelMensaje" class="alert alert-info">
	<b>Lo sentimos, ha superado el máximo de calificaciones permitidas.</b>
	</div>';
	$_SESSION['lblMensaje'] = $lblMensaje;
}

if(!isset($idImagen)){
	$notItem = true;
	$lblMensaje	= '<div id="panelMensaje" class="alert alert-info">
	<b>No hay imagenes para realizar calificación.</b>
	</div>';
	$_SESSION['lblMensaje'] = $lblMensaje;
}

$idPerfil = $_SESSION[_NameSession_idPerfil];
if ($idPerfil != 4) {#Coordinador
	$notAccess = true;
	$lblMensaje	= '<div id="panelMensaje" class="alert alert-danger">
	<b>Lo sentimos, su perfil no tiene acceso a esta opción.</b>
	</div>';
	$_SESSION['lblMensaje'] = $lblMensaje;
}

#============ VALIDACIONES GENERALES ==============




#======== ASIGNACION DE IMAGEN ========
#1. Revisar si tiene imagen asignada, si tiene entonces la eliminamos
#2. Asignar la imagen nueva
#3. Cuando se califica, se desasigna la imagen (no debe existir mas de un registro en la tabla de asignaciones)

if (isset($idImagen) && isset($anioLectivo)) {
	
	$existeAsig	= fndb_existeAsignacionImagenbyUsuario($idUsuario);#, $idImagen, $anioLectivo);
	
	if ($existeAsig != 0){
		#Existe asignacion previa, eliminar.
		fndb_desasignarImagenbyUsuario($idUsuario);#, $idImagen, $anioLectivo);	
		#Asignar la nueva imagen al usuario
		fndb_asignarImagen($idUsuario, $idImagen, $anioLectivo);	
	}else{
		#Asignar imagen al usuario
		fndb_asignarImagen($idUsuario, $idImagen, $anioLectivo);	
	}

}
#======== ASIGNACION DE IMAGEN ========
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once(JPATH_BASE.DS."mod.includes/metaHeader.php"); ?>
<link href="css/etalage.zoomImage.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.etalage.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){  
        $('#zoomImage').etalage({
			thumb_image_width: 400,
			thumb_image_height: 300,
			source_image_width: 700,
			source_image_height: 700,
			zoom_area_width: 680,
			zoom_area_height: 300,
			magnifier_invert: true,
			hide_cursor: false,
			icon_offset: 0,
			speed: 400
		});  
		
		$(".close").click(function(event) {
			$(this).parent().fadeTo(300,0,function(){
				  $(this).remove();
			});
		});
			
		$('#calificarItem').click(function(){
			var nCalificacion = $('input:radio[name=rbCalificar]:checked').val();
				if (typeof nCalificacion == 'undefined'){
					$("#IDMsg").html('<div id="panelMensaje" class="alert alert-warning"><b>Debe seleccionar una calificación.</b></div>');
				}else{
					$('#form1').submit();
					$('#form1').reset();					
				}
			return false;
		});
		
    });  
</script>
</head>

<body>
<?php
include_once(JPATH_BASE.DS.'mod.includes/headerBar.php');
?>
<div id="content">
<div class="row">
    <div class="container">
    	
		<div class="column100">
    		<div class="widget">
                <div class="widget_header">
                    <i class="icon-widget"></i><h3>Calificar Pregunta <?php echo $IDItemImagen; ?></h3>
                </div>
            	<div class="widget_content">
               		<form id="form1" name="form1" method="post" action="" >
                        <input name="Submit" type="hidden" id="Submit" value="1" />
                        <input name="idImagen" type="hidden" id="idImagen" value="<?php echo $idImagen; ?>" />
                        <input name="anioLectivo" type="hidden" id="anioLectivo" value="<?php echo $anioLectivo; ?>" />
                        <input name="fechaInicio" type="hidden" id="fechaInicio" value="<?php echo $fechaInicio; ?>" />
                        
                        <div id="infoRubrica">
                        	<ul class="list_general right">
                            <?php if ($notItem == false): ?>
                            	<li><a href="modalRubrica.php?id=<?php echo $IDItem; ?>" class="openModalRub"><i class="icon-right fa-info-circle"></i>Rubrica</a></li>
                            <?php endif; ?>
                            </ul>
                        </div>
                        
                        <div class="divContentMsg" id="IDMsg">
                        	<?php 
							if ( isset($_SESSION['lblMensaje']) ){
								echo $_SESSION['lblMensaje'];
								unset($_SESSION['lblMensaje']);
							}
							?>
                        </div>
						
                        <?php if ($notItem == false && $notAccess == false): ?>
                        <div id="imagenCalificar">
                            <ul id="zoomImage">
                                <li>
                                    <img class="etalage_thumb_image" src="<?php echo $srcImagen; ?>" alt="" />
                                    <img class="etalage_source_image" src="<?php echo $srcImagen; ?>" alt="" />
                                </li>
                            </ul>
                        </div>
                        <div id="infoCalificar">
                            <h4>Información General</h4>
                            <ul>
                                <li><b>Centro Educativo:</b> <?php echo $nomCentro; ?></li>
                                <li><b>Categoria:</b> <?php echo $codCategoria.'-'.$nomCategoria; ?></li>
                                <li><b>Grado:</b> <?php echo $codGrado.'-'.$nomGrado; ?></li>
                            </ul>
                            <h4>Calificación</h4>
                            <?php
								#if ($bFlagAnclaje != true){
								#Calificacion Imagen Ilegible
								echo '<div class="radio_inline">';
								echo '<input name="rbCalificar" type="radio" id="rbi" value="-1"/><label>Mal Escaneada</label>';
								echo '</div>';	
								#}
								for ($i=0; $i<=$maximoCalif; $i++){
									echo '<div class="radio_inline">';
									echo '<input name="rbCalificar" type="radio" id="rb'.$i.'" value="'.$i.'"/><label>'.$i.'</label>';
									echo '</div>';	
								}
							?>
                            <div class="clear"></div>
                            <h4>Justificación</h4>
                            <textarea rows="8" cols="50" id="rMemoScore" name="rMemoScore"></textarea>
                            <div class="btn_actions">
								<a class="btn btn_succes" href="javascript:;" id="calificarItem"><i class="icon-fa fa-eye"></i>Calificar</a>	
        					</div> <!-- login_actions -->
						</div> 
                        <?php endif; ?>                        
					</form>
				</div><!-- widget_content -->
                </div><!-- widget -->
                
            </div><!-- column100 -->
	</div><!-- container -->
</div><!-- row -->
</div><!-- content -->

<?php include_once(JPATH_BASE.DS."mod.includes/footerBar.php"); ?>
</body>
</html>