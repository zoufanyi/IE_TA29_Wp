jQuery(document).ready(function($){


	$('body').on('click', '.avadanta-icon-list li', function(){
		var icon_class = $(this).find('i').attr('class');
		$(this).addClass('icon-active').siblings().removeClass('icon-active');
		$(this).parent('.avadanta-icon-list').prev('.avadanta-selected-icon').children('i').attr('class','').addClass(icon_class);
		$(this).parent('.avadanta-icon-list').next('input').val(icon_class).trigger('change');
		avadanta_refresh_repeater_values();
	});

	$('body').on('click', '.avadanta-selected-icon', function(){
		$(this).next().slideToggle();
	});

	/*Drag and drop to change order*/
	$(".avadanta-repeater-field-control-wrap").sortable({
		orientation: "vertical",
		update: function( event, ui ) {
			avadanta_refresh_repeater_values();
		}
	});



    
    /**
     * Section re-order
    */
    $('#tm-sections-reorder').sortable({
        cursor: 'move',
        update: function(event, ui) {
            var section_ids = '';
            $('#tm-sections-reorder li').css('cursor','default').each(function() {
                var section_id = jQuery(this).attr( 'data-section-name' );
                section_ids = section_ids + section_id + ',';
            });
            $('#shortui-order').val(section_ids);
            $('#shortui-order').trigger('change');
        }
    });


    //Scroll to section
    $('body').on('click', '#sub-accordion-panel-avadanta_homepage_settings .control-subsection .accordion-section-title', function(event) {
        var section_id = $(this).parent('.control-subsection').attr('id');
        scrollToSection( section_id );
    });

});