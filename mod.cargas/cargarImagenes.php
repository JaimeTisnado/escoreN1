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
set_time_limit(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Proceso de Carga de Imagenes</title>
<link href="<?php echo JPATH_BASE_WEB.DSW; ?>imagenes/favicon.ico" rel="shortcut icon" type="image/ico"/>
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
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime; 

$anioCarga		= $_POST['txtAnio'];
$DirTrabajo 	= realpath(JPATH_BASE.DS._pathImagenesItem);
$DirPNG			= realpath(JPATH_BASE.DS._pathImagenesItemPNG);
$nFilesTotal	= count(glob($DirTrabajo. "/*.png"));
$nFilesOK 		= 0;
$nFilesKO		= 0;
$aArrayKO		= array();

echo '<h3><b>Cargando imagenes para el año lectivo <i>'.$anioCarga.'</i></b></h3>';
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
				
				#Validar año del archivo
				if ($anioCarga != $CodAnio){
					continue;
				}
				#IDs de Informacion para Insercion en la BD
				$sRowItem 		= fndb_getItembyCodigo($CodCategoria,$CodGrado,$CodItem);
				$IDItem			= $sRowItem[strtolower('idItem')];
				$sRowCentro		= fndb_getCentroEducativobyCodigo($CodCentro);
				$IDCentro		= $sRowCentro[strtolower('idCentroEducativo')];
				
								
				print_r($aFile);echo '<br/>';
				if (isset($IDItem) && isset($IDCentro)){
					echo 'Archivo PNG: '.$fileTiff.'<br/>';
					
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
							#moviendo el archivo de la carpeta de trabajo a destino de almacenamiento
							$bMovido = copy($fileTiff, $filePng);
							if ($bMovido == true){
								echo 'Imagen movida a destino satisfactoriamente<br/>';
							}else{
								echo 'Se presento un error al mover la Imagen <b>'.$fileName.'</b> a su destino<br/><br/>';
								$nFilesKO++;
								array_push($aArrayKO,$fileName);
								continue;
							}
							$nCargado = fndb_nuevaImagen(0,$CodAnio,$IDCentro,$IDItem,$fileName.'.png');
							if ($nCargado == 1) {
								#eliminamos el archivo ya copiado a su destino y cargado a la BD
								unlink($fileTiff);
								echo 'Imagen <b>'.$fileName.'</b> cargada satisfactoriamente<br/><br/>';
								$nFilesOK++;
							}else{
								echo 'La Imagen <b>'.$fileName.'</b> no fue cargada<br/><br/>';
								$nFilesKO++;
								array_push($aArrayKO,$fileName);
							}
						}else{
							echo 'La imagen <b>'.$fileName.'</b> ya se encuentra cargada en la base de datos<br/><br/>';
							$nFilesKO++;
							array_push($aArrayKO,$fileName);
						}
					}
					catch(exception $e){
						echo $e->getMessage();
					}#try
					
				}else{#isset($IDItem)
					echo 'La información de la imagen <b>'.$fileName.'</b> no fue encontrada<br/><br/>';
					$nFilesKO++;
					array_push($aArrayKO,$fileName);
				}
			}#$fileExtension
		}#$file
		
    }#while
}#$gestor

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$totaltime = number_format(($endtime - $starttime),3);

#Resumen
echo '<h3>-- Resumen del Proceso de Carga --</h3><ul>';
echo '<li>N° de imagenes encontradas: <b>'.$nFilesTotal.'</b></li>';
echo '<li>N° de imagenes procesadas: <b>'.$nFilesOK.'</b></li>';
echo '<li>N° de imagenes no procesadas: <b>'.$nFilesKO.'</b></li>';
echo '</ul>';

echo '<b>Imagenes no procesadas</b>';
echo '<pre>';
foreach($aArrayKO as $item){
	echo $item.'<br/>';
}
#print_r($aArrayKO);
echo '</pre>';

echo "<h4>El tiempo de ejecución fue de ".$totaltime." segundos.</h4>"; 
?>
</body>
</html>