document.addEventListener('DOMContentLoaded', function () {
  var revealItems = document.querySelectorAll('.reveal-on-scroll');
  var hero = document.querySelector('[data-categories-hero]');
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (!reduceMotion && revealItems.length) {
    if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(function (entries, obs) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            obs.unobserve(entry.target);
          }
        });
      }, {
        threshold: 0.16,
        rootMargin: '0px 0px -40px 0px'
      });

      revealItems.forEach(function (item) {
        observer.observe(item);
      });
    } else {
      revealItems.forEach(function (item) {
        item.classList.add('is-visible');
      });
    }
  } else {
    revealItems.forEach(function (item) {
      item.classList.add('is-visible');
    });
  }

  if (!reduceMotion && hero) {
    var ticking = false;

    var applyShift = function () {
      var rect = hero.getBoundingClientRect();
      var vh = window.innerHeight || document.documentElement.clientHeight;
      var progress = Math.max(-1, Math.min(1, (vh - rect.top) / (vh + rect.height)));
      var shift = (progress - 0.5) * 12;
      hero.style.transform = 'translate3d(0,' + shift.toFixed(2) + 'px,0)';
      ticking = false;
    };

    var onScroll = function () {
      if (!ticking) {
        window.requestAnimationFrame(applyShift);
        ticking = true;
      }
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    onScroll();
  }
});
