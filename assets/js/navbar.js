document.addEventListener("DOMContentLoaded", function () {
  var nav = document.querySelector("[data-navbar]");
  if (!nav) return;

  var desktopLinks = nav.querySelectorAll("[data-nav-desktop] [data-nav-link]");
  var mobileLinks = nav.querySelectorAll("[data-nav-mobile] [data-nav-link]");
  var toggle = nav.querySelector("[data-nav-toggle]");
  var overlay = nav.querySelector("[data-nav-overlay]");
  var mobileMenu = nav.querySelector("[data-nav-mobile]");

  var path = window.location.pathname.toLowerCase();
  var lastScrollY = window.scrollY || 0;
  var threshold = 10;
  var solidThreshold = 56;
  var mobileBreakpoint = 900;
  var isMenuOpen = false;

  function syncNavHeight() {
    var navHeight = Math.ceil(nav.getBoundingClientRect().height);
    document.documentElement.style.setProperty("--hero-navbar-height", navHeight + "px");
  }

  function markActive(linkSet) {
    linkSet.forEach(function (link) {
      var href = (link.getAttribute("href") || "").toLowerCase();
      if (!href) return;

      var isHome = href.indexOf("home.php") !== -1 || href.endsWith("index.php");
      var active =
        (isHome && (path.endsWith("/") || path.endsWith("/index.php") || path.endsWith("/home.php"))) ||
        (!isHome && path.indexOf(href.replace("../", "/")) !== -1);

      if (active) {
        link.classList.add("is-active");
        link.setAttribute("aria-current", "page");
      }
    });
  }

  function syncSurface() {
    var shouldSolid = (window.scrollY || 0) > solidThreshold || isMenuOpen;
    nav.classList.toggle("is-transparent", !shouldSolid);
  }

  function setMenu(open) {
    if (!toggle || !overlay || !mobileMenu) return;

    isMenuOpen = open;
    toggle.setAttribute("aria-expanded", open ? "true" : "false");
    toggle.setAttribute("aria-label", open ? "Close menu" : "Open menu");
    overlay.hidden = !open;
    mobileMenu.hidden = !open;
    document.body.style.overflow = open ? "hidden" : "";
    if (open) nav.classList.remove("is-hidden");
    syncSurface();
    syncNavHeight();
  }

  markActive(desktopLinks);
  markActive(mobileLinks);
  syncNavHeight();
  syncSurface();

  if (toggle && overlay && mobileMenu) {
    toggle.addEventListener("click", function () {
      setMenu(toggle.getAttribute("aria-expanded") !== "true");
    });

    overlay.addEventListener("click", function () {
      setMenu(false);
    });

    mobileLinks.forEach(function (link) {
      link.addEventListener("click", function () {
        setMenu(false);
      });
    });
  }

  window.addEventListener("scroll", function () {
    syncSurface();

    if (window.innerWidth <= mobileBreakpoint || isMenuOpen) {
      nav.classList.remove("is-hidden");
      lastScrollY = window.scrollY || 0;
      return;
    }

    var currentScrollY = window.scrollY || 0;

    if (currentScrollY <= 4) {
      nav.classList.remove("is-hidden");
      lastScrollY = currentScrollY;
      return;
    }

    if (currentScrollY > lastScrollY + threshold) {
      nav.classList.add("is-hidden");
    } else if (currentScrollY < lastScrollY - threshold) {
      nav.classList.remove("is-hidden");
    }

    lastScrollY = currentScrollY;
  }, { passive: true });

  window.addEventListener("resize", function () {
    syncNavHeight();
    if (window.innerWidth > mobileBreakpoint) {
      setMenu(false);
      nav.classList.remove("is-hidden");
    }
    syncSurface();
  });

  window.addEventListener("load", function () {
    syncNavHeight();
    syncSurface();
  });
});
