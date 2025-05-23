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
<title>Carga de Centros Educativos</title>
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
if (($gestor = fopen("Listado_Centros_Educativos_utf.txt", "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 0, ",")) !== FALSE) {
        $numero = count($datos);
        echo "<p>$numero de campos en la línea $fila:</p>";
		print_r($datos);echo '<br/>';
				
		$NomCentro		= $datos[2];
		$CodCentro		= $datos[3];
		$CodDepto		= substr($CodCentro,0,2);
		$CodMunicipio	= substr($CodCentro,2,2);
		$sRowMuni		= fndb_getMunicipiobyCodigoDeptoMuni($CodDepto,$CodMunicipio);
		$IDMunicipio	= $sRowMuni[strtolower('idMunicipio')];
		
		
		echo 'ID Municipio: '.$IDMunicipio.'<br/>';
		echo 'Cod Departamento: '.$CodDepto.'<br/>';
		echo 'Cod Municipio: '.$CodMunicipio.'<br/>';
		echo 'Nom Centro: '.$NomCentro.'<br/>'; 
		echo 'Cod Centro: '.$CodCentro.'<br/>'; 
		
		#Insertar Municipios
		try{
			if ($NomCentro != 'ESPAÑA JESÚS MILLA SELVA' ){
				$nExiste	= fndb_existeCentroEducativo($CodCentro);
				if ($nExiste == 0){
					$nInsert	= fndb_nuevoCentroEducativo(0,$IDMunicipio,$NomCentro,$CodCentro,1);
				}
			}
			if ($nInsert == 1) echo 'Centro Educativo ingresado correctamente <br/>'; 
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