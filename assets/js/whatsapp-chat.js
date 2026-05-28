document.addEventListener('DOMContentLoaded', function () {
  var root = document.querySelector('.whatsapp-chat');
  if (!root) return;

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

  // show hint badge after a short delay if user has not opened panel
  window.setTimeout(function () {
    if (!root.classList.contains('is-open')) {
      root.classList.add('has-new-message');
    }
  }, 1800);
});
