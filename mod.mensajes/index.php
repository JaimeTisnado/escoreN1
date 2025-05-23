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
$idUsuario			= $_SESSION[_NameSession_idUser];
$Submit				= $_GET['Submit'];
$txtNombre			= $_GET['txtNombre'];
$txtAsunto			= $_GET['txtAsunto'];

$numRegistros 		= 20;
if (!isset($ID)) $ID = 0;

if (!isset($_GET['pag'] )) //  numero de pagina 
		$pag = 1;
else $pag = $_GET['pag'];

$URL = "?txtNombre=$txtNombre&txtAsunto=$txtAsunto&Submit=$Submit"; // Nombre de esta Pagina

$sSql = ("
select m.idMensaje, m.fromMensaje, uf.nomUsuario remitente, m.toMensaje, m.asunto, m.mensaje, m.fechaMensaje, m.isLeido, m.isEliminado
from mensajes m
inner join usuarios uf
	on uf.idUsuario = m.fromMensaje
where m.toMensaje = $idUsuario
and lower(m.asunto) like lower('%$txtAsunto%')
and lower(uf.nomUsuario) like lower('%$txtNombre%')
and m.isEliminado = '0'
order by m.fechaMensaje desc
") ;

$inicio = $numRegistros * ($pag-1); 
$final  = $inicio + ($numRegistros - 1);
$pTimeInicial 	= microtime(true);

$resultado = fn_EjecutarQuery($sSql);
$pTimeFinal 	= microtime(true);
$pTimeConsulta	= ($pTimeFinal - $pTimeInicial);

$num_rows = fn_NumeroRegistros($resultado);
$num_pags = ceil($num_rows / $numRegistros);

$sSql .= " limit $numRegistros offset $inicio";
$resultado = fn_EjecutarQuery($sSql);

$sQL = fn_EjecutarQuery($sSql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once(JPATH_BASE.DS."mod.includes/metaHeader.php"); ?>
<script type="application/javascript">
	$(document).ready(function(){
		/*$('.adminList tr').click(function(){
			var IDMensaje =  $(this).attr('id');
			if (typeof IDMensaje != 'undefined'){
				window.location = 'leer_Mensaje.php?id='+IDMensaje;
			}
			return false;
		});*/
	});
	
	function leer(idMensaje){    
		if (typeof idMensaje != 'undefined'){
			window.location = 'leer_Mensaje.php?id='+idMensaje;
		}
	}
	
	function eliminar(idMensaje){    
		if (confirm("¿Seguro desea eliminar este elemento?")) {
			$.post("updMensaje.php", { idMensaje:idMensaje }); 
		}
		setTimeout("location.reload(true);", 1000);  
	}

</script>
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
            <i class="icon-widget fa-envelope"></i>
            <h3>Mensajes</h3>
        </div>
        <div class="widget_content">
        
        	<div id="PanelDetalle">
             <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td></td>
                </tr>
                <tr>
                  <td class="TDTitle">Busqueda
                <!--<a id="btnNuevo" href="nuevo_Mensaje.php" class="btn right"><i class="icon-fa fa-edit"></i>Escribir Mensaje</a>-->			</td>
                </tr>
                <tr>
                  <td><form id="form1" name="form1" method="get" action="">
                    <table width="400" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="100">&nbsp;</td>
                        <td width="300">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="row_form">Remitente:</td>
                        <td><input name="txtNombre" type="text" id="txtNombre" value="<?php echo $txtNombre; ?>" size="40" maxlength="80" /></td>
                      </tr>
                      <tr>
                        <td class="row_form">Asunto:</td>
                        <td><input name="txtAsunto" type="text" id="txtAsunto" value="<?php echo $txtAsunto; ?>" size="40" maxlength="80" />                          <input name="Submit" type="hidden" id="Submit" value="1" /></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><a href="javascript:;" id="frmSubmit" class="btn btn_primary"><i class="icon-fa fa-search"></i>Buscar</a></td>
                      </tr>
                     </table>
                  </form></td>
                </tr>
                <tr>
                  <td><table cellpadding="0" cellspacing="1" class="adminList">
                      <thead>
                      <tr class="Titulos">
                        <td>&nbsp;</td>
                        <td>Remitente</td>
                        <td>Asunto</td>
                        <td>Fecha</td>
                        <td>&nbsp;</td>
                      </tr>
                      </thead>
                    <?php
						while ($sRow = fn_ExtraerQuery($sQL))
						{
							$idMensaje			= $sRow[strtolower('idMensaje')];
							$remitente			= $sRow[strtolower('remitente')];
							$asunto				= $sRow[strtolower('asunto')];
							$mensaje			= $sRow[strtolower('mensaje')];
							$fechaMensaje		= $sRow[strtolower('fechaMensaje')];
							$isLeido			= $sRow[strtolower('isLeido')];
							$isEliminado		= $sRow[strtolower('isEliminado')];
							$fechaFormateada	= date("d/m/Y h:i:s a", strtotime($fechaMensaje));					
							$hrefLeer 			= "leer_Mensaje.php?id=".$idMensaje;
							
							$cssMensaje = ($isLeido == '0' ? 'NoLeido' : 'Leido');
							
							$imgLeer 	= '<a href="'.$hrefLeer.'" title="Leer Mensaje">';
							$imgLeer .= "<img src='".JPATH_BASE_WEB.DSW."imagenes/_mail_16.png' border=0 /></a>";
							
							$imgDelete 	= '<a href="#" onclick="javascript:eliminar('.$idMensaje.')" title="Eliminar Mensaje">';
							$imgDelete .= "<img src='".JPATH_BASE_WEB.DSW."imagenes/_delete_16.png' border=0 /></a>";
							
						?>
                    <tr id="<?php echo $idMensaje; ?>" class="<?php echo $cssMensaje; ?>">
                      <td class=""><?php echo $imgLeer ?></td>
                      <td class="width20"><a href="<?php echo $hrefLeer; ?>"><?php echo $remitente ?></a></td>
                      <td class="width60"><?php echo $asunto; ?></td>
                      <td class="width20"><?php echo $fechaFormateada; ?></td>
                      <td class=""><?php echo $imgDelete ?></td>
                      </tr>
                    <?php
						}
						?>
                    </table></td>
                </tr>
                <tr>
                  <td><?php fn_mostrarPaginacion($URL, $num_pags, $pag, $num_rows); ?></td>
                </tr>
               </table>
              </div><!-- PanelDetalle -->
                
        </div><!-- content -->
    </div><!-- widget -->
            

   	          
        </div> <!-- column100 -->
    </div> <!-- container -->
</div> <!-- row -->
</div> <!-- content -->

<?php include_once(JPATH_BASE.DS."mod.includes/footerBar.php"); ?>

</body>
</html>