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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Carga de Municipios</title>
<style>
body{
	font-family:Verdana, Geneva, sans-serif;
	font-size:11px;
	color:#333;	
}
</style>
</head>
<body>
<?php
$fila = 1;
if (($gestor = fopen("listado_municipios_utf.txt", "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 0, ",")) !== FALSE) {
        $numero = count($datos);
        echo "<p>$numero de campos en la línea $fila:</p>";
		print_r($datos);echo '<br/>';
				
		$CodDepto		= $datos[0];
		$NomMunicipio	= $datos[1];
		$CodMunicipio	= $datos[2];
		$sRowDepto		= fndb_getDepartamentobyCodigo($CodDepto);
		$IDDepto		= $sRowDepto[strtolower('idDepartamento')];
		
		
		echo 'ID Departamento: '.$IDDepto.'<br/>';
		echo 'Cod Departamento: '.$CodDepto.'<br/>';
		echo 'Cod Municipio: '.$CodMunicipio.'<br/>';
		echo 'Nom Municipio: '.$NomMunicipio.'<br/>'; 
		
		#Insertar Municipios
		try{
			if ($NomMunicipio != 'La Ceiba' && $NomMunicipio != 'Distrito Central' ){
				$nExiste	= fndb_existeMunicipio($CodDepto, $CodMunicipio);
				if ($nExiste == 0){
					$nInsert	= fndb_nuevoMunicipio(0,$IDDepto,$NomMunicipio,$CodMunicipio,1);
				}else {
					echo 'Municipio ya existe en la BD <br/>'; 	
				}
			}
			if ($nInsert == 1) {
				echo 'Municipio ingresado correctamente <br/>'; 
			}else{
				echo 'Municipio no ingresado en la BD <br/>'; 
			}
		}catch(exception $e){
			echo $e->getMessage();	
		}
		$fila++;
    }
    fclose($gestor);
}
?>
</body>
</html>