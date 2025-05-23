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
$idPerfil = $_SESSION[_NameSession_idPerfil];

switch($idPerfil){
	case 1:
		$urlCalificar = JPATH_BASE_WEB.DSW.'revisar.php';
		$urlChartItem = "barItemsDts.php";
	break;
	case 2:
		$urlCalificar = JPATH_BASE_WEB.DSW.'revisar.php';
		$urlChartItem = "barItemsDtsUsers.php";
	break;	
	case 3:
		$urlCalificar = JPATH_BASE_WEB.DSW.'revisarSup.php';
		$urlChartItem = "barItemsDtsUsers.php";
	break;	
	case 4:
		$urlCalificar = JPATH_BASE_WEB.DSW.'revisarCoord.php';
		$urlChartItem = "barItemsDtsUsers.php";
	break;
	default:
		$urlCalificar = 'javascript:;';
	break;		
}

#echo date('d/m/Y H:i:s',time()).'<br/>';
#echo date('d/m/Y H:i:s',$_SESSION[_NameSession_expire]);
?>
<?php
#======== ASIGNACION DE IMAGEN ========
#1. Revisar si tiene imagen asignada y no califico, si tiene entonces la eliminamos
$idUsuario	= $_SESSION[_NameSession_idUser];
$existeAsig	= fndb_existeAsignacionImagenbyUsuario($idUsuario);#, $idImagen, $anioLectivo);
if ($existeAsig != 0){ #existen asignaciones
	#Existe asignacion previa, eliminar.
	fndb_desasignarImagenbyUsuario($idUsuario);#, $idImagen, $anioLectivo);	
}

#======== ASIGNACION DE IMAGEN ========
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

<div class="column50">
    
    <div class="widget">
    	<div class="widget_header">
            <i class="icon-widget fa-signal"></i><h3>Graficas</h3>
        </div>
        <div class="widget_content">
        	<center>
        	<h4>% Avance de Calificaciones Global</h4>
            <img src="barItemsGlobal.php" alt="" />
            <h4>% Avance Calificaciones por Item</h4>
            <img src="<?php echo $urlChartItem; ?>" alt="" />
            </center>
        </div>
    </div><!-- widget -->
      
    
</div><!-- column50 -->

<div class="column50">
	
    <div class="widget">
	<div class="widget_header">
        <i class="icon-widget fa-list-alt"></i><h3>Accesos Directos</h3>
    </div>
    <div class="widget_content">
    	<div class="shortcuts">
        	<a class="shortcut" href="<?php echo JPATH_BASE_WEB.DSW;?>mod.usuarios/perfil_Usuario.php">
                <i class="icon-shortcut fa-shield"></i>
                <span class="shortcut-label">Mi Perfil</span>
            </a>
    		<a class="shortcut" href="<?php echo JPATH_BASE_WEB.DSW;?>mod.mensajes/">
                <i class="icon-shortcut fa-envelope"></i>
                <span class="shortcut-label">Mis Mensajes</span>
            </a>
            <a class="shortcut" href="<?php echo $urlCalificar;?>">
                <i class="icon-shortcut fa-eye"></i>
                <span class="shortcut-label">Calificar</span>
            </a>
            <!--<a class="shortcut" href="javascript:;">
                <i class="icon-shortcut fa-bar-chart-o"></i>
                <span class="shortcut-label">Reportes</span>
            </a>-->
    		
        </div><!-- shortcuts -->
    </div><!-- widget_content -->
	</div><!-- widget -->   
    
    <div class="widget">
	<div class="widget_header">
        <i class="icon-widget fa-list-alt"></i><h3>Resumen de Calificaciones</h3>
    </div>
    <div class="widget_content">
    	<table cellpadding="0" cellspacing="1" class="adminList">
                      <tr class="Titulos">
                      	<td>Nombre del Item</td>
                        <td>Total de Calificaciones</td>
                        <td>Máximo de Calificaciones</td>
                      </tr>
                    <?php
						$idUsuario = $_SESSION[_NameSession_idUser];
						$sSql = fndb_getReporteCalificacionesUsuario($idUsuario);
//esta linea he agregado
$totalCalif = 0;
						while ($sRow = fn_ExtraerQuery($sSql))
						{
							$calificaciones		= $sRow[strtolower('calificaciones')];
							$nomItem			= $sRow[strtolower('nomItem')];
							$maxRevisiones		= $sRow[strtolower('maxRevisiones')];
							if($calificaciones > $maxRevisiones){
								$calificaciones = $maxRevisiones;
							}
							$totalCalif			= ($totalCalif + $calificaciones);
											
						?>
                    <tr>
                      <td><?php echo $nomItem; ?></td>
                      <td><?php echo $calificaciones; ?></td>
                      <td><?php echo $maxRevisiones; ?></td>
                    </tr>
                    <?php
						}
						?>
                    <tr>
                      <td><b>Total</b></td>
                      <td><b><?php echo $totalCalif; ?></b></td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
    </div><!-- widget_content -->
	</div><!-- widget -->  
    
</div><!-- column50 -->



</div><!-- container -->
</div><!-- row -->
</div><!-- content -->

    
<?php include_once(JPATH_BASE.DS."mod.includes/footerBar.php"); ?>

</body>
</html>