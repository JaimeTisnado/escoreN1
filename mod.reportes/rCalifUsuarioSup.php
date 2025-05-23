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
$txtFechaIni		= $_POST['txtFechaIni'];
$txtFechaFin		= $_POST['txtFechaFin'];
$idUsuario			= $_SESSION[_NameSession_idUser];

// Obtener el Item del Usuario para el filtro de la busqueda
if(isset($idUsuario)){
fndb_getUsuariobyId($idUsuario);
}
$idItem = $sArray[strtolower('idItem')];

$sSql = fn_EjecutarQuery("
select u.idUsuario, u.nickUsuario, u.nomUsuario
from usuarios u
inner join items i
on i.idItem = u.idItem
where u.idUsuario in (
select si.idUsuario 
from scoreitems si
where si.idUsuario = u.idUsuario
and to_char(si.fechaCalificacion, 'YYYY-MM-DD') between '$txtFechaIni' and '$txtFechaFin'
)
and i.idItem = $idItem
and i.isActivo = 1
and u.idUsuario <> $idUsuario
order by 1 desc;
");

#$txtFechaIni = date("Y-m-d");
#$txtFechaFin = date("Y-m-d");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once(JPATH_BASE.DS."mod.includes/metaHeader.php"); ?>

<script src="<?php echo JPATH_BASE_WEB.DSW; ?>js/jquery.jqprint-0.3.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){ 
		$("#txtFechaIni").datepicker({
			changeMonth: false,
			changeYear: false,
			maxDate: '+1y',
			dateFormat: 'yy-mm-dd',
			onSelect: function(selectedDate) {
				var d = $.datepicker.parseDate('yy-mm-dd', selectedDate);
				d.setDate(d.getDate() + 0); // agregar 1 dia
				var fd = $('#txtFechaFin').val();
				
				if (fd.length == 0) {
					$('#txtFechaFin').val($('#txtFechaIni').val());
				}
				
				if (d < fd) {
					$('#txtFechaFin').datepicker('setDate', d);
				}
				$("#txtFechaFin").datepicker("option", "minDate", d);
	        } // fin  onSelect	
		});
		
		$("#txtFechaFin").datepicker({
			changeMonth: false,
			changeYear: false,
			minDate: $('#txtFechaIni').val(),
			dateFormat: 'yy-mm-dd',
			maxDate: '+1y',
			onSelect: function(selectedDate) {
				$("#txtFechaIni").datepicker("option", "maxDate", selectedDate);
			}
		});
	
	 	$('#frmReset').click(function() {
			$('#txtFechaIni').val("");
			$('#txtFechaFin').val("");
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
                    <h3>Reporte de Calificaciones por Usuario (Supervisor)</h3>
                </div>
            	<div class="widget_content">
                	
                    <form id="form1" name="form1" method="post" action="">
                    <table width="400" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="100">&nbsp;</td>
                        <td width="300">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="row_form">Fecha Inicio:</td>
                        <td><input name="txtFechaIni" type="text" class="inpFecha" id="txtFechaIni" value="<?php  echo $txtFechaIni ?>" size="10" readonly="readonly" /></td>
                      </tr>
                      <tr>
                        <td class="row_form">Fecha Final:</td>
                        <td><input name="txtFechaFin" type="text" class="inpFecha" id="txtFechaFin" value="<?php  echo $txtFechaFin ?>" size="10" readonly="readonly" /></td>
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
               		<h3 class="hide">Reporte de Calificaciones por Usuario (Supervisor)</h3>
                    <table cellpadding="0" cellspacing="1" class="adminList">
                      <tr class="Titulos">
                      	<td>Usuario</td>
                        <td>Nombre del Usuario</td>
                       </tr>
                    <?php
						while ($sRow = fn_ExtraerQuery($sSql))
						{
							$idUsuario			= $sRow[strtolower('idUsuario')];
							$nickUsuario		= $sRow[strtolower('nickUsuario')];
							$nomUsuario			= $sRow[strtolower('nomUsuario')];
							
						?>
                    <tr>
                      <td><?php echo $nickUsuario; ?></td>
                      <td><?php echo $nomUsuario; ?></td>
                    </tr>
                   
                    <tr>
                    	<td colspan="2">
                    	<table cellpadding="0" cellspacing="1" class="info_adminList">
                         <?php
						$sSqlFecha = fn_EjecutarQuery("
						select 
						to_char(si.fechaCalificacion, 'YYYY-MM-DD') fecha
						from scoreitems si
						where si.idUsuario = $idUsuario
						and to_char(si.fechaCalificacion, 'YYYY-MM-DD') between '$txtFechaIni' and '$txtFechaFin' 
						group by to_char(si.fechaCalificacion, 'YYYY-MM-DD')
						order by 1 asc;
						");
						
						$totalAcum = 0;
						while ($sRowFecha = fn_ExtraerQuery($sSqlFecha))
						{
							$fecha	= $sRowFecha[strtolower('fecha')];
					?>
                        	<tr>
                            	<td colspan="3"><?php echo $fecha; ?></td>
                            </tr>
                            <?php
								$sSqlInfo = fn_EjecutarQuery("
select i.idItem, i.nomItem, count(*) calificaciones, (avg(si.fechaCalificacion - si.fechaIniCalificacion)) promedio
from scoreitems si
inner join imagenesitems im
on im.idImagen = si.idImagen
inner join items i
on i.idItem = im.idItem
and im.anioLectivo = si.anioLectivo
where si.idUsuario = $idUsuario
and to_char(si.fechaCalificacion, 'YYYY-MM-DD') = '$fecha'
group by i.idItem, i.nomItem
order by 1 asc;
");
							$totalCalif = 0;
							while ($sRowInfo = fn_ExtraerQuery($sSqlInfo))
							{
								$idItem			= $sRowInfo[strtolower('idItem')];
								$nomItem		= $sRowInfo[strtolower('nomItem')];
								$calificaciones	= fndb_obtenerTotalCalificacionesbyUsuarioItemFecha($idUsuario,$idItem,$fecha); #$sRowInfo[strtolower('calificaciones')];
								$promedio		= $sRowInfo[strtolower('promedio')];
								$totalCalif		= $totalCalif + $calificaciones;
								
							?>
                            <tr>
								<td class="">&nbsp;</td>
                                <td class="width50"><?php echo $nomItem; ?></td>
                                <td class="width40"><?php echo number_format($calificaciones,0); ?></td>
                            </tr>
                            <?php
								}//fin info
								$totalAcum		= $totalAcum + $totalCalif;
							?>
                            <tr>
								<td>&nbsp;</td>
                                <td class="Negrita">Total <?php echo $fecha; ?></td>
                                <td class="Negrita"><?php echo number_format($totalCalif,0); ?></td>
                            </tr>
                            <?php
								} //fin Fecha
								
							?>
                            <tr>
                            	<td colspan="2" class="Negrita">Total de Calificaciones</td>
                                <td class="Negrita"><?php echo number_format($totalAcum,0); ?></td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    <?php
						} //fin Usuarios
					?>
                    <tr>
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