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
$txtFecha			= $_POST['txtFecha'];

$sSql = fn_EjecutarQuery("
select u.idUsuario, u.nomUsuario, p.nomPerfil, u.idItem, i.nomItem, count(ia.idImagen) cantidad
from usuarios u
inner join perfiles p
	on p.idPerfil = u.idPerfil
inner join imagenesasignadas ia
	on ia.idUsuario = u.idUsuario
inner join items i
	on i.idItem = u.idItem
	and i.isActivo = 1
group by u.idUsuario, u.nomUsuario, p.nomPerfil, u.idItem, i.nomItem
order by i.nomItem, u.nomUsuario
");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once(JPATH_BASE.DS."mod.includes/metaHeader.php"); ?>

<script src="<?php echo JPATH_BASE_WEB.DSW; ?>js/jquery.jqprint-0.3.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){ 
		$( "#txtFecha" ).datepicker({
			changeMonth: false,
			changeYear: false,
			maxDate: '+1y',
			dateFormat: 'yy-mm-dd',
		});
		
	 	$('#frmReset').click(function() {
			$('#txtFecha').val("");
			$('#form1').submit();
		});
	
		$('#btnImprimir').click(function(event) {
			$('#areaPint').jqprint();
		});
       		
    });  
</script>
<script language="javascript">
function limpiar(idUsuario){    
		
		if (confirm("¿Seguro desea limpiar las asignaciones de este usuario?")) {
			$.post("updDesasignar.php", { idUsuario:idUsuario }); 
		}
		setTimeout("location.reload(true);", 1000);
	//window.location.reload();     
}

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
                    <i class="icon-widget"></i>
                    <h3>Reporte de Items Pendientes de Calificar por Usuario</h3>
                </div>
            	<div class="widget_content">
            	  <div id="areaPint">
               		<h3 class="hide">Reporte de Items Pendientes de Calificar por Usuario</h3>
                    <table cellpadding="0" cellspacing="1" class="adminList">
                      <tr class="Titulos">
                      	<td>Nombre del Usuario</td>
                        <td>Perfil</td>
                        <td>Item Actual</td>
                        <td>Pendientes</td>
                        <td></td>
                      </tr>
                    <?php
						while ($sRow = fn_ExtraerQuery($sSql))
						{
							$idUsuario		= $sRow[strtolower('idUsuario')];
							$nomUsuario		= $sRow[strtolower('nomUsuario')];
							$nomPerfil		= $sRow[strtolower('nomPerfil')];
							$idItem			= $sRow[strtolower('idItem')];		
							$nomItem		= $sRow[strtolower('nomItem')];	
							$cantidad		= $sRow[strtolower('cantidad')];	
							
							$imgDelete 	= '<a href="#" onclick="javascript:limpiar('.$idUsuario.')" title="Limpiar Asignaciones<p>Elimina asignaciones pendientes calificar</p>">';
							$imgDelete .= "<img src='".JPATH_BASE_WEB.DSW."imagenes/_delete_16.png' border=0 /></a>";
							
						?>
                    <tr>
                      <td><?php echo $nomUsuario; ?></td>
                      <td><?php echo $nomPerfil; ?></td>
                      <td><?php echo $nomItem; ?></td>
                      <td><?php echo number_format($cantidad,0); ?></td>
                      <td class="alinearCentro"><?php echo $imgDelete ?></td>
                    </tr>
                    <?php
						}
					?>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
                  </div>
                  
                  <div class="btn_actions">
					<a class="btn btn_info" href="javascript:;" id="btnImprimir"><i class="icon-fa fa-print"></i>Imprimir</a>	
				  </div> <!-- login_actions -->
                            
				</div><!-- widget_content -->
                </div><!-- widget -->
                
            </div><!-- column100 -->
	</div><!-- container -->
</div><!-- row -->
</div><!-- content -->

<?php include_once(JPATH_BASE.DS."mod.includes/footerBar.php"); ?>
</body>
</html>