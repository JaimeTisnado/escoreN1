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

$sSql = fn_EjecutarQuery("
select coalesce(vr.revisados,0) revisados, vi.totalItems, c.nomCategoria categoria, g.nomGrado grado, i.nomItem item
from items i
inner join categorias c
on c.idCategoria = i.idCategoria
inner join grados g
on g.idGrado = i.idGrado
left join (
select count(*) revisados, im.idItem
from imagenesitems im
where im.flagRevisado = 1
group by im.idItem
) vr on vr.idItem = i.idItem
inner join (
select count(*) totalItems, im.idItem
from imagenesitems im
group by im.idItem
)vi on vi.idItem = i.idItem
where i.isActivo = 1
order by i.nomItem asc;
");


if ($Submit == 1){
	
	$filename	= "AvanceItems.csv";
	header('Content-Type: text/csv;charset=UTF-8' );
	header('Content-Disposition: attachment;filename='.$filename);
	header('Pragma: no-cache');
	header('Expires: 0');

	#Crear/Sobrescribir archivo CSV
	$fp = fopen("php://output", "w");#fopen($filename, 'w');
  
	#Escribir cada linea del CSV
	$nRows = fn_NumeroRegistros($sSql);
	$nAcum = 0;
	while($sRow = fn_ExtraerQuery($sSql)) {
		$revisados		= number_format($sRow[strtolower('revisados')],0);
		$totalItems		= number_format($sRow[strtolower('totalItems')],0);
		$categoria		= $sRow[strtolower('categoria')];
		$grado			= $sRow[strtolower('grado')];
		$item			= $sRow[strtolower('item')];	
		$porcAvance		= number_format(($revisados / $totalItems) *100,2).'%';
							
		$Array = array("Item"=>$item,"Categoria"=>$categoria,"Grado"=>$grado,
					   "Total Items"=>$totalItems,"Total Revisados"=>$revisados,"Porc. Avance"=>$porcAvance);
					   
		if ($nAcum == 0){
		  #Escribir el encabezado/titulos
		   fputcsv($fp, array_keys($Array));
		}
		fputcsv($fp, $Array);
		$nAcum++;
	}
  
	fclose($fp);
  	exit();
		
}#Submit
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once(JPATH_BASE.DS."mod.includes/metaHeader.php"); ?>

<script src="<?php echo JPATH_BASE_WEB.DSW; ?>js/jquery.jqprint-0.3.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){  
		$('#btnImprimir').click(function(event) {
			$('#areaPint').jqprint();
		});
       	
		$('#btnGenerar').click(function(event) {
			$('#form1').submit();
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
                    <i class="icon-widget"></i><h3>Reporte de Avance de Revisiones por Item</h3>
                </div>
            	<div class="widget_content">
                	
                    <div id="areaPint">
               		<h3 class="hide">Reporte de Avance de Revisiones por Item</h3>
                    <table cellpadding="0" cellspacing="1" class="adminList">
                      <tr class="Titulos">
                      	<td>Item</td>
                        <td>Categoria</td>
                        <td>Grado</td>
                        <td>Total Items</td>
                        <td>Total Revisados</td>
                        <td>Porc. Avance</td>
                      </tr>
                    <?php
						while ($sRow = fn_ExtraerQuery($sSql))
						{
							$revisados		= $sRow[strtolower('revisados')];
							$totalItems		= $sRow[strtolower('totalItems')];
							$categoria		= $sRow[strtolower('categoria')];
							$grado			= $sRow[strtolower('grado')];
							$item			= $sRow[strtolower('item')];	
							$porcAvance		= ($revisados / $totalItems) *100;	
							
							$sumRevisados	= $sumRevisados + $revisados;
							$sumTotalItems	= $sumTotalItems + $totalItems;	
							$porcAvanceTotal= ($sumRevisados / $sumTotalItems) *100;				
						?>
                    <tr>
                      <td><?php echo $item; ?></td>
                      <td><?php echo $categoria; ?></td>
                      <td><?php echo $grado; ?></td>
                      <td><?php echo number_format($totalItems,0); ?></td>
                      <td><?php echo number_format($revisados,0); ?></td>
                      <td><?php echo number_format($porcAvance,2).' %'; ?></td>
                    </tr>
                    <?php
						}
						?>
                    <tr>
                      <td colspan="3">&nbsp;</td>
                      <td><?php echo number_format($sumTotalItems,0); ?></td>
                      <td><?php echo number_format($sumRevisados,0); ?></td>
                      <td><?php echo number_format($porcAvanceTotal,2).' %'; ?></td>
                    </tr>
                  </table>
                  </div>
                  
                  	<div class="btn_actions">
						<form id="form1" name="form1" method="post" action="" >
                            <input name="Submit" type="hidden" id="Submit" value="1" />                             
                                <a class="btn btn_info" href="javascript:;" id="btnImprimir"><i class="icon-fa fa-print"></i>Imprimir</a>
                                <a class="btn btn_primary" href="javascript:;" id="btnGenerar"><i class="icon-fa fa-gear"></i>Generar Archivo</a>	
                        </form>         
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