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
// Busqueda
$Submit				= $_GET['Submit'];
$txtNombre			= $_GET['txtNombre'];
$cmbEstado			= $_GET['cmbEstado'];

$numRegistros 		= _numRegistros;

if (!isset($_GET['pag'] )) //  numero de pagina 
		$pag = 1;
else $pag = $_GET['pag'];

if (!isset($cmbEstado)) $cmbEstado = "%";
if (isset($cmbEstado)) $cmbEstadoFilter = "$cmbEstado";
if (($cmbEstado) == "%") $cmbEstadoFilter = "0,1";

$URL = "?txtNombre=$txtNombre&cmbEstado=$cmbEstado&Submit=$Submit"; // Nombre de esta Pagina

$sSql = ("
select a.idConfiguracion, a.nomConfiguracion, a.valorConfiguracion
from configuraciones a
where lower(a.nomConfiguracion) like lower('%$txtNombre%')
order by a.nomConfiguracion
") ;

$inicio = $numRegistros * ($pag-1); 
$final  = $inicio + ($numRegistros - 1);
$pTimeInicial 	= microtime(true);

$resultado = fn_EjecutarQuery($sSql);
$pTimeFinal 	= microtime(true);
$pTimeConsulta	= ($pTimeFinal - $pTimeInicial);

$num_rows = fn_NumeroRegistros($resultado);
$num_pags = ceil($num_rows / $numRegistros);

/* Descripcion de ROW */
$sRow	= fn_ExtraerQuery($resultado);
$sDescr = $sRow[strtolower('nomPais')];
/* ------ */

$sSql .= " limit $numRegistros offset $inicio";
$resultado = fn_EjecutarQuery($sSql);

$sQL = fn_EjecutarQuery($sSql);
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
            <i class="icon-widget fa-list"></i>
            <h3>Listado de Configuraciones</h3>
        </div>
        <div class="widget_content">
        
        	<div id="PanelDetalle">
             <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="TDTitle">Busqueda</td>
                </tr>
                <tr>
                  <td><form id="form1" name="form1" method="get" action="">
                    <table width="400" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="100">&nbsp;</td>
                        <td width="300">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="row_form">Nombre:</td>
                        <td><label for="txtMonto">
                          <input name="txtNombre" type="text" id="txtNombre" value="<?php echo $txtNombre; ?>" size="40" maxlength="40" />
                        </label></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><input name="Submit" type="hidden" id="Submit" value="1" />
                          <input name="id" type="hidden" id="id" value="<?php echo $ID ?>" /></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><a href="javascript:;" id="frmSubmit" class="btn btn_primary"><i class="icon-fa fa-search"></i>Buscar</a></td>
                      </tr>
                     </table>
                  </form></td>
                </tr>
                <tr>
                  <td><table cellpadding="0" cellspacing="1" class="adminList">
                  	  <thead>
                      <tr class="Titulos">
                        <td>Nombre</td>
                        <td>Valor</td>
                      </tr>
                      </thead>
                    <?php
						while ($sRow = fn_ExtraerQuery($sQL))
						{
							$idConfiguracion	= $sRow[strtolower('idConfiguracion')];
							$nomConfiguracion	= $sRow[strtolower('nomConfiguracion')];
							$valorConfiguracion	= $sRow[strtolower('valorConfiguracion')];
												
							$hrefEdit 	= "mant_Conf.php?id=".$idConfiguracion;
							
						?>
                    <tr>
                      <td><a href="<?php echo $hrefEdit; ?>" title="Editar Configuración"><?php echo $nomConfiguracion ?></a></td>
                      <td class="alinearCentro"><?php echo $valorConfiguracion ?></td>
                      </tr>
                    <?php
						}
						?>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td><?php fn_mostrarPaginacion($URL, $num_pags, $pag, $num_rows); ?></td>
                </tr>
               </table>
              </div><!-- PanelDetalle -->
                
        </div><!-- content -->
    </div><!-- widget -->
            

   	          
        </div> <!-- column100 -->
    </div> <!-- container -->
</div> <!-- row -->
</div> <!-- content -->

<?php include_once(JPATH_BASE.DS."mod.includes/footerBar.php"); ?>
</body>
</html>