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
if (($gestor = fopen("usuarios_utf.txt", "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 0, ",")) !== FALSE) {
        $numero = count($datos);
        echo "<p>$numero de campos en la línea $fila:</p>";
		print_r($datos);echo '<br/>';
				
		$NomUsuario		= $datos[0];
		$nickUsuario	= $datos[1];
		$passUsuario	= $datos[2];
		$idPerfil		= $datos[3];
		$IDItem			= 1;
		
		echo 'Nombre: '.$NomUsuario.'<br/>';
		echo 'Usuario: '.$nickUsuario.'<br/>';
		echo 'Password: '.$passUsuario.'<br/>';
		echo 'IDPerfil: '.$idPerfil.'<br/>'; 
		echo 'IDItem: '.$IDItem.'<br/>'; 
		
		#exit();
		#Insertar Municipios
		try{
			$nExiste	= fndb_existeUsuario($nickUsuario);
				if ($nExiste == 0){
					$nInsert	= fndb_nuevoUsuario($idPerfil, $IDItem, $NomUsuario, $nickUsuario, $passUsuario, 1);
				}else{
					echo 'El usuario ya existe en la base de datos<br/>';
				}
			
			if ($nInsert == 1) echo 'Usuario ingresado correctamente <br/>'; 
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