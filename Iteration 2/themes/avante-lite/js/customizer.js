/**
 * This file adds some LIVE to the Theme Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @see https://codex.wordpress.org/Theme_Customization_API#Part_3:_Configure_Live_Preview_.28Optional.29
 */
( function( $ ) {

  /*
   * Update benefits columns
   */
  wp.customize( 'avante_onepage_benefits_layout', function( value ) {
    value.bind( function( newval ) {
      var classData = {
        1: 'col-sm-12 col-md-12 col-lg-12',
        2: 'col-sm-12 col-md-6 col-lg-6',
        3: 'col-sm-12 col-md-6 col-lg-4',
        4: 'col-sm-12 col-md-6 col-lg-3',
        6: 'col-sm-12 col-md-6 col-lg-2',
      },
      $widgets = $( '.avante-benefit-widget' );
      $widgets.each(function() {
        var className = $(this).attr('class'),
            val = className.replace(/\s?col-[a-zA-Z0-9-]*\s?/g, ' ' + newval + ' ');
        $(this).attr( 'class', val );
      });
    });
  });

  /*
   * Update pricing columns
   */
  wp.customize( 'avante_onepage_pricing_layout', function( value ) {
    value.bind( function( newval ) {
      var classData = {
        1: 'col-sm-12 col-md-12 col-lg-12',
        2: 'col-sm-12 col-md-6 col-lg-6',
        3: 'col-sm-12 col-md-6 col-lg-4',
        4: 'col-sm-12 col-md-6 col-lg-3',
        6: 'col-sm-12 col-md-6 col-lg-2',
      },
      $widgets = $( '.avante-pricing-widget' );
      $widgets.each(function() {
        var className = $(this).attr('class'),
            val = className.replace(/\s?col-[a-zA-Z0-9-]*\s?/g, ' ' + newval + ' ');
        $(this).attr( 'class', val );
      });
    });
  });

  /*
   * Update blog columns
   */
  wp.customize( 'avante_onepage_blog_layout', function( value ) {
    value.bind( function( newval ) {
      var classData = {
        1: 'col-sm-12 col-md-12 col-lg-12',
        2: 'col-sm-12 col-md-6 col-lg-6',
        3: 'col-sm-12 col-md-6 col-lg-4',
        4: 'col-sm-12 col-md-6 col-lg-3',
        6: 'col-sm-12 col-md-6 col-lg-2',
      },
      $widgets = $( '.blog .content .post' );
      $widgets.each(function() {
      var className = $(this).attr('class'),
          val = className.replace(/\s?col-[a-zA-Z0-9-]*\s?/g, ' ' + newval + ' ');
      $(this).attr( 'class', val );
      });
    } );
  } );

  /*
   * Update featured section button
   */
  wp.customize( 'avante_onepage_featured_bg_color', function( value ) {
    value.bind( function( newval ) {
      $( '.featured' ).css('background-color', newval);
    } );
  } );

  wp.customize( 'avante_onepage_featured_button_url', function( value ) {
    value.bind( function( newval ) {
      $( '.featured .featured-button .btn' ).attr('href', newval);
    } );
  } );

  wp.customize( 'avante_onepage_featured_button_bg_color', function( value ) {
    value.bind( function( newval ) {
      $( '.featured .featured-button .btn' ).css('background-color', newval);
    } );
  } );

  wp.customize( 'avante_onepage_featured_image', function( value ) {
    value.bind( function( newval ) {
      $( '.featured .featured-image' ).css('background-image', 'url(' + newval + ')');
    } );
  } );

  /*
  var sections = ['hero', 'benefits', 'showcase', 'testimonials', 'pricing', 'featured'];
  $.map( sections, function( s ) {
      return wp.customize( 'avante_onepage_' + s + '_scheme', function( value ) {
        value.bind( function( newval ) {
          var $el = $( 'section.' + s );
          var className = $el.attr('class'),
              val = className.replace(/\s?text-(?:dark|light)/, '') + ' ' + newval;
          $el.attr( 'class', val );
        } );
      } );
  } );
  */

  wp.customize( 'avante_onepage_featured_layout', function( value ) {
    value.bind( function( newval ) {
      var $el = $( 'section.featured' );
      var className = $el.attr('class'),
          val = className.replace(/\s?layout-(?:left|right)/, '') + ' ' + newval;
      $el.attr( 'class', val );
    } );
  } );

  /*
   * Update testimonials carousel
   */
  wp.customize( 'avante_onepage_testimonials_layout', function( value ) {
    value.bind( function( newval ) {
      $( '.testimonials .slick-carousel' ).slick('slickSetOption', 'slidesToShow', newval, true);
    } );
  } );

  /*
   * Highlight the title dot
   */
  $( window ).load(function() {

    hasSelectiveRefresh = (
        'undefined' !== typeof wp &&
        wp.customize &&
        wp.customize.selectiveRefresh &&
        wp.customize.widgetsPreview &&
        wp.customize.widgetsPreview.WidgetPartial
    );

    if ( hasSelectiveRefresh ) {

      wp.customize.selectiveRefresh.bind('partial-content-rendered', function( data ) {
        var partial = data.partial;

        if ( partial.id === 'avante_hero_title1') {

          $('.hero .title').each(function(){
            var $self = $(this);

            var text = data.addedContent;
            var lastChar = text.split('').pop();

            if ( lastChar === '.' ) {
              var message = text.slice(0, -1);

              $self.html( message + '<span class="dot">' + lastChar + '</span>' ) ;
            }

          });
        }

      });

    }

  });

} )( jQuery );