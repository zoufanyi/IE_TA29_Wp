(function($) {
    $(document).ready(function () {
        let body = 'body';
        let feedback_modal = '#ccb-feedback-modal';

        /**
         * Feedback Modal
         */
        set_stars();

        $(body).on('click', '.ccb-feedback-button', function (e) {
            e.preventDefault();
            $(feedback_modal).fadeIn(200);
        });

        $(body).on('click', '.feedback-modal-close', function (e) {
            e.preventDefault();
            $(feedback_modal).fadeOut(200);
        });

        $(body).on('click', function ( e ) {
            if ( e.target.id === 'ccb-feedback-modal' ) {
                $(feedback_modal).fadeOut(200);
            }
        });

        /**
         * Feedback Review
         */
        $(body).on('click', '#feedback-stars li', function (e) {
            var rating  = parseInt($(this).data('value'), 10),
                stars   = $(this).parent().children('li.star');

            stars.removeClass('selected');

            for ( i = 0; i < rating; i++ ) {
                $(stars[i]).addClass('selected');
            }

            set_stars();
            $('.feedback-rating-stars span.rating-text').text($(this).attr('title'));
            $('.feedback-extra').toggle(rating < 4);
            $('.feedback-submit img').toggle(rating > 3);
        });

        $(body).on('click', '.feedback-submit', function (e) {
            var rating  = parseInt($('ul#feedback-stars li.selected').last().data('value'), 10),
                review  = $('#feedback-review').val();

            /** Send Feedback */
            if ( rating < 4 ) {
                e.preventDefault();
                $.ajax({
                    url: 'https://panel.stylemixthemes.com/api/item-review',
                    dataType: 'json',
                    method: 'POST',
                    data: {
                        'item': 'cost-calculator-builder',
                        'type': 'plugin',
                        rating,
                        review
                    },
                    success: function(response) {}
                });
            }

            /** Thank You */
            $('ul#feedback-stars li').addClass('disabled').prop('disabled', true);
            $('#ccb-feedback-modal h2').text('Thank You for Feedback');
            $('.feedback-review-text').text(review);
            $('.feedback-review-text, .feedback-thank-you').show();
            $('.feedback-extra, .feedback-submit').hide();

            /** Remove Feedback Button */
            $.ajax({
                url: ajaxurl,
                type: 'GET',
                data: 'action=ccb_ajax_add_feedback&security=' + window.ccb_nonces.ccb_ajax_add_feedback,
                success: function (data) {
                    $('.ccb-feedback-button').remove();
                }
            });
        });
    });

    function set_stars() {
        $('ul#feedback-stars li i').css('background-image', 'url(' + ajax_window.plugin_url + '/frontend/dist/img/feedback-star.svg)');
        $('ul#feedback-stars li.selected i').css('background-image', 'url(' + ajax_window.plugin_url + '/frontend/dist/img/feedback-star-filled.svg)');
    }
})(jQuery);