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
$ID					= $_GET['id'];
$Submit				= $_GET['Submit'];
$txtNombre			= $_GET['txtNombre'];

$numRegistros 		= _numRegistros;

if (!isset($ID)) $ID = 0;

if (!isset($_GET['pag'] )) //  numero de pagina 
		$pag = 1;
else $pag = $_GET['pag'];

$URL = "?id=$ID&txtNombre=$txtNombre&Submit=$Submit"; // Nombre de esta Pagina

$sSql = ("select a.idAcceso, a.nomAcceso, a.linkAcceso, a.orden, a.isActivo, a.parentID
				  from accesos a 
				  where lower(a.nomAcceso) like lower('%$txtNombre%')
				  and a.isActivo in (1)
				  and a.idAcceso not in (select idAcceso 
				  						from perfilaccesos
										where idPerfil = $ID)
				  order by a.orden,a.parentID") ;

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
<?php
if ( isset( $_POST ) )
{
   $postArray = &$_POST; #Captura todos los obejtos creados del FORM
}

$Submit2			= $_POST['Submit2'];
$numSeleccionados 	= sizeof($postArray['chkAcceso']);

for ($i=0;$i<$numSeleccionados;$i++){

	$txtIdAcceso = $postArray['chkAcceso'][$i];
	echo $txtIdAcceso;
	if ($Submit2 == 1) {
	fndb_nuevoPerfilAcceso($ID, $txtIdAcceso);	
	}
	
} // fin for

if ($Submit2 == 1) {
echo fn_CloseFancyBox();
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once(JPATH_BASE.DS."mod.includes/metaHeader.php"); ?>
</head>
<body class="bodyModal">
<?php include_once(JPATH_BASE.DS."mod.includes/noscript.php"); ?>

	
            <h2>Lista de Accesos</h2>
            
            <div>
              <table width="550" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><form id="form1" name="form1" method="get" action="">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="100" class="row_form">Nombre:</td>
                        <td width="300"><label for="txtMonto">
                          <input name="txtNombre" type="text" id="txtNombre" value="<?php echo $txtNombre; ?>" size="40" maxlength="40" />
                          <input name="Submit" type="hidden" id="Submit" value="1" />
                          <input name="id" type="hidden" id="id" value="<?php echo $ID; ?>" />
                        </label></td>
                        <td width="300"><div class="linkGeneralPanelMin"><a href="javascript:;" id="frmSubmit" class="btn btn_primary"><i class="icon-fa fa-search"></i></a></div></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                  </form></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>
                  <form id="form2" name="form2" method="post" action="">
                  <table width="500" cellpadding="0" cellspacing="1" class="adminList">
                      <tr id="Titulos">
                        <td>&nbsp;</td>
                        <td>Nombre del Acceso</td>
                      </tr>
                    <?php
						while ($sRow = fn_ExtraerQuery($sQL))
						{
							$idAcceso	= $sRow['idacceso'];
							$nomAcceso	= $sRow['nomacceso'];
							$parentID	= $sRow['parentid'];
							if ($parentID == 0) {
								$nomAcceso = "<b>$nomAcceso</b>";					
							}else{
								$nomAcceso = "<span class='indent'>$nomAcceso</span>";	
							}							
						?>
                    <tr>
                      <td width="8%" class="alinearCentro"><input name="chkAcceso[]" type="checkbox" id="chkAcceso[]" value="<?php echo $idAcceso?>" /></td>
                      <td width="92%"><?php echo $nomAcceso ?></td>
                    </tr>
                    <?php
						}
						?>
                    <tr>
                      <td>&nbsp;</td>
                      <td><input name="Submit2" type="hidden" id="Submit2" value="1" /></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="alinearCentro"><a href="javascript:;" id="frmGuardar2" class="btn btn_succes">Guardar</a></div></td>
                    </tr>
                  </table>
                  </form>
                  </td>
                </tr>
                <tr>
                  <td><?php fn_mostrarPaginacion($URL, $num_pags, $pag, $num_rows); ?></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
              </table>
            </div>
   	          
     
</body>
</html>