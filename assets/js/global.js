(() => {
  const doc = document;
  const baseMeta = doc.querySelector('meta[name="base-url"]');
  const baseFromMeta = baseMeta ? baseMeta.getAttribute('content') : '/';
  const base = (window.APP_BASE_URL || baseFromMeta || '/').replace(/\/+$/, '/') + '';

  window.APP = window.APP || {};
  window.APP.baseUrl = base;
  window.APP.buildUrl = (path = '') => base.replace(/\/+$/, '/') + String(path).replace(/^\//, '');
  window.APP.onReady = (fn) => {
    if (doc.readyState === 'loading') {
      doc.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  };

  // Throttle utility for performance-sensitive events
  window.APP.throttle = (fn, delay) => {
    let lastCall = 0;
    return (...args) => {
      const now = Date.now();
      if (now - lastCall >= delay) {
        lastCall = now;
        fn(...args);
      }
    };
  };

  window.APP.onReady(() => {
    requestAnimationFrame(() => {
      doc.body.classList.add('is-loaded');
    });

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const heroAnimNodes = Array.from(doc.querySelectorAll('.hero-animate'));
    const heroFxSections = Array.from(doc.querySelectorAll('[data-hero-fx]'));

    if (heroAnimNodes.length) {
      if (reduceMotion || !('IntersectionObserver' in window)) {
        heroAnimNodes.forEach((node) => node.classList.add('is-visible'));
      } else {
        const observer = new IntersectionObserver((entries, obs) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-visible');
              obs.unobserve(entry.target);
            }
          });
        }, { threshold: 0.18, rootMargin: '0px 0px -40px 0px' });

        heroAnimNodes.forEach((node) => observer.observe(node));
      }
    }

    if (!reduceMotion && heroFxSections.length) {
      let ticking = false;
      const clamp = (value, min, max) => Math.min(Math.max(value, min), max);

      const updateHeroParallax = () => {
        const viewport = window.innerHeight || doc.documentElement.clientHeight;
        heroFxSections.forEach((section) => {
          const rect = section.getBoundingClientRect();
          const progress = (viewport - rect.top) / (viewport + rect.height);
          const centered = clamp(progress, 0, 1) - 0.5;
          section.style.setProperty('--hero-fx-shift', `${centered * -16}px`);
        });
        ticking = false;
      };

      const requestTick = () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(updateHeroParallax);
      };

      updateHeroParallax();
      window.addEventListener('scroll', requestTick, { passive: true });
      window.addEventListener('resize', requestTick);
    } else if (heroFxSections.length) {
      heroFxSections.forEach((section) => {
        section.style.setProperty('--hero-fx-shift', '0px');
      });
    }
  });
})();
