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
define('pUrl', JPATH_BASE_WEB."/dashboard.php");
$PAGINA				= $_SERVER['PHP_SELF'];
$ID					= $_SESSION[_NameSession_idUser];

if(isset($ID)){
fndb_getUsuariobyId($ID);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once(JPATH_BASE.DS."mod.includes/metaHeader.php"); ?>
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
            <i class="icon-widget fa-list"></i><h3>Perfil de Usuario</h3>
        </div>
        <div class="widget_content">
        
        	<div id="PanelMantenimiento">
            	<form id="form1" name="form1" method="post" action="">
            	  <table width="500" cellpadding="0" cellspacing="1">
            	    <tr>
            	      <td class="">&nbsp;</td>
            	      <td class="">&nbsp;</td>
           	        </tr>
            	    <tr>
            	      <td class="row_form">Usuario:</td>
            	      <td>
                      <input name="txtUsuario" type="text" id="txtUsuario" disabled="disabled" value="<?php echo $sArray[strtolower('nickUsuario')]; ?>" /></td>
           	        </tr>
            	   <tr>
            	      <td class="row_form">Nombre Completo:</td>
            	      <td>
                      <input name="txtNombre" type="text" disabled="disabled" id="txtNombre" value="<?php echo $sArray[strtolower('nomUsuario')]; ?>" size="50" />            	        </td>
           	        </tr>
            	    <tr>
            	      <td class="row_form">Perfil:</td>
            	      <td><input name="txtPerfil" type="text" disabled="disabled" id="txtPerfil" value="<?php echo $sArray[strtolower('nomPerfil')]; ?>" size="30" />            	       </td>
           	        </tr>
                    <tr>
            	      <td class="row_form">Última Conexión:</td>
            	      <td><?php echo $sArray[strtolower('lastLogin')]; ?></td>
           	        </tr>
            	    <tr>
            	      <td class="row_form">Perfil de Calificación:</td>
            	      <td><?php echo 'Categoria: '.$sArray[strtolower('nomCategoria')]; ?></td>
           	        </tr>
                    <tr>
            	      <td>&nbsp;</td>
            	      <td><?php echo 'Grado: '.$sArray[strtolower('nomGrado')]; ?></td>
           	        </tr>
                    <tr>
            	      <td>&nbsp;</td>
            	      <td><?php echo 'Pregunta: '.$sArray[strtolower('nomItem')]; ?></td>
           	        </tr>
            	    <tr>
            	      <td>&nbsp;</td>
            	      <td><input name="Submit" type="hidden" id="Submit" value="1" /></td>
           	        </tr>
            	    <tr>
            	      <td>&nbsp;</td>
            	      <td>&nbsp;</td>
           	        </tr>
          	    </table>
            	</form>
            </div><!-- PanelMantenimiento -->
                
        </div><!-- content -->
    </div><!-- widget -->
            

   	          
        </div> <!-- column100 -->
    </div> <!-- container -->
</div> <!-- row -->
</div>

<?php include_once(JPATH_BASE.DS."mod.includes/footerBar.php"); ?>
</body>
</html>