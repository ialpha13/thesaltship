<?php
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Categories | The Saltship';
$pageDescription = 'Explore our premium salt product categories for global wholesale and industrial requirements.';
$currentPage = 'categories';
$forceSolid = true;

$styles = [
  'assets/css/navbar.css',
  'assets/css/footer.css',
  'assets/css/pages/categories.css'
];

$scripts = [
  'assets/js/navbar.js',
  'assets/js/footer.js',
  'assets/js/categories.js'
];

$categoriesData = categories();
$segments = $categoriesData['categories'] ?? [];
$resolveImageUrl = static function (string $path): string {
  $path = trim($path);
  if ($path === '') {
    return '/thesaltship/assets/images/hero/herobackground4.webp';
  }
  if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
    return $path;
  }
  if (strpos($path, '/thesaltship/') === 0) {
    return $path;
  }
  if (strpos($path, '/assets/') === 0) {
    return '/thesaltship' . $path;
  }
  return '/thesaltship/' . ltrim($path, '/');
};

include __DIR__ . '/../includes/head.php';
include __DIR__ . '/../includes/navbar.php';
?>

<main class="categories-page">
  <section class="categories-hero" data-categories-hero>
    <div class="categories-hero__orb categories-hero__orb--left" aria-hidden="true"></div>
    <div class="categories-hero__orb categories-hero__orb--right" aria-hidden="true"></div>

    <div class="categories-shell categories-hero__shell">
      <div class="categories-hero__panel reveal-on-scroll">
        <h1>Discover Our Salt Categories</h1>
        <p>
          Curated product categories for wellness, decor, gifting, and culinary use.
          Built for wholesale buyers and private-label brands.
        </p>
      </div>
    </div>
  </section>

  <section class="categories-main">
    <div class="categories-shell">
      <header class="categories-main__head reveal-on-scroll">
        <p>Shop by Category</p>
        <h2>Choose Your Product Line</h2>
      </header>

      <?php if (empty($segments)): ?>
        <p class="categories-empty">Product categories are currently being updated.</p>
      <?php else: ?>
        <div class="categories-grid">
          <?php foreach ($segments as $cat): ?>
            <?php
              $imgPath = (string) ($cat['img'] ?? '');
              if ($imgPath === '') {
                $imgPath = 'assets/images/hero/herobackground4.webp';
              }
              if (strpos($imgPath, 'http') !== 0) {
                $checkPath = SITE_ROOT . '/' . ltrim($imgPath, '/');
                if (!is_file($checkPath)) {
                  $imgPath = 'assets/images/hero/herobackground4.webp';
                }
              }
              $imgSrc = $resolveImageUrl($imgPath);
              $title = (string) ($cat['title'] ?? 'Category');
              $desc = (string) ($cat['desc'] ?? 'Explore this category for premium salt products.');
              $slug = (string) ($cat['slug'] ?? slugify((string) ($cat['id'] ?? $title)));
              $iconMap = [
                'salt-lamps' => '&#128161;',
                'candles' => '&#128367;',
                'bath-body' => '&#128703;',
                'soaps' => '&#129532;',
                'massage-stones' => '&#128170;',
                'cooking-salt' => '&#127859;',
                'wellness-therapy' => '&#10024;',
                'gift-sets' => '&#127873;',
              ];
              $icon = $iconMap[$slug] ?? '&#10022;';
            ?>
            <article class="category-card reveal-on-scroll">
              <a class="category-card__link" href="<?= h(category_products_url($slug)) ?>" aria-label="View <?= h($title) ?> products">
                <div class="category-card__media">
                  <img src="<?= h($imgSrc) ?>" alt="<?= h($title) ?>" loading="lazy">
                </div>

                <div class="category-card__icon">
                  <span aria-hidden="true"><?= $icon ?></span>
                </div>

                <div class="category-card__body">
                  <h3><?= h($title) ?></h3>
                  <p><?= h($desc) ?></p>
                  <span class="category-card__cta">Explore <span aria-hidden="true">-></span></span>
                </div>
              </a>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div class="categories-trust reveal-on-scroll">
        <article>
          <span>?</span>
          <div>
            <h4>100% Natural</h4>
            <p>No additives or chemicals</p>
          </div>
        </article>

        <article>
          <span>?</span>
          <div>
            <h4>Sourced from Pure Mines</h4>
            <p>Ethically sourced, premium quality</p>
          </div>
        </article>

        <article>
          <span>?</span>
          <div>
            <h4>Handcrafted with Care</h4>
            <p>Made with love and expertise</p>
          </div>
        </article>

        <article>
          <span>?</span>
          <div>
            <h4>Trusted by Thousands</h4>
            <p>Quality you can rely on</p>
          </div>
        </article>
      </div>
    </div>
  </section>
</main>

<?php
include __DIR__ . '/../includes/footer.php';
include __DIR__ . '/../includes/foot.php';
?>
