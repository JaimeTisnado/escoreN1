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
$idDepartamento		= $_REQUEST["idDepartamento"]; 

$sQL_get = fn_EjecutarQuery("
select d.idDepartamento, d.nomDepartamento, m.idMunicipio, m.nomMunicipio, m.isActivo
from municipios m
inner join departamentos d
	on d.idDepartamento = m.idDepartamento
where m.isActivo = 1
and m.idDepartamento = $idDepartamento
order by m.nomMunicipio
");

echo '<option selected="selected" value="0">-Seleccione Municipio-</option>';
while ($rOW = fn_ExtraerQuery($sQL_get))
{
$value 			= $rOW[strtolower('idMunicipio')];
$descripcion 	= $rOW[strtolower('nomMunicipio')];
										 
?>
<option value="<?php echo $value;?>"><?php echo $descripcion;?></option>
<?php
}

?>