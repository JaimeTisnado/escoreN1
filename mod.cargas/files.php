<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Conteo de Imagenes</title>
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

$directory = "../img.preguntas/png/"; // dir location
if (glob($directory . "*.*") != false)
{
 $filecount = count(glob($directory . "*.*"));
 echo $filecount.'<br/>';
}
else
{
 echo 0;
}


$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$totaltime = number_format(($endtime - $starttime),3);
echo "El tiempo de ejecución fue de ".$totaltime." segundos."; 
   
?>
</body>
</html>