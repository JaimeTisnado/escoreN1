jQuery(document).ready(function() {

    /*
    *   Modal Dialogs
    */
		
	$("a#Gallery").fancybox({
		'transitionIn'	:	'fade',
		'transitionOut'	:	'fade',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	true,
		'titleShow'		:	true,
		'titlePosition':	'over'	// 'outside', 'inside' or 'over'
	});
	
    $(".Editar").fancybox({
        'width': '75%',
        'height': '85%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'type': 'iframe',
        'onClosed': function() { parent.location.reload(true); }
    });

    /* -> Master Default */
    $(".openModal").fancybox({
        'width': 580,
        'height': '70%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'modal': true,
        'type': 'iframe'
    });
	
	$(".openModalRub").fancybox({
        'width': 580,
        'height': '70%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'modal': false,
        'type': 'iframe'
    });
	
	 $(".openGMaps").fancybox({
        'width': 580,
        'height': 380,
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'modal': true,
        'type': 'iframe'
    });
	
	$(".openModalAccesos").fancybox({
        'width': 580,
        'height': '70%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'modal': true,
        'type': 'iframe',
		'showCloseButton' : true,
		'onClosed': function() { parent.location.reload(true); } 
    });
    
	 $(".openModalTipoMenu").fancybox({
        'width': 580,
        'height': '70%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'modal': true,
        'type': 'iframe'
    });
	
	$(".openModalSlide").fancybox({
        'width': 860,
        'height': '50%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'modal': true,
        'type': 'iframe'
    });
	
	/*
	$('#modalClose').live('click', function(e) {
		var thisHref = $(this).attr('href');
		parent.location.href = thisHref;
		//parent.$.fancybox.close();
	});*/
	
	
});   // fin
