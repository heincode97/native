/*!
 *      Author: DigitalDots
	name: script.js
	requires: jquery
 */

/*!
 * StellarNav init + cleanup.
 * Instead of hiding the plugin's markup with CSS (!important),
 * we remove what we don't want and use our own elements.
 */
(function ($) {
  var BREAKPOINT = 991;
  var SUBMENU_BTN =
    '<button type="button" class="submenu-toggle" aria-label="Toggle submenu"></button>';

  function isMobile() {
    return window.matchMedia("(max-width: " + BREAKPOINT + "px)").matches;
  }

  // body.menu-open uses position:fixed to stop the page scrolling behind
  // the drawer. Fixing the body throws the page back to the top, so we
  // remember the scroll position, pin the body at that offset, and
  // restore the position when the menu closes.
  var savedScrollY = 0;

  function lockScroll() {
    if ($("body").hasClass("menu-open")) return;
    savedScrollY = window.scrollY || document.documentElement.scrollTop;
    $("body")
      .addClass("menu-open")
      .css("top", -savedScrollY + "px");
  }

  function unlockScroll() {
    if (!$("body").hasClass("menu-open")) return;
    $("body").removeClass("menu-open").css("top", "");
    window.scrollTo({ top: savedScrollY, left: 0, behavior: "instant" });
  }

  function closeMobileMenu() {
    $(".stellarnav .menu-toggle").removeClass("active");
    $(".stellarnav > ul").removeClass("mobile-menu-open");
    unlockScroll();
  }

  function cleanNav($nav) {
    // 1. Remove the plugin's own hamburger handler (it writes inline
    //    display styles on the <ul>). Our delegated handler below
    //    still works because it is bound on document.
    $nav.children("a.menu-toggle").off();

    // 2. Drop inline styles the plugin wrote on the <ul>
    $nav.children("ul").removeAttr("style");

    // 2b. The plugin also writes style="display: none" on every
    //     ul.sub-menu. Inline styles beat our CSS, so the mobile
    //     submenu could never open. Remove it; CSS controls the state.
    $nav.find("ul.sub-menu").removeAttr("style");

    // 3. Remove plugin .dd-toggle, add our own button per submenu parent
    $nav.find(".dd-toggle").remove();
    $nav.find("li.has-sub").each(function () {
      var $li = $(this);
      if (!$li.children(".submenu-toggle").length) {
        $li.children("a").first().after(SUBMENU_BTN);
      }
    });
  }

  $(document).ready(function () {
    $(".stellarnav").stellarNav({
      theme: "plain",
      breakpoint: BREAKPOINT,
      position: "right",
      menuLabel: "",
      closeBtn: false,
      showArrows: false,
      phoneBtn: false,
      locationBtn: false,
    });

    $(".stellarnav").each(function () {
      cleanNav($(this));
    });
  });

  // The plugin may re-render bits of itself on resize; re-clean (debounced)
  // and reset the mobile menu when moving up to desktop.
  var resizeTimer;
  $(window).on("resize", function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function () {
      $(".stellarnav").each(function () {
        cleanNav($(this));
      });
      if (!isMobile()) closeMobileMenu();
    }, 150);
  });

  /* Hamburger toggle */
  $(document).on("click", ".stellarnav .menu-toggle", function (e) {
    e.preventDefault();

    var $toggle = $(this);
    var $nav = $toggle.closest(".stellarnav");
    var $menu = $nav.children("ul").first();

    $toggle.toggleClass("active");
    $menu.toggleClass("mobile-menu-open");

    if ($menu.hasClass("mobile-menu-open")) {
      lockScroll();
    } else {
      unlockScroll();
    }
  });

  /* Close the panel when a top-level link is clicked */
  $(document).on(
    "click",
    ".stellarnav > ul.mobile-menu-open > li > a",
    closeMobileMenu
  );

  /* Submenu toggle: our own button */
  $(document).on("click", ".stellarnav .submenu-toggle", function (e) {
    e.preventDefault();
    e.stopPropagation();

    var $li = $(this).closest("li.has-sub");
    $li.toggleClass("open");
    $li.children("ul.sub-menu").removeAttr("style").toggleClass("open");
  });
})(jQuery);

/*!
 * Mobile menu logo injector — additive.
 * Uses the second logo (window.siteHeaderLogo.second) when one is set,
 * otherwise falls back to the main logo.
 */
(function ($) {
  $(window).on("load", function () {
    var logo = window.siteHeaderLogo;
    if (!logo) return;

    var drawerLogo = logo.second || logo.url;
    if (!drawerLogo) return;

    $(".stellarnav").each(function () {
      var $nav = $(this);
      var $ul = $nav.children("ul").first();
      if (!$ul.length) return;

      $nav.find(".mobile-menu-logo").remove();

      $ul.prepend(
        '<li class="mobile-menu-logo">' +
          '<a href="' + logo.link + '">' +
          '<img src="' + drawerLogo + '" alt="' + logo.alt + '">' +
          "</a>" +
          "</li>"
      );
    });
  });
})(jQuery);

/*!
 * Sticky header on scroll — additive.
 */
(function ($) {
  var $header = $(".site-header");
  var stickyThreshold = 10;
  var ticking = false;

  function updateStickyState() {
    // While the mobile menu is open the body is pinned (scrollY reads 0),
    // so keep the current sticky state instead of flipping it.
    if (document.body.classList.contains("menu-open")) {
      ticking = false;
      return;
    }

    var scrollTop = window.scrollY || document.documentElement.scrollTop;

    if (scrollTop > stickyThreshold) {
      $header.addClass("is-sticky");
    } else {
      $header.removeClass("is-sticky");
    }
    ticking = false;
  }

  $(document).ready(function () {
    updateStickyState();

    $(window).on("scroll", function () {
      if (!ticking) {
        window.requestAnimationFrame(updateStickyState);
        ticking = true;
      }
    });
  });
})(jQuery);

// Image Slider Section 3col per 2 rows
(function () {
  const PREV_ICON = 'https://nativeasiandynamism.dev.myanmarcafe.trade/app/uploads/2026/09/left-arrow.svg';
  const NEXT_ICON = 'https://nativeasiandynamism.dev.myanmarcafe.trade/app/uploads/2026/09/right-vector.svg';

  function replaceChevron(btn, src, alt) {
    if (!btn) return;
    if (btn.querySelector('img[data-custom-arrow]')) return;

    btn.querySelectorAll('svg, img').forEach(n => n.remove());
    const img = document.createElement('img');
    img.src = src;
    img.alt = alt;
    img.dataset.customArrow = '1';
    img.setAttribute('aria-hidden', 'true');
    btn.appendChild(img);
  }

  function setupWidget(container) {
    const el = container.querySelector('.elementor-image-carousel-wrapper');
    if (!el) return;

    const prevBtn = container.querySelector('.elementor-swiper-button-prev');
    const nextBtn = container.querySelector('.elementor-swiper-button-next');

    replaceChevron(prevBtn, PREV_ICON, 'Previous');
    replaceChevron(nextBtn, NEXT_ICON, 'Next');

    if (el.dataset.gridInit === '1') return;
    el.dataset.gridInit = '1';

    if (el.swiper) el.swiper.destroy(true, true);

    new Swiper(el, {
      slidesPerView: 3,
      slidesPerGroup: 3,
      grid: { rows: 2, fill: 'row' },
      spaceBetween: 20,
      loop: false,
      speed: 500,
      watchOverflow: true,
      navigation: {
        nextEl: nextBtn,
        prevEl: prevBtn,
      },
      breakpoints: {
        // 0px to 379px (very small mobile)
        0: {
          slidesPerView: 1,
          slidesPerGroup: 1,
          grid: { rows: 1 },
          spaceBetween: 10
        },
        // 380px to 479px (standard mobile portrait)
        380: {
          slidesPerView: 1,
          slidesPerGroup: 1,
          grid: { rows: 1 },
          spaceBetween: 15
        },
        // 480px to 767px (large mobile / landscape)
        480: {
          slidesPerView: 2,
          slidesPerGroup: 2,
          grid: { rows: 1 },
          spaceBetween: 15
        },
        // 768px to 1024px (tablet)
        768: {
          slidesPerView: 2,
          slidesPerGroup: 2,
          grid: { rows: 2 },
          spaceBetween: 20
        },
        // 1025px+ (desktop)
        1025: {
          slidesPerView: 3,
          slidesPerGroup: 3,
          grid: { rows: 2 },
          spaceBetween: 20
        }
      }
    });
  }

  function initAll(scope) {
    (scope || document)
      .querySelectorAll('.col-3-slider')
      .forEach(setupWidget);
  }

  if (window.elementorFrontend) {
    elementorFrontend.hooks.addAction(
      'frontend/element_ready/image-carousel.default',
      function ($scope) {
        const node = $scope[0] || $scope;
        const container = node.closest('.col-3-slider');
        if (container) setupWidget(container);
      }
    );
  }

  window.addEventListener('load', () => initAll(document));

  let t;
  const mo = new MutationObserver(() => {
    clearTimeout(t);
    t = setTimeout(() => initAll(document), 100);
  });
  window.addEventListener('load', () => {
    mo.observe(document.body, { childList: true, subtree: true });
  });
})();