<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes"> 
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9" />
<title>DashBoard eScore | <?php echo _metaAutor; ?></title>
<link href="<?php echo JPATH_BASE_WEB.DSW; ?>imagenes/favicon.ico" rel="shortcut icon" type="image/ico"/>
<link href="<?php echo JPATH_BASE_WEB.DSW; ?>css/styles.css" rel="stylesheet" type="text/css" />

<script src="<?php echo JPATH_BASE_WEB.DSW; ?>js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo JPATH_BASE_WEB.DSW; ?>js/jquery.tooltip.js" type="text/javascript"></script>
<script src="<?php echo JPATH_BASE_WEB.DSW; ?>js/listexpander.js" type="text/javascript"></script>
<script src="<?php echo JPATH_BASE_WEB.DSW; ?>js/jquery.tablesorter.min.js" type="text/javascript"></script>
<script src="<?php echo JPATH_BASE_WEB.DSW; ?>js/jquery_fns.js" type="text/javascript"></script>
<script src="<?php echo JPATH_BASE_WEB.DSW; ?>js/rightClick.js" type="text/javascript"></script>
<!-- fancybox -->
<script type="text/javascript" src="<?php echo JPATH_BASE_WEB.DSW; ?>css/fancybox/jquery.mousewheel-3.0.2.pack.js"></script>
<script type="text/javascript" src="<?php echo JPATH_BASE_WEB.DSW; ?>css/fancybox/jquery.fancybox-1.3.1.js"></script>
<script type="text/javascript" src="<?php echo JPATH_BASE_WEB.DSW; ?>css/fancybox/web.js?m=20100203"></script>
<link href="<?php echo JPATH_BASE_WEB.DSW; ?>css/fancybox/jquery.fancybox-1.3.1.css" rel="stylesheet" type="text/css" />

<!-- JQuery UI -->
<script src="<?php echo JPATH_BASE_WEB.DSW; ?>js/jquery.ui.core.js"></script>
<script src="<?php echo JPATH_BASE_WEB.DSW; ?>js/jquery.ui.datepicker.js"></script>
<script src="<?php echo JPATH_BASE_WEB.DSW; ?>js/jquery.ui.datepicker-es.js"></script>
<link href="<?php echo JPATH_BASE_WEB.DSW; ?>css/jquerytheme/jquery-ui-1.8.23.custom.css" rel="stylesheet" type="text/css" />

<script language="javascript">
// =========== LOGOUT AUTOMATICO ========== //
    var timeLogout = 0;
    var sessionLogoutTimer = 0;
    var sessionTimeoutWarning = 1;
    var sessionTimeout = <?php echo (_MinExpireSession + 1); ?>;
    
    function IniciarTimeOut() {
        clearTimeout(sessionLogoutTimer);
        timeLogout = ((parseInt(sessionTimeout) - parseInt(sessionTimeoutWarning)) * 60000);
        sessionLogoutTimer = setTimeout('SessionLogout()', timeLogout);
    }
    
    function SessionLogout() {
        clearTimeout(sessionLogoutTimer);
        RedirectLogout();
    } // fin SessionLogout

    function RedirectLogout() {
        var href = "<?php echo JPATH_BASE_WEB.DSW.'logout.php'; ?>";
        document.location.href = href;
    } // fin RedirectLogout
    //=========== LOGOUT AUTOMATICO ========== //
</script>

<script type="text/javascript">
//setInterval(refreshMensajes, 5000); 

function refreshMensajes(){
$.post("<?php echo JPATH_BASE_WEB.DSW; ?>postMensajes.php",
	  { '' : 0, '':1 },
	  function(result){
		   $("#msg").html(result);
	  });
}
</script>