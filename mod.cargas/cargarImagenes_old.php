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
<title>Carga de Imagenes TIF</title>
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
$DirTrabajo = realpath(JPATH_BASE.DS._pathImagenesItem);
$DirPNG		= realpath(JPATH_BASE.DS._pathImagenesItemPNG);

if ($gestor = opendir($DirTrabajo)) {
	
	while (false !== ($file = readdir($gestor))) {
		$aFileInfo 		= explode(".",$file);
		$fileName 		= $aFileInfo[0];
		$fileExtension 	= $aFileInfo[1];
		
		if ($file == '.' || $file == '..') {
          continue;
        } else{
			if ($fileExtension == 'png'){ #tif
				$fileTiff 	= realpath($DirTrabajo.'/'.$file);
				$aFile 		= explode('_',$file);
				print_r($aFile);echo '<br/>';
				
				#Variables Separadas
				$sCatGrado 	= $aFile[0];
				$Centro 	= $aFile[2];
				$Alumno 	= $aFile[3];
				$Item 		= $aFile[4];
				$aItem 		= explode('.',$Item);
				
				#Codigos de Variables
				$CodCategoria	= substr($sCatGrado,0,2);
				$CodGrado		= substr($sCatGrado,2,2);
				$CodAnio		= '20'.substr($sCatGrado,5,2); 
				$CodCentro		= $Centro; #Cambiarse
				$CodItem 		= $aItem[0];
				
				#IDs de Informacion para Insercion en la BD
				$sRowItem 		= fndb_getItembyCodigo($CodCategoria,$CodGrado,$CodItem);
				$IDItem			= $sRowItem[strtolower('idItem')];
				$sRowCentro		= fndb_getCentroEducativobyCodigo($CodCentro);
				$IDCentro		= $sRowCentro[strtolower('idCentroEducativo')];
				
								
				#exit();
				if (isset($IDItem)){
					echo 'Archivo TIF: '.$fileTiff.'<br/>';
					
					echo 'Cod Categoria: '.$CodCategoria.'<br/>';
					echo 'Cod Grado: '.$CodGrado.'<br/>';
					echo 'Cod Anio: '.$CodAnio.'<br/>';
					echo 'Cod Centro: '.$CodCentro.'<br/>';
					echo 'Cod Item: '.$CodItem.'<br/>';  
					echo 'ID Item: '.$IDItem.'<br/>';  
					echo 'ID Centro: '.$IDCentro.'<br/>';  
					
					#exit();
					try{
						$filePng = $DirPNG.DS.$fileName.'.png';
						
						#No existe directorio, se crea
						if ( !file_exists($DirPNG) ) mkdir($DirPNG);
												
						echo 'Archivo PNG: '.$filePng.'<br/>';
						#Conversion de la Imagen
						#$image = new Imagick($fileTiff);
						#$image->writeImage($filePng);
						
						#Cargar Imagen en la BD
						$nExiste = fndb_existeImagen($CodAnio,$IDCentro,$IDItem,$fileName.'.png');
						if ( $nExiste == 0) {
							$nCargado = fndb_nuevaImagen(0,$CodAnio,$IDCentro,$IDItem,$fileName.'.png');
							if ($nCargado == 1) echo 'Imagen cargada satisfactoriamente<br/><br/>';
						}else{
							echo 'La imagen ya se encuentra cargada en la base de datos<br/><br/>';
						}
					}
					catch(exception $e){
						echo $e->getMessage();
					}#try
					
				}#isset($IDItem)
							
			}#$fileExtension
		}#$file
		
    }#while
}#$gestor
?>
</body>
</html>