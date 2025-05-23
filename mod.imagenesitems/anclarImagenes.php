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

if ($Submit == 1) {

$idUsuario			= $_SESSION[_NameSession_idUser];
$anioLectivo		= $_POST['txtAnio'];
	
	if(isset($anioLectivo)){
		$nResultado	= fndb_anclarImagenesItem($idUsuario, $anioLectivo);
		if ($nResultado == 0){
			$lblMensaje	= '<div id="panelMensaje" class="alert alert-success">
			<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
			<b>Proceso anclaje de doble revisión realizado correctamente.</b>
			</div>';	
		}elseif ($nResultado == 2){
			$lblMensaje	= '<div id="panelMensaje" class="alert alert-info">
			<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
			<b>El Proceso anclaje de doble revisión ya fue realizado para dicho periodo.</b>
			</div>';	
		}else{
			$lblMensaje	= '<div id="panelMensaje" class="alert alert-danger">
			<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
			<b>Se presento un error al realizar el proceso de doble revisión.</b>
			</div>';
		}
		$_SESSION['lblMensaje'] = $lblMensaje;
		header("location: ".$PAGINA);
		exit();
	}
	
}

$sArrayInfo		=  fndb_getDatosReporte2();
$totalImagenes	= $sArrayInfo['totalPreguntas'];

$sRowConf		= fndb_getConfiguracionbyId(2); #Porcentaje Doble Revision
$porcDoble		= number_format($sRowConf[strtolower('valorConfiguracion')],2);

$totalProcesar	= ceil($totalImagenes * ($porcDoble / 100));

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once(JPATH_BASE.DS."mod.includes/metaHeader.php"); ?>
<script type="text/javascript">
    $(document).ready(function(){  
        $(".close").click(function(event) {
			$(this).parent().fadeTo(300,0,function(){
				  $(this).remove();
			});
		});
			
		$('#procesarAnclaje').click(function(){
			var nAnio = $('#txtAnio').val();
				if (nAnio.length == 0){
					$("#IDMsg").html('<div id="panelMensaje" class="alert alert-warning"><b>Debe ingresar el año lectivo a procesar</b></div>');
					$("#txtAnio").focus();
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
                    <i class="icon-widget"></i><h3>Procesar Anclaje Doble Revisión</h3>
                </div>
            	<div class="widget_content">
               		<form id="form1" name="form1" method="post" action="" >
                        <input name="Submit" type="hidden" id="Submit" value="1" />
                                                
                        <div class="divContentMsg" id="IDMsg">
                        	<?php 
							if ( isset($_SESSION['lblMensaje']) ){
								echo $_SESSION['lblMensaje'];
								unset($_SESSION['lblMensaje']);
							}
							?>
                        </div>

                        <div id="infoCalificar">
                            <h4>Información General</h4>
                            <ul>
                                <li><b>Total de Imagenes:</b> <?php echo $sArrayInfo['totalPreguntas']; ?></li>
                                <li><b>Porcentaje Doble Revisión:</b> <?php echo $porcDoble.'%'; ?></li>
                                <li><b>Total de Imagenes a Procesar:</b> <?php echo $totalProcesar; ?></li>
                            </ul>
                            <h4>Año Lectivo</h4>
                            <input name="txtAnio" type="text" id="txtAnio" value="<?php echo $txtAnio; ?>" size="6" maxlength="4" onkeypress="return isCampoEntero(event,this);" />
                            <div class="clear"></div>
                            <div class="btn_actions">
								<a class="btn btn_succes" href="javascript:;" id="procesarAnclaje"><i class="icon-fa fa-gear"></i>Procesar</a>	
        					</div> <!-- login_actions -->
                            
						</div>                          
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