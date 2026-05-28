document.addEventListener('DOMContentLoaded', function () {
  var revealItems = document.querySelectorAll('.reveal-on-scroll');
  var hero = document.querySelector('[data-contact-hero]');
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  var typeNodes = document.querySelectorAll('.hero-typewriter');
  if (typeNodes.length) {
    typeNodes.forEach(function (node) {
      var fullText = (node.textContent || '').trim();
      if (!fullText || node.dataset.typewriterReady === '1') return;
      node.dataset.typewriterReady = '1';

      if (reduceMotion) {
        node.classList.add('is-typed');
        return;
      }

      var lockedHeight = node.getBoundingClientRect().height;
      if (lockedHeight > 0) {
        node.style.minHeight = lockedHeight + 'px';
      }

      node.textContent = '';
      var typedSpan = document.createElement('span');
      typedSpan.className = 'hero-typewriter__text is-typing';
      node.appendChild(typedSpan);

      var i = 0;
      var tick = function () {
        i += 1;
        typedSpan.textContent = fullText.slice(0, i);
        if (i < fullText.length) {
          window.setTimeout(tick, 38);
        } else {
          typedSpan.classList.remove('is-typing');
          typedSpan.classList.add('is-typed');
          node.classList.add('is-typed');
        }
      };

      window.setTimeout(tick, 260);
    });
  }

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
        threshold: 0.2,
        rootMargin: '0px 0px -50px 0px'
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
      var shift = (progress - 0.5) * 14;
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
