document.addEventListener("DOMContentLoaded", function () {
    var footer = document.querySelector(".site-footer");
    if (!footer) return;

    var pagePath = window.location.pathname.toLowerCase();
    var links = footer.querySelectorAll(".footer-nav a");

    links.forEach(function (link) {
        var href = link.getAttribute("href");
        if (!href) return;

        var normalized = href.toLowerCase();
        var isHome = normalized.indexOf("home.php") !== -1 || normalized.endsWith("/index.php") || normalized.endsWith("index.php");
        var isActive = false;

        if (isHome) {
            isActive = pagePath.endsWith("/index.php") || pagePath.endsWith("/") || pagePath.endsWith("/home.php");
        } else {
            isActive = pagePath.indexOf(normalized.replace("../", "/")) !== -1;
        }

        if (isActive) {
            link.classList.add("is-active");
            link.setAttribute("aria-current", "page");
        }
    });

    var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (reduceMotion || !("IntersectionObserver" in window)) {
        footer.classList.add("is-visible");
        return;
    }

    var observer = new IntersectionObserver(function (entries, obs) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                footer.classList.add("is-visible");
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    observer.observe(footer);
});
