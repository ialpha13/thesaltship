(() => {
  const CLOSE_ANIMATION_MS = 280;

  const init = () => {
    const root = document.querySelector('.whatsapp-chat');
    if (!root) return;

    const button = root.querySelector('.whatsapp-button');
    const panel = root.querySelector('.whatsapp-panel');
    if (!button || !panel) return;

    const setOpenState = (isOpen) => {
      if (isOpen) {
        panel.hidden = false;
        requestAnimationFrame(() => {
          root.classList.add('is-open');
        });
      } else {
        root.classList.remove('is-open');
        window.setTimeout(() => {
          if (!root.classList.contains('is-open')) {
            panel.hidden = true;
          }
        }, CLOSE_ANIMATION_MS);
      }

      button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      if (isOpen) {
        root.classList.remove('has-new-message');
      }
    };

    setOpenState(false);

    button.addEventListener('click', (event) => {
      event.preventDefault();
      setOpenState(!root.classList.contains('is-open'));
    });

    document.addEventListener('click', (event) => {
      if (!root.contains(event.target)) {
        setOpenState(false);
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        setOpenState(false);
      }
    });
  };

  if (window.APP && typeof window.APP.onReady === 'function') {
    window.APP.onReady(init);
  } else {
    document.addEventListener('DOMContentLoaded', init);
  }
})();
