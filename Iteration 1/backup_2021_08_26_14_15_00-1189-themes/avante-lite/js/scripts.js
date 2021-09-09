/*
 * Header
 */
 ;(function($){

  var num = 20; //number of pixels before modifying styles

  $(window).bind('scroll', function () {
      if ($(window).scrollTop() > num) {
          $('.top-navbar').addClass('fixed-menu');
          $('.navbar').removeClass('navbar-transparent');
          $('.navbar').addClass('navbar-default');
      } else {
          $('.top-navbar').removeClass('fixed-menu');
          $('.navbar').removeClass('navbar-default');
          $('.navbar').addClass('navbar-transparent');
      }
  });

})(jQuery);

/*
 * Mobile Tabbed Navigation
 */
(function($){

  // add all the elements inside modal which you want to make focusable
  const focusableElements = 'button, [href], b, input, select, textarea, [tabindex]:not([tabindex="-1"])';
  const modal = document.querySelector('#mobileNav'); // select the modal by it's id

  const firstFocusableElement = modal.querySelectorAll(focusableElements)[0]; // get first element to be focused inside modal
  const focusableContent = modal.querySelectorAll(focusableElements);
  const lastFocusableElement = focusableContent[focusableContent.length - 1]; // get last element to be focused inside modal


  document.addEventListener('keydown', function(e) {
    let isTabPressed = e.key === 'Tab' || e.keyCode === 9;

    if (!isTabPressed) {
      return;
    }

    if (e.shiftKey) { // if shift key pressed for shift + tab combination
      if (document.activeElement === firstFocusableElement) {
        document.querySelector('#mobileNavToggler').focus(); // add focus for the last focusable element
        e.preventDefault();
      }
    } else { // if tab key is pressed
      if (document.activeElement === lastFocusableElement) { // if focused has reached to last focusable element then focus first focusable element after pressing tab
        document.querySelector('#mobileNavToggler').focus(); // add focus for the first focusable element
        e.preventDefault();
      }
    }
  });

  firstFocusableElement.focus();

})(jQuery);

/*
 *_Debounced resize
 */
function debounce(func, wait, immediate) {
  var timeout;
  return function() {
    var context = this,
      args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) {
        func.apply(context, args);
      }
    };
    if (immediate && !timeout) {
      func.apply(context, args);
    }
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}


/*
 * Navbar Navigation
 */

(function($){

  function init_main_nav() {
      // main menu
      $('#primaryMenu').each(function(){
      var $items = $(this).children('.menu-item');

      $items.each(function(){
        hoverintent(this, function(){
          $items.not(this).filter('.open').removeClass('open');
          $(this).addClass('open');
        }, function() {
          $(this).filter('.open').removeClass('open');
        }).options({
          sensitivity: 6,
          interval: 50,
          timeout: 300,
        });
      });
    });
  }

  function init_mobile_nav(selector) {
    // mobile menu
    $('#mobileNavToggler').on('click', function () {
      refresh_slide_menu_position();
      $(this).toggleClass('is-active');
      $('#mobileNav').toggleClass('is-active');
      $('body').toggleClass('menu-active');
      $('#mobileNav input[name="s"]').focus();
    });

    $('#mobileNav .mobile-menu').each(function(){
      var $menuItems = $(this).find('.nav-item'),
          $dropdownItems = $menuItems.filter('.nav-item-has-children'),
          animSpeed = 400,
          easing = 'easeInOutQuad',
          itemConstruct = function($item) {
            $item.data('dropdown', $item.find('.dropdown-menu-wrapper'));
            $item.data('dropdownMenu', $item.find('.mobile-dropdown-menu').hide());
            $item.data('dropdownItems', $dropdownItems);

            $item.on('click', '.caret', function() {

              if (!$item.data('busy')) {

                $item.data('busy', true);
                closeAll($item);

                if ($item.data('open')) {
                  closeMenu($item);
                } else {
                  openMenu($item);
                }
              }

              return false;
            });

            $item.on('focusin', function() {

              if (!$item.data('busy')) {

                $item.data('busy', true);
                closeAll($item);

                if ($item.data('open')) {
                  //closeMenu($item);
                } else {
                  openMenu($item);
                }
              }

              return false;
            });

          },
          closeAll = function($item) {
            $item.data('dropdownItems').not($item).filter('.open').each(function(){
              closeMenu($(this));
            });
          },
          closeMenu = function($item) {
            $item.removeClass('open').data('dropdown').css('height', $item.data('dropdownMenu').outerHeight()).animate({
              'height': 0
            }, animSpeed, easing, function(){
              $item.removeData('open busy');
            });
            $item.data('dropdownMenu').fadeOut(animSpeed, easing, function(){
              $item.data('dropdown').height('auto');
            });
          },
          openMenu = function($item) {
            $item.addClass('open').data('dropdown').css('height', 0).show().animate({
              'height': $item.data('dropdownMenu').outerHeight()
            }, animSpeed, easing, function(){
              $item.data('open', true).removeData('busy');
            });
            $item.data('dropdownMenu').fadeIn(animSpeed, easing, function(){
              $item.data('dropdown').height('auto');
            });
          };

      $dropdownItems.each(function() {
        itemConstruct($(this));
      });
    });
  }

  function shrink_navbar() {
    var $navbarFixed = $('#navbar');

    // only for desktop
    $navbarFixed.toggleClass('shrink', $(window).scrollTop() > $navbarFixed.outerHeight());

    if ($(window).scrollTop() === 0) {
      setTimeout(function(){
        adjust_fixed_navbar();
      }, 300);
    }
  }

  function adjust_fixed_navbar() {
    var $navbarFixed = $('#navbar'),
        $adminbar = $('#wpadminbar'),
        $vc_editor = $('body.vc_editor'),
        navbarHeight = $navbarFixed.length ? $navbarFixed.outerHeight() : 0,
        adminbarHeight = ($adminbar.length && !$vc_editor.length) ? $adminbar.outerHeight() : 0;

    // positioning
    $navbarFixed.css({
      'top' : adminbarHeight,
    });

    $('#mobileNav').css({
      'padding-top': navbarHeight,
      'margin-top': adminbarHeight
    });

    $('#navbar + section:not(#hero), #navbar + section#hero .avante-hero-widget .item').css({
      'padding-top' : navbarHeight,
    });
  }

  function refresh_slide_menu_position () {
    var $navbarFixed = $('#navbar'),
        $adminbar = $('#wpadminbar'),
        $vc_editor = $('body.vc_editor'),
        navbarHeight = $navbarFixed.length ? $navbarFixed.outerHeight() : 0,
        adminbarHeight = ($adminbar.length && !$vc_editor.length) ? $adminbar.outerHeight() : 0;

    // refresh menu position
    $('#mobileNav').css({
      'padding-top': navbarHeight,
      'margin-top': adminbarHeight
    });
  }

  function init_slick_carousel() {
    var carousels = $(".slick-carousel");

    carousels.slick();

    $( window ).resize( debounce(function() {

      // re-initialize after "unslick"
      if ( $( window ).width() > 768  ) {
        carousels.not('.slick-initialized').slick();
      }

    }, 100) );

  }

  /*
   * Hero message dot custom color
   */
  function highlightTitleDot() {

    $('.hero .title').each(function(){
      var $self = $(this);

      var text = $self.text();
      var lastChar = text.split('').pop();

      if ( lastChar === '.' ) {
        var message = text.slice(0, -1);

        $self.html( message + '<span class="dot">' + lastChar + '</span>' ) ;
      }

    });

  }

  /*
   * Wrap price
   */
  function highlightPricing() {

    $('.pricing-table .price').each(function(){
      var $self = $(this);

      var text = $self.text();
      var regex = /[0-9]+/m;

      if ( false !== regex.test(text) ) {
        $self.html( text.replace(regex, '<b>$&</b>') );
      }

    });
  }

  // wait until users finishes resizing the browser
  var debouncedResize = debounce(function() {
    refresh_slide_menu_position();
    adjust_fixed_navbar();
  }, 100);

  // wait until users finishes scrolling the browser
  var debouncedScroll = debounce(function() {
    shrink_navbar();
  }, 10);

  var onloadCallback = function() {

    setTimeout(function(){

      $(window).scroll(debouncedScroll).trigger('scroll');
      $(window).resize(debouncedResize).trigger('resize');

    }, 10);
  };

  //window handlers
  $(window).load(onloadCallback);

  //main functions
  init_main_nav();
  init_mobile_nav();
  init_slick_carousel();
  highlightTitleDot();
  highlightPricing();

})(jQuery);