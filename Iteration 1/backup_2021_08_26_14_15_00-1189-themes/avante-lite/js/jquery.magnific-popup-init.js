jQuery(document).ready(function($) {
    $('a[href*=".jpg"], a[href*=".jpeg"], a[href*=".png"], a[href*=".gif"]').each(function(){
    if ($(this).parents('.gallery').length == 0) {
        $(this).magnificPopup({
            type:'image',
            closeOnContentClick: true,
            });
        }
    });
    $('.gallery').each(function() {
    $(this).magnificPopup({
        delegate: 'a',
        type: 'image',
        gallery: {enabled: true}
        });
    });
    $('.popup-video').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    });
});