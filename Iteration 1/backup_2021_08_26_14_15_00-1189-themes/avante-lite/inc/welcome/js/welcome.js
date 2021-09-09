jQuery(document).ready(function() {
	
	/* Tabs in welcome page */
	function avante_welcome_page_tabs(event) {
		jQuery(event).parent().addClass("nav-tab-active");
        jQuery(event).parent().siblings().removeClass("nav-tab-active");
        var tab = jQuery(event).attr("href");
        jQuery(".avante-tab-pane").not(tab).css("display", "none");
        jQuery(tab).fadeIn();
	}
	
	var avante_actions_anchor = location.hash;
	
	if( (typeof avante_actions_anchor !== 'undefined') && (avante_actions_anchor != '') ) {
		avante_welcome_page_tabs('a[href="'+ avante_actions_anchor +'"]');
	}
	
    jQuery(".nav-tab-wrapper a").click(function(event) {
        event.preventDefault();
		avante_welcome_page_tabs(this);
    });

});