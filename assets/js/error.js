document.addEventListener('DOMContentLoaded', function () {
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var revealItems = document.querySelectorAll('[data-error-reveal]');
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

  if (!reduceMotion && revealItems.length && 'IntersectionObserver' in window) {
    var obs = new IntersectionObserver(function (entries, observer) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.25, rootMargin: '0px 0px -40px 0px' });

    revealItems.forEach(function (item) {
      obs.observe(item);
    });
  } else {
    revealItems.forEach(function (item) {
      item.classList.add('is-visible');
    });
  }
});
