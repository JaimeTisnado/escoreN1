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
select vi.calificaciones, vi.promedio, u.idUsuario, u.nomUsuario, i.nomItem
from usuarios u
inner join items i
on i.idItem = u.idItem
inner join (
select count(*) calificaciones, (avg(si.fechaCalificacion - si.fechaIniCalificacion)) promedio, si.idUsuario, u.nomUsuario
from scoreitems si
inner join imagenesitems im
on im.idImagen = si.idImagen
and im.anioLectivo = im.anioLectivo
inner join usuarios u
on u.idUsuario = si.idUsuario
where to_char(si.fechaCalificacion, 'YYYY-MM-DD') like '%$txtFecha%'
group by si.idUsuario, u.nomUsuario
) vi on vi.idUsuario = u.idUsuario
where i.isActivo = 1
order by 1 desc;
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
                    <h3>Reporte de Calificaciones por Usuario</h3>
                </div>
            	<div class="widget_content">
                	
                    <form id="form1" name="form1" method="post" action="">
                    <table width="400" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="100">&nbsp;</td>
                        <td width="300">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="row_form">Fecha:</td>
                        <td><input name="txtFecha" type="text" class="inpFecha" id="txtFecha" value="<?php  echo $txtFecha ?>" size="10" readonly="readonly" /></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><input name="Submit" type="hidden" id="Submit" value="1" /></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>
                        <a href="javascript:;" id="frmSubmit" class="btn btn_primary"><i class="icon-fa fa-gear"></i>Generar</a>
                        <a href="javascript:;" id="frmReset" class="btn"><i class="icon-fa fa-eraser"></i>Limpiar</a>
                        </td>
                      </tr>
                     </table>
                  </form>
                  
                  <div id="areaPint">
               		<h3 class="hide">Reporte de Calificaciones por Usuario</h3>
                    <table cellpadding="0" cellspacing="1" class="adminList">
                      <tr class="Titulos">
                      	<td>Nombre del Usuario</td>
                        <td>Calificaciones</td>
                        <td>Item Actual</td>
                        <td>Resumen</td>
                      </tr>
                    <?php
						while ($sRow = fn_ExtraerQuery($sSql))
						{
							$calificaciones		= $sRow[strtolower('calificaciones')];
							$promedio			= $sRow[strtolower('promedio')];
							$nomUsuario			= $sRow[strtolower('nomUsuario')];
							$nomItem			= $sRow[strtolower('nomItem')];		
							$idUsuario			= $sRow[strtolower('idUsuario')];	
							
							$resumen			= '';
							$sSqlRes = fndb_getReporteCalificacionesUsuario($idUsuario,$txtFecha);
							while ($sRowRes = fn_ExtraerQuery($sSqlRes))
							{
								$calItemRes		= $sRowRes[strtolower('calificaciones')];
								$nomItemRes		= $sRowRes[strtolower('nomItem')];
								$resumen .= '<p>'.$nomItemRes.': '.$calItemRes.'</p>';
							}
						?>
                    <tr>
                      <td><?php echo $nomUsuario; ?></td>
                      <td><?php echo number_format($calificaciones,0); ?></td>
                      <td><?php echo $nomItem; ?></td>
                      <td><?php echo $resumen; ?></td>
                    </tr>
                    <?php
						}
						?>
                    <tr>
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