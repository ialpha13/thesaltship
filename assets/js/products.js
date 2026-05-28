document.addEventListener('DOMContentLoaded', function () {
  var chips = Array.from(document.querySelectorAll('.products-chip'));
  var search = document.getElementById('products-search');
  var cards = Array.from(document.querySelectorAll('#products-grid .product-card'));
  var empty = document.getElementById('products-empty');

  if (!chips.length || !cards.length) return;

  var activeFilter = 'all';

  function applyFilters() {
    var query = (search ? search.value : '').trim().toLowerCase();
    var visibleCount = 0;

    cards.forEach(function (card) {
      var category = (card.getAttribute('data-category') || '').toLowerCase();
      var titleEl = card.querySelector('.product-card-title');
      var descEl = card.querySelector('.product-card-desc');
      var title = titleEl ? titleEl.textContent.toLowerCase() : '';
      var desc = descEl ? descEl.textContent.toLowerCase() : '';

      var matchesCategory = activeFilter === 'all' || category === activeFilter;
      var matchesQuery = query === '' || title.indexOf(query) !== -1 || desc.indexOf(query) !== -1;
      var show = matchesCategory && matchesQuery;

      card.classList.toggle('is-hidden', !show);
      if (show) visibleCount += 1;
    });

    if (empty) {
      empty.classList.toggle('is-hidden', visibleCount !== 0);
    }
  }

  chips.forEach(function (chip) {
    chip.addEventListener('click', function () {
      chips.forEach(function (btn) { btn.classList.remove('is-active'); });
      chip.classList.add('is-active');
      activeFilter = (chip.getAttribute('data-filter') || 'all').toLowerCase();
      applyFilters();
    });
  });

  if (search) {
    search.addEventListener('input', applyFilters);
  }

  applyFilters();
});
