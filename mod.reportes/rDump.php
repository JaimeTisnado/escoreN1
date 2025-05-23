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

if ($Submit == 1){
	
$sSql = fn_EjecutarQuery("
select c.nomCategoria, c.codigoCategoria, g.nomGrado, g.codigoGrado,
i.nomItem, i.codigoItem, im.nomImagen, si.calificacion, si.memoscore, im.calificacionFinal, u.nomUsuario,
d.nomDepartamento, d.codigoDepartamento, m.nomMunicipio, m.codigoMunicipio,
ct.nomCentro, ct.codigoCentro, im.anioLectivo||''||lpad(cast(im.idImagen as char(6)),6,'0') idImagen
from scoreitems si
inner join imagenesitems im
on im.idImagen = si.idImagen
and im.anioLectivo = im.anioLectivo
and im.flagRevisado = 1 
inner join items i
on i.idItem = im.idItem
inner join categorias c
on c.idCategoria = i.idCategoria
inner join grados g
on g.idGrado = i.idGrado
inner join centroseducativos ct
on ct.idCentroEducativo = im.idCentroEducativo
inner join municipios m
on m.idMunicipio = ct.idMunicipio
inner join departamentos d
on d.idDepartamento = m.idDepartamento
inner join usuarios u
on u.idUsuario = si.idUsuario
where i.isActivo = 1
order by si.idImagen, si.fechaCalificacion asc;
");

	$filename	= "dump.csv";
	header('Content-Type: text/csv;charset=UTF-8' );
	header('Content-Disposition: attachment;filename='.$filename);
	header('Pragma: no-cache');
	header('Expires: 0');

	#Crear/Sobrescribir archivo CSV
	$fp = fopen("php://output", "w");#fopen($filename, 'w');
  
	#Escribir cada linea del CSV
	$nRows = fn_NumeroRegistros($sSql);
	$nAcum = 0;
	while($row = fn_ExtraerQuery($sSql)) {
		if ($nAcum == 0){
		  #Escribir el encabezado/titulos
		   fputcsv($fp, array_keys($row));
		}
		fputcsv($fp, $row);
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
                    <i class="icon-widget"></i><h3>Generar CSV Calificaciones de Items</h3>
                </div>
            	<div class="widget_content">
                	<form id="form1" name="form1" method="post" action="" >
                        <input name="Submit" type="hidden" id="Submit" value="1" />                             
                  
                          <div class="btn_actions">
                            <a class="btn btn_primary" href="javascript:;" id="btnGenerar"><i class="icon-fa fa-gear"></i>Generar Archivo</a>	
                          </div> <!-- login_actions -->
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