document.addEventListener('DOMContentLoaded', function () {
  var root = document.querySelector('.whatsapp-chat');
  if (!root) return;
  var isHomePage = document.body && document.body.classList.contains('page-home');
  var homeHero = isHomePage ? document.getElementById('hero') : null;

  var toggle = root.querySelector('.whatsapp-button');
  var panel = root.querySelector('.whatsapp-panel');
  var startChat = root.querySelector('.whatsapp-start-chat');

  function setOpen(next) {
    root.classList.toggle('is-open', next);
    if (toggle) {
      toggle.setAttribute('aria-expanded', next ? 'true' : 'false');
      toggle.setAttribute('aria-label', next ? 'Close WhatsApp chat' : 'Open WhatsApp chat');
    }
    if (panel) {
      panel.setAttribute('aria-hidden', next ? 'false' : 'true');
    }
  }

  if (toggle) {
    toggle.addEventListener('click', function () {
      setOpen(!root.classList.contains('is-open'));
    });
  }

  document.addEventListener('click', function (event) {
    if (!root.classList.contains('is-open')) return;
    if (root.contains(event.target)) return;
    setOpen(false);
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
      setOpen(false);
    }
  });

  if (startChat) {
    startChat.addEventListener('click', function () {
      setOpen(false);
    });
  }

  if (isHomePage && homeHero) {
    var ticking = false;
    var updateVisibility = function () {
      var rect = homeHero.getBoundingClientRect();
      var passedHero = rect.bottom <= 0;
      root.classList.toggle('is-home-hero-hidden', !passedHero);
      if (!passedHero) {
        setOpen(false);
      }
      ticking = false;
    };

    var onScroll = function () {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(updateVisibility);
    };

    updateVisibility();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
  }

  // show hint badge after a short delay if user has not opened panel
  window.setTimeout(function () {
    if (!root.classList.contains('is-open')) {
      root.classList.add('has-new-message');
    }
  }, 1800);
});
