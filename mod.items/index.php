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
$ID					= $_GET['id'];
$txtNombre			= $_GET['txtNombre'];
$cmbCategoria		= $_GET['cmbCategoria'];
$cmbGrado			= $_GET['cmbGrado'];
$cmbEstado			= $_GET['cmbEstado'];

$numRegistros 		= _numRegistros;
if (!isset($ID)) $ID = 0;

if (!isset($_GET['pag'] )) //  numero de pagina 
		$pag = 1;
else $pag = $_GET['pag'];

if (!isset($cmbCategoria)) $cmbCategoria = "%";
if (isset($cmbCategoria)) $cmbCategoriaFilter = "= $cmbCategoria";
if (($cmbCategoria) == "%") $cmbCategoriaFilter = "between 0 and 999";

if (!isset($cmbGrado)) $cmbGrado = "%";
if (isset($cmbGrado)) $cmbGradoFilter = "= $cmbGrado";
if (($cmbGrado) == "%") $cmbGradoFilter = "between 0 and 999";

if (!isset($cmbEstado)) $cmbEstado = "%";
if (isset($cmbEstado)) $cmbEstadoFilter = "$cmbEstado";
if (($cmbEstado) == "%") $cmbEstadoFilter = "0,1";

$URL = "?cmbCategoria=$cmbCategoria&cmbGrado=$cmbGrado&txtNombre=$txtNombre&cmbEstado=$cmbEstado&Submit=$Submit"; // Nombre de esta Pagina

$sSql = ("
select c.idCategoria, c.nomCategoria, g.idGrado, g.nomGrado, i.idItem, i.nomItem, i.codigoItem, i.memoItem,
i.maxRevisiones, i.isActivo
from items i
inner join categorias c
	on c.idCategoria = i.idCategoria
	and c.idCategoria $cmbCategoriaFilter
inner join grados g
	on g.idGrado = i.idGrado
	and g.idGrado $cmbGradoFilter
where lower(i.nomItem) like lower('%$txtNombre%')
and i.isActivo in ($cmbEstadoFilter)
order by i.nomItem
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
            <i class="icon-widget fa-list"></i>
            <h3>Listado de Items (Preguntas)</h3>
        </div>
        <div class="widget_content">
        
        	<div id="PanelDetalle">
             <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td></td>
                </tr>
                <tr>
                  <td class="TDTitle">Busqueda<a id="btnNuevo" href="mant_Item.php" class="btn right"><i class="icon-fa fa-file-o"></i>Agregar Item</a>
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
                        <td class="row_form">Categoria:</td>
                        <td><select name="cmbCategoria" id="cmbCategoria">
                          <option value="%">-Cualquier Categoria-</option>
                          <?php
	  			
						 while ($rOW = fn_ExtraerQuery($sQL_getCategorias))
							{
							 
							 $idCategoria 	= $rOW[strtolower('idCategoria')];
							 $nomCategoria	= $rOW[strtolower('nomCategoria')];
							 							
							 if ($idCategoria == $cmbCategoria ) {
							  	$value = 	"value=$idCategoria selected=\"selected\" ";
							 } else {
								$value = 	"value=$idCategoria"; 
							 }
						?>
                          <option <?php echo $value; ?>><?php echo $nomCategoria;?></option>
                          <?php
                            }
                        ?>
                        </select></td>
                      </tr>
                      <tr>
                        <td class="row_form">Grado:</td>
                        <td><select name="cmbGrado" id="cmbGrado">
                          <option value="%">-Cualquier Grado-</option>
                          <?php
	  			
						 while ($rOW = fn_ExtraerQuery($sQL_getGrados))
							{
							 
							 $idGrado 	= $rOW[strtolower('idGrado')];
							 $nomGrado	= $rOW[strtolower('nomGrado')];
							 							
							 if ($idGrado == $cmbGrado ) {
							  	$value = 	"value=$idGrado selected=\"selected\" ";
							 } else {
								$value = 	"value=$idGrado"; 
							 }
						?>
                          <option <?php echo $value; ?>><?php echo $nomGrado;?></option>
                          <?php
                            }
                        ?>
                        </select></td>
                      </tr>
                      <tr>
                        <td class="row_form">Nombre:</td>
                        <td><label for="txtMonto">
                          <input name="txtNombre" type="text" id="txtNombre" value="<?php echo $txtNombre; ?>" size="40" maxlength="40" />
                        </label></td>
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
                        <td>Código</td>
                        <td>Categoria</td>
                        <td>Grado</td>
                        <td>Máximo Revisiones</td>
                        <td>Estado</td>
                      </tr>
                      </thead>
                    <?php
						while ($sRow = fn_ExtraerQuery($sQL))
						{
							$idItem				= $sRow[strtolower('idItem')];
							$nomItem			= $sRow[strtolower('nomItem')];
							$nomCategoria		= $sRow[strtolower('nomCategoria')];
							$codigoItem			= $sRow[strtolower('codigoItem')];
							$nomGrado			= $sRow[strtolower('nomGrado')];
							$maxRevisiones		= $sRow[strtolower('maxRevisiones')];
							$isActivo			= $sRow[strtolower('isActivo')];
												
							if ( $isActivo == 1 ) {
								$imgActivo = "<img src='".JPATH_BASE_WEB.DSW."imagenes/_publish_16.png' title='Activo' />";
							}
							else {
								$imgActivo = "<img src='".JPATH_BASE_WEB.DSW."imagenes/_nopublish_16.png' title='Inactivo' />";
							}
							
							$hrefEdit 	= "mant_Item.php?id=".$idItem;
							
						?>
                    <tr>
                      <td><a href="<?php echo $hrefEdit; ?>" title="Editar Pregunta"><?php echo $nomItem ?></a></td>
                      <td><?php echo $codigoItem; ?></td>
                      <td><?php echo $nomCategoria; ?></td>
                      <td><?php echo $nomGrado; ?></td>
                      <td><?php echo $maxRevisiones; ?></td>
                      <td class="alinearCentro"><?php echo $imgActivo ?></td>
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