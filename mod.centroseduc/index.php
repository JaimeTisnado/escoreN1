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
$txtCodigo			= $_GET['txtCodigo'];
$cmbEstado			= $_GET['cmbEstado'];

$numRegistros 		= _numRegistros;
if (!isset($ID)) $ID = 0;

if (!isset($_GET['pag'] )) //  numero de pagina 
		$pag = 1;
else $pag = $_GET['pag'];

if (!isset($cmbEstado)) $cmbEstado = "%";
if (isset($cmbEstado)) $cmbEstadoFilter = "$cmbEstado";
if (($cmbEstado) == "%") $cmbEstadoFilter = "0,1";

$URL = "?txtNombre=$txtNombre&txtCodigo=$txtCodigo&cmbEstado=$cmbEstado&Submit=$Submit"; // Nombre de esta Pagina

$sSql = ("
select p.idPais, p.nomPais, d.idDepartamento, d.nomDepartamento, m.idMunicipio, m.nomMunicipio, c.idCentroEducativo,
c.nomCentro, c.codigoCentro, c.isActivo
from centroseducativos c
inner join municipios m
	on m.idMunicipio = c.idMunicipio
inner join departamentos d
	on d.idDepartamento = m.idDepartamento
inner join paises p
	on p.idPais = d.idPais
where lower(c.nomCentro) like lower('%$txtNombre%')
and lower(c.codigoCentro) like lower('%$txtCodigo%')
and c.isActivo in ($cmbEstadoFilter)
order by c.nomCentro
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
$sDescr = NULL;
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
            <i class="icon-widget fa-list"></i><h3>Listado de Centros Educativos</h3>
        </div>
        <div class="widget_content">
        
        	<div id="PanelDetalle">
             <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td></td>
                </tr>
                <tr>
                  <td class="TDTitle">Busqueda<a id="btnNuevo" href="mant_Centro.php" class="btn right"><i class="icon-fa fa-file-o"></i>Agregar Centro Educativo</a>
                  </td>
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
                        <td class="row_form">Código:</td>
                        <td><input name="txtCodigo" type="text" id="txtCodigo" value="<?php echo $txtCodigo; ?>" size="40" maxlength="40" /></td>
                      </tr>
                      <tr>
                        <td class="row_form">Estado:</td>
                        <td><label for="cmbEstado"></label>
                          <select name="cmbEstado" id="cmbEstado">
                            <option value="%" <?php if (!(strcmp("%", $cmbEstado))) {echo "selected=\"selected\"";} ?>>-Cualquier Estado-</option>
                            <option value="1" <?php if (!(strcmp(1, $cmbEstado))) {echo "selected=\"selected\"";} ?>>Activo</option>
                            <option value="0" <?php if (!(strcmp(0, $cmbEstado))) {echo "selected=\"selected\"";} ?>>Inactivo</option>
                          </select></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><input name="Submit" type="hidden" id="Submit" value="1" /></td>
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
                        <td>Código</td>
                        <td>Estado</td>
                      </tr>
                      </thead>
                    <?php
						while ($sRow = fn_ExtraerQuery($sQL))
						{
							$idCentroEducativo	= $sRow[strtolower('idCentroEducativo')];
							$nomCentro			= $sRow[strtolower('nomCentro')];
							$codigoCentro		= $sRow[strtolower('codigoCentro')];
							$isActivo			= $sRow[strtolower('isActivo')];
												
							if ( $isActivo == 1 ) {
								$imgActivo = "<img src='".JPATH_BASE_WEB.DSW."imagenes/_publish_16.png' title='Activo' />";
							}
							else {
								$imgActivo = "<img src='".JPATH_BASE_WEB.DSW."imagenes/_nopublish_16.png' title='Inactivo' />";
							}
							
							$hrefEdit 	= "mant_Centro.php?id=".$idCentroEducativo;
							
						?>
                    <tr>
                      <td><a href="<?php echo $hrefEdit; ?>" title="Editar Centro Educativo"><?php echo $nomCentro ?></a></td>
                      <td><?php echo $codigoCentro; ?></td>
                      <td class="alinearCentro"><?php echo $imgActivo ?></td>
                    </tr>
                    <?php
						}
						?>
                    <tr>
                      <td>&nbsp;</td>
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