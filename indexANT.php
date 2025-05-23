<?php
$approot = dirname($_SERVER['SCRIPT_NAME']);
$BFolder = explode('/', $approot);
$Folder  = $approot;
if(isset($BFolder[1])) $Folder = $BFolder[1];
if (!defined('BASE_FOLDER')) define('BASE_FOLDER',$Folder);
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
//Defines.
if (!defined('FBASE')) define('FBASE', ( BASE_FOLDER == 'panel' ? NULL : BASE_FOLDER ) );
if (!defined('JPATH_BASE')) define('JPATH_BASE',$_SERVER['DOCUMENT_ROOT'].'/'.FBASE);
if (!defined('JPATH_BASE_PANEL')) define('JPATH_BASE_PANEL', JPATH_BASE.'/panel');

//--->Configuracion General
require_once(JPATH_BASE.DS.'mod.config/config.php');
$isMostrarAlert = false;
include_once(JPATH_BASE.DS."mod.includes/sessions.php"); 

if ( isset($_SESSION[_NameSession_idUser]) ) # (session_is_registered(_NameSession_idUser) ) 
{ 
	header("Location: dashboard.php");
}

if (!isset($_SESSION[_NameSession_mensaje])){
	$me = $_GET['me'];
	switch($me){
		case '0':
			$_SESSION[_NameSession_mensaje] = 'El sístema en este momento no se encuentra disponible.';
		break;
		default:
			$_SESSION[_NameSession_mensaje] = NULL;
		break;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" />
<title>Login <?php echo _nomPanel.' | '._metaAutor; ?></title>
<link href="<?php echo JPATH_BASE_WEB.DSW; ?>imagenes/favicon.ico" rel="shortcut icon" type="image/ico"/>
<link href="<?php echo JPATH_BASE_WEB.DSW; ?>css/styles.css" rel="stylesheet" type="text/css" />

</head>

<body onload="javascript:setFocus();">
<?php include_once(JPATH_BASE.DS."mod.includes/noscript.php"); ?>
<div class="headerBar headerBar_fixed">
    <div class="headerBar_content">
        
        <div class="container">
            <a class="brand" href="javascript:;"><?php echo _nomApp; ?></a>
            
        </div><!-- container -->
        
    </div><!-- headerBar_content -->   
</div><!-- headerBar -->


<div id="content_login">
<div class="row">

<div class="container">

<div class="divContentMsg" id="IDMsg">
<?php 
if (isset($_SESSION[_NameSession_mensaje])){
echo '<span class="ContentMsgWarning">'.$_SESSION[_NameSession_mensaje].'</span>';
unset($_SESSION[_NameSession_mensaje]);
}
?>
</div>
<div class="login_container stacked">
	<div class="content">
    	<form id="form1" name="form1" method="post" action="" AutoComplete="Off">
        <h1>Autentificación</h1>
        <div class="login_fields">
        
            <p>Ingresa con los datos de tu cuenta:</p>
            <div class="field">
                <input id="txtUsuario" class="login username-field" type="text" placeholder="Usuario" value="" name="txtUsuario"></input>
            </div>
            <div class="field">
                <input id="txtPassword" class="login password-field" type="password" placeholder="Contraseña" value="" name="txtPassword"></input>
            </div>
                
        </div><!-- login_fields -->
        
        <div class="login_actions">
			<a class="btn btn_primary" href="javascript:;" id="IDLoginAcceso"><i class="icon-fa fa-sign-in"></i>Login</a>	
        </div> <!-- login_actions -->
        <hr />
        </form>
    </div><!-- content -->
    
    
</div><!-- login_container -->

</div><!-- container -->

</div><!-- row -->
</div><!-- content_login -->

<?php include_once(JPATH_BASE.DS."mod.includes/footerBar.php"); ?>


<script src="<?php echo JPATH_BASE_WEB.DSW; ?>js/jquery.min.js" type="text/javascript"></script>
<script>
	$(function(){
		$("#txtPassword").keyup(function(event){
			if(event.keyCode == 13){
				$("#IDLoginAcceso").click();
			}
		});
		
		$(".close").click(function(event) {
			//$(this).parent().remove();	
			$(this).parent().fadeTo(300,0,function(){
				  $(this).remove();
			});
		});
		
	});
	
	function setFocus() {
		document.getElementById('txtUsuario').focus();
	}
	
	//Ajax Login
$(function (){
	$("#IDLoginAcceso").click(function(){
		var usuario = $("#txtUsuario").val();
        var password = $("#txtPassword").val();
		$.post("mod.ajax/login.php",
			  { 'txtUsuario' : usuario, 'txtPassword': password },
			  function(result){
				  if (result == 0){
					window.location.href = "dashboard.php";
				  }else{
				  	$("#IDMsg").html(result);
					$("#txtUsuario").focus();
				  }
				  				   
			  });
	});
});
</script>
</body>
</html>