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
$cmbPerfil			= $_GET['cmbPerfil'];
$cmbItem			= $_GET['cmbItem'];
$cmbEstado			= $_GET['cmbEstado'];

$numRegistros 		= _numRegistros;

if (!isset($_GET['pag'] )) //  numero de pagina 
		$pag = 1;
else $pag = $_GET['pag'];

if (!isset($cmbPerfil)) $cmbPerfil = "%";
if (isset($cmbPerfil)) $cmbPerfilFilter = "= $cmbPerfil";
if (($cmbPerfil) == "%") $cmbPerfilFilter = "between 0 and 999";

if (!isset($cmbItem)) $cmbItem = "%";
if (isset($cmbItem)) $cmbItemFilter = "= $cmbItem";
if (($cmbItem) == "%") $cmbItemFilter = "between 0 and 999";

if (!isset($cmbEstado)) $cmbEstado = "%";
if (isset($cmbEstado)) $cmbEstadoFilter = "$cmbEstado";
if (($cmbEstado) == "%") $cmbEstadoFilter = "0,1";

$URL = "?txtNombre=$txtNombre&cmbPerfil=$cmbPerfil&cmbItem=$cmbItem&cmbEstado=$cmbEstado&Submit=$Submit"; // Nombre de esta Pagina

$sSql = ("
select a.idUsuario, a.nomUsuario, a.nickUsuario, a.isActivo, b.idPerfil, b.nomPerfil,
i.idItem, i.nomItem
from usuarios a
inner join perfiles b
	on a.idPerfil = b.idPerfil
inner join items i
	on i.idItem = a.idItem
where lower(a.nomUsuario) like lower('%$txtNombre%')
and a.idPerfil $cmbPerfilFilter
and i.idItem $cmbItemFilter
and a.isActivo in ($cmbEstadoFilter)
order by a.nomUsuario
");

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
$sDescr = $sRow[strtolower('nomUsuario')];
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
            <i class="icon-widget fa-list"></i><h3>Listado de Usuarios</h3>
        </div>
        <div class="widget_content">
        
        	<div id="PanelDetalle">
              <table width="650" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td></td>
                </tr>
                <tr>
                  <td class="TDTitle">Busqueda
                  <a id="btnNuevo" href="mant_Usuario.php" class="btn right"><i class="icon-fa fa-file-o"></i>Agregar Usuario</a>
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
                        <td class="row_form">Perfil:</td>
                        <td><select name="cmbPerfil" id="cmbPerfil">
                          <option value="%">-Cualquier Perfil-</option>
                          <?php
	  			
						 while ($rOW = fn_ExtraerQuery($sQL_getPerfiles))
							{
							 
							 $idPerfil 	= $rOW[strtolower('idPerfil')];
							 $nomPerfil	= $rOW[strtolower('nomPerfil')];
							
							 if ($idPerfil == $cmbPerfil ) {
							  	$value = 	"value=$idPerfil selected=\"selected\" ";
							 } else {
								$value = 	"value=$idPerfil"; 
							 }
						?>
                          <option <?php echo $value; ?>><?php echo $nomPerfil;?></option>
                          <?php
                            }
                        ?>
                        </select></td>
                      </tr>
                      <tr>
                        <td class="row_form">Item:</td>
                        <td><select name="cmbItem" id="cmbItem">
                          <option value="%">-Cualquier Item-</option>
                          <?php
	  			
						 while ($rOW = fn_ExtraerQuery($sQL_getItems))
							{
							 
							 $idItem 	= $rOW[strtolower('idItem')];
							 $nomItem	= $rOW[strtolower('nomItem')];
							
							 if ($idItem == $cmbItem ) {
							  	$value = 	"value=$idItem selected=\"selected\" ";
							 } else {
								$value = 	"value=$idItem"; 
							 }
						?>
                          <option <?php echo $value; ?>><?php echo $nomItem;?></option>
                          <?php
                            }
                        ?>
                        </select></td>
                      </tr>
                      <tr>
                        <td class="row_form">Nombre:</td>
                        <td><input name="txtNombre" type="text" id="txtNombre" value="<?php echo $txtNombre; ?>" size="40" maxlength="40" /></td>
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
                  <td><table cellpadding="0" cellspacing="1" width="600" class="adminList">
                      <thead>
                      <tr class="Titulos">
                        <td>Nombre Usuario</td>
                        <td>NickName</td>
                        <td>Perfil</td>
                        <td>Item</td>
                        <td>Estado</td>
                      </tr>
                      </thead>
                    <?php
						while ($sRow = fn_ExtraerQuery($sQL))
						{
							$idUsuario			= $sRow[strtolower('idUsuario')];
							$nomUsuario			= $sRow[strtolower('nomUsuario')];
							$nickUsuario		= $sRow[strtolower('nickUsuario')];
							$nomPerfil			= $sRow[strtolower('nomPerfil')];
							$nomItem			= $sRow[strtolower('nomItem')];
							$isActivo			= $sRow[strtolower('isActivo')];
												
									
							if ( $isActivo == 1 ) {
								$imgActivo = "<img src='".JPATH_BASE_WEB.DSW."imagenes/_publish_16.png' title='Activo' />";
							}
							else {
								$imgActivo = "<img src='".JPATH_BASE_WEB.DSW."imagenes/_nopublish_16.png' title='Inactivo' />";
							}
							
																				
							$hrefEdit = "mant_Usuario.php?id=".$idUsuario;
							
						?>
                    <tr>
                      <td><a href="<?php echo $hrefEdit; ?>" title="Editar Usuario"><?php echo $nomUsuario ?></a></td>
                      <td><?php echo $nickUsuario ?></td>
                      <td><?php echo $nomPerfil ?></td>
                      <td><?php echo $nomItem ?></td>
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
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td><?php fn_mostrarPaginacion($URL, $num_pags, $pag, $num_rows); ?></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
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