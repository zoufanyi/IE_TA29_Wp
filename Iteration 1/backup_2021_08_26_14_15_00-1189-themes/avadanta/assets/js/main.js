var NioApp = (function (a, e, s) {
    "use strict";
    var t = { AppInfo: { name: "avadanta", package: "", version: "", author: "avadantathemes" } },
        i = { docReady: [], docReadyDefer: [], winLoad: [], winLoadDefer: [] };


if(jQuery('a.zoom').length)
{
jQuery('a.zoom').colorbox({rel:'gal'});
} 

// var openButton = document.getElementById('open-dialog');
// var dialog = document.getElementById('dialog');
// var closeButton = document.getElementById('close-dialog');

// var keyHandle;
// var tabHandle;
// var disabledHandle;
// var hiddenHandle;
// var focusedElementBeforeDialogOpened;

// function openDialog() {
//   focusedElementBeforeDialogOpened = document.activeElement;

//   ally.when.visibleArea({
//     context: dialog,
//     callback: function(context) {
//       var element = ally.query.firstTabbable({
//         context: context, // context === dialog
//         defaultToContext: true,
//       });
//       element.focus();
//     },
//   });

//   disabledHandle = ally.maintain.disabled({
//     filter: dialog,
//   });

//   hiddenHandle = ally.maintain.hidden({
//     filter: dialog,
//   });

//   tabHandle = ally.maintain.tabFocus({
//     context: dialog,
//   });

//   keyHandle = ally.when.key({
//     escape: closeDialogByKey,
//   });

//   // Show the dialog
//   dialog.hidden = false;
// }

// function closeDialogByKey() {
  
//   setTimeout(closeDialog);
// }

//  function closeDialog() {
//    keyHandle.disengage();
//    tabHandle.disengage();
//    hiddenHandle.disengage();
//    disabledHandle.disengage();
//    focusedElementBeforeDialogOpened.focus();
//    dialog.hidden = true;
//  }



// // openButton.addEventListener('click', openDialog, false);
// // closeButton.addEventListener('click', closeDialog, false);


      function avadantaaccess() {
        jQuery( document ).on( 'keydown', function( e ) {
            if ( jQuery( window ).width() > 992 ) {
                return;
            }
            var activeElement = document.activeElement;
            var menuItems = jQuery( '#primary-menu .menu-item > a' );
            var firstEl = jQuery( '.menu-toggle' );
            var lastEl = menuItems[ menuItems.length - 1 ];
            var tabKey = event.keyCode === 9;
            var shiftKey = event.shiftKey;
            if ( ! shiftKey && tabKey && lastEl === activeElement ) {
                event.preventDefault();
                firstEl.focus();
            }
        } );
    }


     
     jQuery(document).ready(function() {
   
   
    jQuery(".cross").click(function(){
    jQuery('#modal').removeClass('open')
    });
   
 });


  jQuery(document).ready(function() {
  jQuery("a").on('click', function(e) {
    jQuery(this).css("outline", "none");
  });


    jQuery(document).ready(function () {
    avadantaaccess();
    });

});
// Show or hide the sticky footer button
jQuery(window).on('scroll', function(event) {
    if(jQuery(this).scrollTop() > 600){
        jQuery('.back-to-top').fadeIn(200)
    } else{
        jQuery('.back-to-top').fadeOut(200)
    }
});
    
    jQuery('.slider').slick({
        slidesToShow: 2,
    slidesToScroll: 1,
    dots: true,
    speed:1000,
    infinite: true,
    cssEase: 'linear',
     responsive: [
       
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 3
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 2
            }
        },
          {
            breakpoint: 992,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 2
            }
        }

  ],

});

//Animate the scroll to yop
jQuery('.back-to-top').on('click', function(event) {
    event.preventDefault();
    
    jQuery('html, body').animate({
        scrollTop: 0,
    }, 1500);
});
    function o(e) {
        (e = void 0 === e ? a : e),
            i.docReady.concat(i.docReadyDefer).forEach(function (a) {
                a(e);
            });
    }
    function n(e) {
        (e = "object" == typeof e ? a : e),
            i.winLoad.concat(i.winLoadDefer).forEach(function (a) {
                a(e);
            });
    }
    return a(s).ready(o), a(e).on("load", n), (t.components = i), (t.docReady = o), (t.winLoad = n), t;
})(jQuery, window, document);
NioApp = (function (f, b, d, n) {
    "use strict";
    var c = b(d),
        p = b(n),
        l = b("body"),
        h = b(".header-main"),
        a = b(".header-navbar-creative"),
        u = 992,
        m = "menu-mobile",
        s = "has-fixed",
        t = "is-shrink",
        e = d.location.href,
        i = h.innerHeight() - 2;
    e.split("#");
    (b.fn.exists = function () {
        return 0 < this.length;
    }),
        a.exists() && (u = 576),
        (f.Win = {}),
        (f.Win.height = b(d).height()),
        (f.Win.width = b(d).width()),
        (f.getStatus = {}),
        (f.getStatus.isRTL = !(!l.hasClass("has-rtl") && "rtl" !== l.attr("dir"))),
        (f.getStatus.isTouch = "ontouchstart" in n.documentElement),
        (f.getStatus.isMobile = !!navigator.userAgent.match(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Windows Phone|/i)),
        (f.getStatus.asMobile = f.Win.width < 768),
        c.on("resize", function () {
            (f.Win.height = b(d).height()), (f.Win.width = b(d).width()), (f.getStatus.asMobile = f.Win.width < 768);
        }),
        (f.Util = {}),
        (f.Util.classInit = function () {
            function a() {
                !0 === f.getStatus.asMobile ? l.addClass("as-mobile") : l.removeClass("as-mobile");
            }
            !0 === f.getStatus.isTouch ? l.addClass("has-touch") : l.addClass("no-touch"), a(), "rtl" === l.attr("dir") && (l.addClass("has-rtl"), (f.getStatus.isRTL = !0)), b(d).on("resize", a);
        }),
        f.components.docReady.push(f.Util.classInit),
        (f.Util.preLoader = function () {
            var a = b(".preloader"),
                e = b(".spinner");
            a.exists() && (l.addClass("page-loaded"), e.addClass("load-done"), a.delay(200).fadeOut(100));
        }),
        f.components.winLoad.push(f.Util.preLoader),
        (f.Util.backTop = function () {
            var a = b(".backtop");
            if (a.exists()) {
                800 < c.scrollTop() ? a.fadeIn("slow") : a.fadeOut("slow"),
                    a.on("click", function (a) {
                        b("body,html").stop().animate({ scrollTop: 0 }, 1500, "easeInOutExpo"), a.preventDefault();
                    });
            }
        }),
        f.components.docReady.push(f.Util.backTop),
        (f.Util.headerSearch = function () {
            var a = b(".header-search"),
                e = b(".search-trigger"),
                s = b("body");
            e.on("click", function (a) {
                a.preventDefault(), s.addClass("search-show");
            }),
                a.on("click", function (a) {
                    b(a.target).is(".input-search") || (s.hasClass("search-show") && s.removeClass("search-show"));
                });
        }),
        f.components.docReady.push(f.Util.headerSearch),
        (f.Util.browser = function () {
            var a = -1 !== navigator.userAgent.indexOf("Chrome") ? 1 : 0,
                e = -1 !== navigator.userAgent.indexOf("Firefox") ? 1 : 0,
                s = -1 !== navigator.userAgent.indexOf("Safari"),
                t = -1 !== navigator.userAgent.indexOf("MSIE") || n.documentMode ? 1 : 0,
                i = !t && !!d.StyleMedia,
                o = navigator.userAgent.indexOf("Opera") || navigator.userAgent.indexOf("OPR") ? 1 : 0;
            a ? l.addClass("chrome") : e ? l.addClass("firefox") : t ? l.addClass("ie") : i ? l.addClass("edge") : o ? l.addClass("opera") : s && l.addClass("safari");
        }),
        f.components.winLoad.push(f.Util.browser),
        (f.Util.headerSticky = function () {
            var e = b(".is-sticky"),
                a = {
                    hasFixed: function () {
                        if (e.exists()) {
                            var a = e.offset();
                            c.on("scroll", function () {
                                c.scrollTop() > a.top ? e.hasClass(s) || e.addClass(s) : e.hasClass(s) && e.removeClass(s);
                            });
                        }
                    },
                    hasShrink: function () {
                        e.hasClass(t) && (i = h.height() + 16 - 2);
                    },
                };
            a.hasFixed(),
                a.hasShrink(),
                c.on("resize", function () {
                    i = e.hasClass(t) ? h.height() + 16 - 2 : h.innerHeight() - 2;
                });
        }),
        f.components.docReady.push(f.Util.headerSticky),
        (f.Util.imageBG = function () {
            var a = b(".bg-image");
            a.exists() &&
                a.each(function () {
                    var a = b(this),
                        e = a.parent(),
                        s = a.data("overlay"),
                        t = a.data("opacity"),
                        i = a.children("img").attr("src"),
                        o = !(void 0 === s || !s) && s,
                        n = !(void 0 === t || !t) && t;
                    void 0 !== i &&
                        "" !== i &&
                        (e.hasClass("has-bg-image") || e.addClass("has-bg-image"),
                        o ? a.hasClass("overlay-" + o) || (a.addClass("overlay"), a.addClass("overlay-" + o)) : a.hasClass("overlay-fall") || a.addClass("overlay-fall"),
                        n && a.addClass("overlay-opacity-" + n),
                        a.css("background-image", 'url("' + i + '")').addClass("bg-image-loaded"));
                });
        }),
        f.components.docReady.push(f.Util.imageBG),
        
        (f.Util.sliderAnimation = function () {
            var a = b(".animate");
            a.exists() &&
                a.each(function () {
                    var a = b(this),
                        e = a.data("animate"),
                        s = a.data("duration"),
                        t = a.data("delay");
                    a.addClass(e), a.css({ visibility: "visible", "transition-duration": s + "s", "transition-delay": t + "s" });
                });
        }),
        f.components.winLoad.push(f.Util.sliderAnimation),
        (f.MainMenu = function () {
            var e = b(".navbar-toggle"),
                s = b(".header-navbar"),
                t = b(".header-navbar-classic"),
                i = b(".menu-toggle"),
                o = b(".menu-link"),
                n = ".menu-drop",
                l = "open-nav",
                r = "menu-shown",
                a = {
                    Overlay: function () {
                        s.exists() && s.append('<div class="header-navbar-overlay"></div>');
                    },
                    navToggle: function () {
                        e.exists() &&
                            e.on("click", function (a) {
                                var e = b(this),
                                    s = e.data("menu-toggle");
                                e.toggleClass("active"),
                                    t.exists()
                                        ? b("#" + s)
                                              .slideToggle()
                                              .toggleClass(r)
                                        : b("#" + s)
                                              .parent()
                                              .toggleClass(r),
                                    a.preventDefault();
                            });
                    },
                    navClose: function () {
                        b(".header-navbar-overlay").on("click", function () {
                            e.removeClass(""), s.removeClass(r);
                        }),
                            p.on("click", "body", function (a) {
                                !i.is(a.target) && 0 === i.has(a.target).length && !h.is(a.target) && 0 === h.has(a.target).length && c.width() < u && (e.removeClass("active"), t.find(".header-menu").slideUp(), s.removeClass(r));
                            });
                    },
                    menuToggle: function () {
                        i.exists() &&
                            i.on("click", function (a) {
                                var e = b(this),
                                    s = e.parent();
                                (c.width() < u || e.hasClass("menu-toggle-alt")) && (s.children(n).slideToggle(400), s.siblings().children(n).slideUp(400), s.toggleClass(l), s.siblings().removeClass(l)), a.preventDefault();
                            });
                    },
                    mobileNav: function () {
                        c.width() < u ? s.delay(500).addClass(m) : (s.delay(500).removeClass(m), e.removeClass("active"), s.removeClass(r));
                    },
                    currentPage: function () {
                        var a = d.location.href;
                        o.exists() &&
                            o.each(function () {
                                a === this.href && b(this).closest("li").addClass("active").parent().closest("li").addClass("active");
                            });
                    },
                };
            a.Overlay(),
                a.navToggle(),
                a.navClose(),
                a.menuToggle(),
                a.mobileNav(),
                a.currentPage(),
                c.on("resize", function () {
                    a.mobileNav();
                });
        }),
        f.components.docReady.push(f.MainMenu),

        (f.scrollAct = function () {
            l.scrollspy({ target: "#header-menu", offset: i + 2 });
        }),
        f.components.docReady.push(f.scrollAct),
        (f.Plugins = {}),
        (f.Plugins.carousel = function () {
            var a = b(".has-carousel");
            a.exists() &&
                a.each(function () {
                    var a = b(this),    
                        e = a.data("items") ? a.data("items") : 4,
                        s = a.data("items-tab-l") ? a.data("items-tab-l") : e - 1,
                        t = (a.data("items-tab-p") && a.data("items-tab-p"), a.data("items-mobile") ? a.data("items-mobile") : 1),
                        i = a.data("scroll") ? a.data("scroll") : 1,
                        o = a.data("delay") ? a.data("delay") : 6e3,
                        n = a.data("speed") ? a.data("speed") : 500,
                        l = a.data("ease") ? a.data("ease") : "linear",
                        r = !!a.data("auto") && a.data("auto"),
                        d = !!a.data("loop") && a.data("loop"),
                        c = !!a.data("dots") && a.data("dots"),
                        p = !!a.data("navs") && a.data("navs"),
                        h = !!a.data("center") && a.data("center"),
                        u = !!a.data("effect") && a.data("effect"),
                        m = !!a.data("varwidth") && a.data("varwidth"),
                        g = b(".slick-next"),
                        v = b(".slick-prev");
                    1 === e && (s = t = 1),
                        !0 === p && b(".tes-arrow").addClass("active"),
                        a.slick({
                            autoplay: r,
                            autoplaySpeed: o,
                            speed: n,
                            centerMode: h,
                            infinite: d,
                            dots: c,
                            swipeToSlide: !0,
                            arrows: p,
                            slidesToScroll: i,
                            cssEase: l,
                            variableWidth: m,
                            touchThreshold: 30,
                            mobileFirst: !0,
                            fade: u,
                            nextArrow: g,
                            prevArrow: v,
                            rtl: f.getStatus.isRTL,
                            responsive: [
                                { breakpoint: 1200, settings: { slidesToShow: e } },
                                { breakpoint: 992, settings: { slidesToShow: s } },
                                { breakpoint: 767, settings: { slidesToShow: s } },
                                { breakpoint: 575, settings: { slidesToShow: t } },
                            ],
                        });
                });
        }),
        f.components.winLoad.push(f.Plugins.carousel),
    

        (f.Plugins.videoBG = function () {
            var a = b(".bg-video");
            a.exists() &&
                a.each(function () {
                    var a = b(this),
                        e = a.parent(),
                        s = a.data("overlay"),
                        t = a.data("opacity"),
                        i = a.data("video"),
                        o = a.data("cover"),
                        n = !(void 0 === s || !s) && s,
                        l = !(void 0 === t || !t) && t,
                        r = !(void 0 === o || !o) && o;
                    e.hasClass("has-bg-video") || e.addClass("has-bg-video"),
                        n ? a.hasClass("overlay-" + n) || (a.addClass("overlay"), a.addClass("overlay-" + n)) : a.hasClass("overlay-fall") || a.addClass("overlay-fall"),
                        l && a.addClass("overlay-opacity-" + l),
                        r && f.getStatus.isTouch && f.getStatus.isMobile && (a.addClass("cover-enabled"), a.append('<div class="bg-video-cover" style="background-image:url(' + r + ')"></div>')),
                        a.hasClass("bg-video-youtube") &&
                            !a.hasClass("cover-enabled") &&
                            a.YTPlayer({
                                fitToBackground: !0,
                                videoId: i,
                                callback: function () {
                                    console.clear();
                                },
                            });
                });
        }),
        f.components.docReady.push(f.Plugins.videoBG),
        c.on("resize", function () {
            f.components.docReady.push(f.Plugins.videoBG);
        }),
     
        (f.colorPanel = function () {
            var i = b(".color-trigger");
            function o(a, i) {
                a.each(function () {
                    var a = b(this),
                        e = a.children("img").attr("src").split("/"),
                        s = e[e.length - 1].split("."),
                        t = "old" == i ? s[0] : "alt-" + s[0];
                    a.css("background-image", 'url("images/' + t + '.jpg")');
                });
            }
            0 < i.length &&
                i.on("click", function (a) {
                    var e = b(this),
                        s = e.attr("title"),
                        t = b(".change-bg");
                    if (e.hasClass("current")) return !1;
                    i.removeClass("current"),
                        e.addClass("current"),
                        b("body").fadeOut(function () {
                            e.hasClass("style-blue") || e.hasClass("style-purple") ? o(t, "new") : o(t, "old"), b("#color-sheet").attr("href", "assets/css/" + s + ".css"), b(this).delay(300).fadeIn();
                        }),
                        a.preventDefault();
                });
        }),
        f.components.winLoad.push(f.colorPanel);
})(NioApp, jQuery, window, document);
