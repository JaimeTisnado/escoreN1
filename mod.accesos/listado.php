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
/*$Submit				= $_GET['Submit'];
$cmbPadre			= $_GET['cmbPadre'];
$txtNombre			= $_GET['txtNombre'];
$cmbEstado			= $_GET['cmbEstado'];*/


$Submit     = isset($_GET['Submit']) ? $_GET['Submit'] : '';
$cmbPadre   = isset($_GET['cmbPadre']) ? $_GET['cmbPadre'] : '%';
$txtNombre  = isset($_GET['txtNombre']) ? $_GET['txtNombre'] : '';
$cmbEstado  = isset($_GET['cmbEstado']) ? $_GET['cmbEstado'] : '%';



$numRegistros 		= _numRegistros;

if (!isset($_GET['pag'] )) //  numero de pagina 
		$pag = 1;
else $pag = $_GET['pag'];


if (!isset($cmbPadre)) $cmbPadre = "%";
if (isset($cmbPadre)) $cmbPadreFilter = "= $cmbPadre";
if (($cmbPadre) == "%") $cmbPadreFilter = "between 0 and 999";

if (!isset($cmbEstado)) $cmbEstado = "%";
if (isset($cmbEstado)) $cmbEstadoFilter = "$cmbEstado";
if (($cmbEstado) == "%") $cmbEstadoFilter = "0,1";

$URL = "?cmbPadre=$cmbPadre&txtNombre=$txtNombre&cmbEstado=$cmbEstado&Submit=$Submit"; // Nombre de esta Pagina

$sSql = ("select a.idAcceso, a.nomAcceso, a.linkAcceso, a.orden, a.parentID, a.isActivo
				  from accesos a
				  where lower(a.nomAcceso) like lower('%$txtNombre%')
				  and a.parentID $cmbPadreFilter
				  and a.isActivo in ($cmbEstadoFilter)
				  order by a.orden, a.parentID") ;

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
$sDescr = $sRow['nomPerfil'];
/* ------ */

$sSql .= " limit $numRegistros offset $inicio";
$resultado = fn_EjecutarQuery($sSql);
$sQL = fn_EjecutarQuery($sSql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once(JPATH_BASE.DS."mod.includes/metaHeader.php"); ?>
<script language="javascript">
function reOrdenar(id, original, nuevo){    
    $.post("updOrdenar.php", { id: id, original: original, nuevo: nuevo }
	
    );    
	setTimeout("location.reload(true);", 1000);
	//window.location.reload();     
}
</script>
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
            <i class="icon-widget fa-list"></i><h3>Listado de Accesos</h3>
        </div>
        <div class="widget_content">
            
            <div id="PanelDetalle">
            
              <table width="650" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="TDTitle">Busqueda
                  	<a href="mant_Acceso.php" class="btn right"><i class="icon-fa fa-file-o"></i>Agregar Acceso</a>
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
                        <td class="row_form">Acceso Padre:</td>
                        <td><select name="cmbPadre" id="cmbPadre">
                          <option value="%">-Cualquier Acceso Padre-</option>
                          <?php
	  			
						 while ($rOW = fn_ExtraerQuery($sQL_getAccesosPadre))
							{
							 
							 $idAcceso 	= $rOW['idacceso'];
							 $nomAcceso	= $rOW['nomacceso'];
							 							
							 if ($idAcceso == $cmbPadre ) {
							  	$value = 	"value=$idAcceso selected=\"selected\" ";
							 } else {
								$value = 	"value=$idAcceso"; 
							 }
						?>
                          <option <?php echo $value; ?>><?php echo $nomAcceso;?></option>
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
                        <!--- onclick="jsSolCnsltaRemesa(this.form)" -->
                      </tr>
                     </table>
                  </form></td>
                </tr>
                <tr>
                  <td><table cellpadding="0" cellspacing="1" width="600" class="adminList">
                  <thead>
                      <tr class="Titulos">
                        <td>Nombre Acceso</td>
                        <td>Enlace</td>
                        <td>Estado</td>
                        <td>Orden</td>
                      </tr>
                  </thead>
                  <tbody> 
                    <?php
						while ($sRow = fn_ExtraerQuery($sQL))
						{
							$idAcceso		= $sRow['idacceso'];
							$nomAcceso		= $sRow['nomacceso'];
							$linkAcceso		= $sRow['linkacceso'];
							$orden			= $sRow['orden'];
							$isActivo		= $sRow['isactivo'];
							$parentID		= $sRow['parentid'];
							if ($parentID == 0) {
								$nomAcceso = "<b>$nomAcceso</b>";					
							}else{
								$nomAcceso = "<span class='indent'>$nomAcceso</span>";	
							}
									
							if ( $isActivo == 1 ) {
								$imgActivo = "<img src='".JPATH_BASE_WEB.DSW."imagenes/_publish_16.png' title='Activo' />";
							}
							else {
								$imgActivo = "<img src='".JPATH_BASE_WEB.DSW."imagenes/_nopublish_16.png' title='Inactivo' />";
							}
							
							$pid		= $idAcceso;
							$poriginal 	= chr(39).($orden).chr(39); // original
							$maximo 	= fndb_getOrdenAccesos() -1;
							
							if ($orden == 1) {
								
								$pnuevoDo = chr(39).($orden+1).chr(39); // nuevo
								
						$divOrden = '<div class="ordenBoxDown"><a href="#" onclick="javascript:reOrdenar('.$pid.','.$poriginal.','.$pnuevoDo.');">&nbsp;</a></div>';
										 
							} else if ($orden == $maximo) {
								#$divOrden = '<a href="ordenar.php?order='.($orden-1).'"><div class="ordenBoxUp">&nbsp;</div></a>';
								$pnuevoUp = chr(39).($orden-1).chr(39); // nuevo
								
						$divOrden = '<div class="ordenBoxUp"><a href="#" onclick="javascript:reOrdenar('.$pid.','.$poriginal.','.$pnuevoUp.');">&nbsp;</a></div>';
										 
							} else {
								#$divOrden = '<a href="ordenar.php?order='.($orden-1).'"><div class="ordenBoxUp">&nbsp;</div></a>
								#			 <a href="ordenar.php?order='.($orden+1).'"><div class="ordenBoxDown">&nbsp;</div></a>';
							
							$pnuevoUp = chr(39).($orden-1).chr(39); // nuevo
							$pnuevoDo = chr(39).($orden+1).chr(39); // nuevo
							
						$divOrden = '<div class="ordenBoxUp"><a href="#" onclick="javascript:reOrdenar('.$pid.','.$poriginal.','.$pnuevoUp.');">&nbsp;</a></div>';
						$divOrden .= '<div class="ordenBoxDown"><a href="#" onclick="javascript:reOrdenar('.$pid.','.$poriginal.','.$pnuevoDo.');">&nbsp;</a></div>';		
										 			 
							}
							
							$hrefEdit = "mant_Acceso.php?id=".$idAcceso;
							
						?>
                    <tr>
                      <td><a href="<?php echo $hrefEdit; ?>" title="Editar Acceso"><?php echo $nomAcceso ?></a></td>
                      <td><?php echo $linkAcceso ?></td>
                      <td class="alinearCentro"><?php echo $imgActivo ?></td>
                      <td class="alinearCentro"><?php echo $divOrden ?>
              <div class="ordenBox"><?php echo $orden ?></div></td>
                    </tr>
                    <?php
						}
						?>
                    </tbody>
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