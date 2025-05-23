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
// Informacion
$ID	= $_GET['id'];
if (!isset($ID)) $ID = 0;
fndb_getRubricabyIdItem($ID);

$dirFiles 	= JPATH_BASE_WEB.DSW._pathRubricasItem;
$archivo	= $dirFiles.$sArray[strtolower('rutaRubrica')];
(strlen($sArray[strtolower('rutaRubrica')]) == 0) ? $bFile = false : $bFile = true;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once(JPATH_BASE.DS."mod.includes/metaHeader.php"); ?>
</head>
<body class="bodyModal">
<?php include_once(JPATH_BASE.DS."mod.includes/noscript.php"); ?>

	
            <h2>Rubrica [<?php echo $sArray[strtolower('nomItem')]; ?>]</h2>
            
            <div>
              <table width="550" border="0" cellspacing="0" cellpadding="0">
              	<tr>
                  <td class="width10">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" class="Negrita">Descripción</td>
                </tr>
                <tr>
                  <td colspan="2"><?php echo $sArray[strtolower('memoRubrica')]; ?></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <?php if ($bFile != false): ?>
                <tr>
                  <td colspan="2" class="Negrita">Descargar Archivo</td>
                </tr>
				<tr>
                  <td colspan="2"><?php echo '<a href="'.$archivo.'" target="_blank">'.$sArray[strtolower('rutaRubrica')].'<a/>'; ?></td>
                </tr>
                <?php endif; ?>
              </table>
            </div>
   	          
     
</body>
</html>