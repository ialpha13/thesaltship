<?php
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Categories | The Saltship';
$pageDescription = 'Explore The Saltship categories for Himalayan salt lamps, culinary salts, wellness tiles and livestock mineral blocks for global wholesale buyers.';
$currentPage = 'categories';
$forceSolid = true;
$canonicalUrl = full_url('categories');
$schemaData = [
  [
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    '@id' => $canonicalUrl . '#webpage',
    'url' => $canonicalUrl,
    'name' => $pageTitle,
    'description' => $pageDescription,
    'inLanguage' => 'en',
  ],
  [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
      ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => full_url('home')],
      ['@type' => 'ListItem', 'position' => 2, 'name' => 'Categories', 'item' => $canonicalUrl],
    ],
  ],
];

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
    return base_url('assets/images/hero/pages/categories.webp');
  }
  if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
    return $path;
  }
  if (strpos($path, '/assets/') === 0) {
    return base_url(ltrim($path, '/'));
  }
  if (strpos($path, '/thesaltship/') === 0) {
    return base_url(ltrim(substr($path, strlen('/thesaltship/')), '/'));
  }
  return base_url(ltrim($path, '/'));
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
        <h1 class="hero-typewriter">Explore Our Collection</h1>
        <p>
          Curated export-ready salt collections for décor, wellness, culinary and livestock markets.
          Each category is designed to meet wholesale demand and custom brand packaging requirements.
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
                $imgPath = 'assets/images/hero/pages/categories.webp';
              }
              if (strpos($imgPath, 'http') !== 0) {
                $checkPath = SITE_ROOT . '/' . ltrim($imgPath, '/');
                if (!is_file($checkPath)) {
                  $imgPath = 'assets/images/hero/pages/categories.webp';
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
            <h4>Pure Himalayan Salt</h4>
            <p>Natural mineral content with no additives.</p>
          </div>
        </article>

        <article>
          <span>?</span>
          <div>
            <h4>Export-ready Quality</h4>
            <p>Packed and graded for global shipment.</p>
          </div>
        </article>

        <article>
          <span>?</span>
          <div>
            <h4>Designed for Business</h4>
            <p>Built to suit wholesale, private label and retail needs.</p>
          </div>
        </article>

        <article>
          <span>?</span>
          <div>
            <h4>Fast Response</h4>
            <p>Dedicated support for trade inquiries.</p>
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
