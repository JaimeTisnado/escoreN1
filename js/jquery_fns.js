// JavaScript Document
// When the document loads do everything inside here ...
$(document).ready(function(){
	
	/*$('.list_top > li').click(function(){
		$(this).addClass("open");
	});*/
	
	$('.list_top > li').hover(
		function(){
			$('.list_top_sub',this).stop(true,true).fadeIn('fast');
		},
			function(){
			$('.list_top_sub',this).fadeOut('fast');
		}
	);
	
	$('.list_main > li').hover(
		function(){
			$('.list_main_sub',this).stop(true,true).fadeIn('fast');
		},
			function(){
			$('.list_main_sub',this).fadeOut('fast');
		}
	);
	
	/*---Forma Manual (html: <div id="toTop"></div> 
	$(window).scroll(function() {
		if($(this).scrollTop() != 0) {
			$('#toTop').fadeIn();	
		} else {
			$('#toTop').fadeOut();
		}
	});
 
	$('#toTop').click(function() {
		$('body,html').animate({scrollTop:0},200);
	});	
	*/
	function enableBackToTop () {
		var backToTop = $('<a>', { id: 'toTop' });
		backToTop.appendTo('body');
				
	    backToTop.hide();

	    $(window).scroll(function () {
	        if ($(this).scrollTop() != 0) {
	            backToTop.fadeIn ();
	        } else {
	            backToTop.fadeOut ();
	        }
	    });

	    backToTop.click (function (e) {
	    	e.preventDefault ();

	        $('body, html').animate({
	            scrollTop: 0
	        }, 200);
	    });
	}
	
	// $("[title]").tooltip();
	$("a[href][title]").tooltip({ position: "bottom right", delay: 0, tipClass: "tooltip"});
	$("img[title]").tooltip({ position: "bottom right", delay: 0});
	$("li[title]").tooltip({ position: "center right", delay: 0, tipClass: "tooltipHelp"});
	$("span[title]").tooltip({ position: "bottom right", delay: 0, tipClass: "tooltipHelp"});
	
	$('#frmSubmit').click(function() {
	  $('#form1').submit();
	});
	
	$('#frmSubmit2').click(function() {
	  $('#form2').submit();
	});
	
	$('#frmGuardar').click(function() {
		if (confirm("¿Seguro desea Guardar los Datos?")) { 
	  		$('#form1').submit();
		}
	});
	
	$('#frmGuardar2').click(function() {
		if (confirm("¿Seguro desea Guardar los Datos?")) { 
	  		$('#form2').submit();
		}
	});
	
	$("#btnNuevo").click(function(){
		$("#PanelDetalle").hide();
		$("#PanelMantenimiento").show();
		
    });
	
	$("#btnCancelar").click(function(){
		$("#PanelDetalle").show();
		$("#PanelMantenimiento").hide();
    });
	
	$(".adminList").tablesorter(); 
	
	$(window).load(function () {
		IniciarTimeOut();
		listexpander();
		enableBackToTop();
		refreshMensajes();
	});
		
}); //FIN  $(document).ready(function(){


function jsSubmit(form)
{
 	form.submit();
}

function jsGuardar(form)
{
 	if (confirm("¿Seguro desea Guardar los Datos?")) { 
		form.submit();
	}
}

function jsRegresar(pagina)
{
	document.location.href=pagina;
}

function isCampoEntero(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

function isCampoNumerico(event, obj) {
    var noIE = false;
    if (!event) {
        event = window.event;
    }
    if (event.keyCode) {
        code = event.keyCode;
    }
    else if (event.which) {
        code = event.which; noIE = true;
    }
    // check double dot
    if (obj.value.indexOf('.') != -1 && code == 46) {
        return false;
    }

    return ((code >= 48 && code <= 57) || code == 46 || code == 8 || code == 9 || (code == 35 && noIE == true));
}