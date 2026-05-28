<?php
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Products | The Saltship';
$pageDescription = 'Browse our Himalayan and industrial salt products with category-wise listings and product variations.';
$currentPage = 'products';
$forceSolid = true;

$styles = [
  'assets/css/navbar.css',
  'assets/css/footer.css',
  'assets/css/pages/products.css'
];

$scripts = [
  'assets/js/navbar.js',
  'assets/js/footer.js',
  'assets/js/products.js'
];

$rawCategories = categories();
$rawProducts = products();

$categoryItems = $rawCategories['categories'] ?? [];
$productItems = $rawProducts['products'] ?? [];

$categoryMetaById = [];
foreach ($categoryItems as $categoryItem) {
  $categoryId = trim((string) ($categoryItem['id'] ?? ''));
  if ($categoryId === '') {
    continue;
  }
  $categoryMetaById[$categoryId] = [
    'id' => $categoryId,
    'slug' => slugify($categoryId),
    'title' => (string) ($categoryItem['title'] ?? $categoryId),
    'desc' => (string) ($categoryItem['desc'] ?? ''),
    'img' => (string) ($categoryItem['img'] ?? ''),
    'products' => [],
  ];
}

foreach ($productItems as $item) {
  $categoryId = trim((string) ($item['category'] ?? ''));
  if ($categoryId === '') {
    continue;
  }
  if (!isset($categoryMetaById[$categoryId])) {
    $categoryMetaById[$categoryId] = [
      'id' => $categoryId,
      'slug' => slugify($categoryId),
      'title' => $categoryId,
      'desc' => '',
      'img' => '',
      'products' => [],
    ];
  }
  $titleParts = split_product_title((string) ($item['title'] ?? ''));
  $baseTitle = (string) ($titleParts['base_title'] ?? '');
  $variationLabel = trim((string) ($titleParts['variation'] ?? ''));
  $productSlug = slugify($baseTitle !== '' ? $baseTitle : (string) ($item['title'] ?? ''));
  if ($productSlug === '') {
    continue;
  }
  if (!isset($categoryMetaById[$categoryId]['products'][$productSlug])) {
    $categoryMetaById[$categoryId]['products'][$productSlug] = [
      'slug' => $productSlug,
      'title' => $baseTitle !== '' ? $baseTitle : (string) ($item['title'] ?? ''),
      'category_id' => $categoryId,
      'category_slug' => $categoryMetaById[$categoryId]['slug'],
      'summary' => (string) ($item['desc'] ?? ''),
      'image' => (string) ($item['image'] ?? ''),
      'variations' => [],
    ];
  }
  $categoryMetaById[$categoryId]['products'][$productSlug]['variations'][] = [
    'id' => $item['id'] ?? null,
    'title' => (string) ($item['title'] ?? ''),
    'label' => $variationLabel !== '' ? $variationLabel : 'Standard',
    'description' => (string) ($item['desc'] ?? ''),
    'image' => (string) ($item['image'] ?? ''),
  ];
}

$catalogCategories = [];
foreach ($categoryMetaById as $category) {
  $category['products'] = array_values($category['products']);
  usort($category['products'], static function (array $a, array $b): int {
    return strnatcasecmp((string) ($a['title'] ?? ''), (string) ($b['title'] ?? ''));
  });

  foreach ($category['products'] as &$productRef) {
    $variations = $productRef['variations'] ?? [];
    usort($variations, static function (array $a, array $b): int {
      $aId = (int) ($a['id'] ?? 0);
      $bId = (int) ($b['id'] ?? 0);
      if ($aId !== 0 && $bId !== 0) {
        return $aId <=> $bId;
      }
      return strnatcasecmp((string) ($a['title'] ?? ''), (string) ($b['title'] ?? ''));
    });
    $productRef['variations'] = $variations;
  }
  unset($productRef);
  $catalogCategories[] = $category;
}
$categoryParam = isset($_GET['category']) ? trim((string) $_GET['category']) : '';
$productParam = isset($_GET['product']) ? trim((string) $_GET['product']) : '';

$activeCategory = null;
if ($categoryParam !== '') {
  $needle = slugify(rawurldecode($categoryParam));
  foreach ($catalogCategories as $category) {
    if (($category['slug'] ?? '') === $needle || strtolower((string) ($category['id'] ?? '')) === strtolower(rawurldecode($categoryParam))) {
      $activeCategory = $category;
      break;
    }
  }
}

$activeProduct = null;
if ($activeCategory !== null && $productParam !== '') {
  $productNeedle = slugify(rawurldecode($productParam));
  foreach (($activeCategory['products'] ?? []) as $product) {
    if (($product['slug'] ?? '') === $productNeedle) {
      $activeProduct = $product;
      break;
    }
  }
}

$view = 'catalog';
if ($categoryParam !== '') {
  $view = $activeCategory !== null ? 'category' : 'not-found';
}
if ($productParam !== '') {
  $view = ($activeCategory !== null && $activeProduct !== null) ? 'product' : 'not-found';
}
if ($view === 'not-found') {
  http_response_code(404);
}

$flatProducts = [];
foreach ($catalogCategories as $category) {
  foreach (($category['products'] ?? []) as $product) {
    $product['category_title'] = (string) ($category['title'] ?? '');
    $product['category_slug'] = (string) ($category['slug'] ?? '');
    $flatProducts[] = $product;
  }
}

if ($view === 'category' && $activeCategory !== null) {
  $pageTitle = (string) ($activeCategory['title'] ?? 'Category') . ' | Products | The Saltship';
  $pageDescription = (string) ($activeCategory['desc'] ?? $pageDescription);
}

if ($view === 'product' && $activeProduct !== null) {
  $pageTitle = (string) ($activeProduct['title'] ?? 'Product') . ' | The Saltship';
  $pageDescription = 'Explore all available sizes and variations for ' . (string) ($activeProduct['title'] ?? 'this product') . '.';
}

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
<main id="main-content" class="products-page" data-page-view="<?= h($view) ?>">
  <?php if ($view === 'catalog'): ?>
    <section class="products-hero products-hero--catalog hero-fx" data-hero-fx>
      <div class="container mx-auto px-6 lg:px-12">
        <span class="products-kicker hero-animate delay-100">Global Export Catalog</span>
        <h1 class="products-title hero-animate delay-200">Pure. Natural. Essential.</h1>
        <p class="products-copy hero-animate delay-300">Discover our wide range of premium salt products, sourced from trusted mines and crafted to meet global quality standards.</p>
      </div>
    </section>

    <section class="products-body container mx-auto px-6 lg:px-12">
      <div class="products-filterbar">
        <div class="products-filterchips" id="products-filterchips">
          <button type="button" class="products-chip is-active" data-filter="all">All Products</button>
          <?php foreach ($catalogCategories as $category): ?>
            <button type="button" class="products-chip" data-filter="<?= h((string) ($category['slug'] ?? '')) ?>">
              <?= h((string) ($category['title'] ?? 'Category')) ?>
            </button>
          <?php endforeach; ?>
        </div>
        <div class="products-searchwrap">
          <input id="products-search" type="search" placeholder="Search products...">
          <button type="button" class="products-filter-btn" aria-label="Filter products">Filter</button>
        </div>
      </div>

      <div class="products-top-benefits" aria-label="Service highlights">
        <article><span>&#9678;</span><div><h4>Premium Quality</h4><p>Tested &amp; Certified</p></div></article>
        <article><span>&#9678;</span><div><h4>Sustainable Practices</h4><p>Eco-friendly packaging</p></div></article>
        <article><span>&#9678;</span><div><h4>Secure Payments</h4><p>100% safe checkout</p></div></article>
        <article><span>&#9678;</span><div><h4>Fast &amp; Reliable</h4><p>Worldwide shipping</p></div></article>
      </div>

      <div class="products-grid" id="products-grid">
        <?php foreach ($flatProducts as $product): ?>
          <?php
            $imgPath = (string) ($product['image'] ?? (($product['variations'][0]['image'] ?? '') ?: ''));
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
            $categorySlug = (string) ($product['category_slug'] ?? '');
            ?>
          <article class="product-card" data-category="<?= h($categorySlug) ?>">
            <a class="product-card-hit" href="<?= h(product_detail_url((string) ($product['category_slug'] ?? ''), (string) ($product['slug'] ?? ''))) ?>" aria-label="View <?= h((string) ($product['title'] ?? 'Product')) ?>">
              <div class="product-card-image-wrap">
                <img class="product-card-image" src="<?= h($imgSrc) ?>" alt="<?= h((string) ($product['title'] ?? 'Product')) ?>" loading="lazy" decoding="async">
              </div>
              <div class="product-card-body">
                <h3 class="product-card-title"><?= h((string) ($product['title'] ?? '')) ?></h3>
                <p class="product-card-desc"><?= h((string) ($product['summary'] ?? '')) ?></p>
                <div class="product-card-footer">
                  <span class="product-card-link">View Details <span aria-hidden="true">-></span></span>
                </div>
              </div>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
      <p class="products-empty is-hidden" id="products-empty">No products match your search and selected category.</p>
      </section>

  <?php elseif ($view === 'category' && $activeCategory !== null): ?>
    <section class="products-hero products-hero--category hero-fx" data-hero-fx>
      <div class="container mx-auto px-6 lg:px-12">
        <span class="products-kicker hero-animate delay-100">Category</span>
        <h1 class="products-title hero-animate delay-200"><?= h((string) ($activeCategory['title'] ?? 'Category')) ?></h1>
        <p class="products-copy hero-animate delay-300"><?= h((string) ($activeCategory['desc'] ?? '')) ?></p>
      </div>
    </section>

    <section class="products-body container mx-auto px-6 lg:px-12">
      <div class="products-grid">
        <?php foreach (($activeCategory['products'] ?? []) as $product): ?>
          <?php
            $imgPath = (string) ($product['image'] ?? (($product['variations'][0]['image'] ?? '') ?: ''));
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
          ?>
          <article class="product-card">
            <a class="product-card-hit" href="<?= h(product_detail_url((string) ($activeCategory['slug'] ?? ''), (string) ($product['slug'] ?? ''))) ?>" aria-label="View <?= h((string) ($product['title'] ?? 'Product')) ?> variations">
              <div class="product-card-image-wrap">
                <img class="product-card-image" src="<?= h($imgSrc) ?>" alt="<?= h((string) ($product['title'] ?? 'Product')) ?>" loading="lazy" decoding="async">
              </div>
              <div class="product-card-body">
                <p class="product-card-meta"><?= h((string) ($activeCategory['title'] ?? '')) ?></p>
                <h2 class="product-card-title"><?= h((string) ($product['title'] ?? '')) ?></h2>
                <p class="product-card-desc"><?= h((string) ($product['summary'] ?? '')) ?></p>
                <div class="product-card-footer">
                  <span class="product-card-variations"><?= count($product['variations'] ?? []) ?> Variations</span>
                </div>
              </div>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

  <?php elseif ($view === 'product' && $activeCategory !== null && $activeProduct !== null): ?>
    <?php
      $heroImagePath = (string) ($activeProduct['image'] ?? (($activeProduct['variations'][0]['image'] ?? '') ?: ''));
      if ($heroImagePath === '') {
        $heroImagePath = 'assets/images/hero/herobackground4.webp';
      }
      if (strpos($heroImagePath, 'http') !== 0) {
        $checkPath = SITE_ROOT . '/' . ltrim($heroImagePath, '/');
        if (!is_file($checkPath)) {
          $heroImagePath = 'assets/images/hero/herobackground4.webp';
        }
      }
      $heroImageSrc = $resolveImageUrl($heroImagePath);
    ?>
    <section class="products-hero products-hero--product hero-fx" data-hero-fx>
      <div class="container mx-auto px-6 lg:px-12">
        <span class="products-kicker hero-animate delay-100"><?= h((string) ($activeCategory['title'] ?? 'Category')) ?></span>
        <h1 class="products-title hero-animate delay-200"><?= h((string) ($activeProduct['title'] ?? 'Product')) ?></h1>
        <p class="products-copy hero-animate delay-300">All available sizes and design variations are listed below for wholesale inquiry and custom packaging requests.</p>
      </div>
    </section>

    <section class="product-detail container mx-auto px-6 lg:px-12">
      <div class="product-detail-layout">
        <div class="product-detail-media">
          <img src="<?= h($heroImageSrc) ?>" alt="<?= h((string) ($activeProduct['title'] ?? 'Product')) ?>" loading="lazy" decoding="async">
        </div>
        <div class="product-detail-summary">
          <h2>Product Overview</h2>
          <p><?= h((string) ($activeProduct['summary'] ?? '')) ?></p>
          <div class="product-detail-stats">
            <div class="product-stat">
              <span class="product-stat-label">Category</span>
              <strong><?= h((string) ($activeCategory['title'] ?? '')) ?></strong>
            </div>
            <div class="product-stat">
              <span class="product-stat-label">Variations</span>
              <strong><?= count($activeProduct['variations'] ?? []) ?> Options</strong>
            </div>
          </div>
          <a class="product-detail-cta" href="<?= h(page_url('contact')) ?>">Request Wholesale Quote</a>
        </div>
      </div>

      <h2 class="variations-title">Available Variations</h2>
      <div class="variation-visuals" role="list" aria-label="Available variations">
        <?php foreach (($activeProduct['variations'] ?? []) as $variation): ?>
          <?php
            $variationImagePath = (string) ($variation['image'] ?? $heroImagePath);
            if ($variationImagePath === '') {
              $variationImagePath = $heroImagePath;
            }
            if (strpos($variationImagePath, 'http') !== 0) {
              $checkPath = SITE_ROOT . '/' . ltrim($variationImagePath, '/');
              if (!is_file($checkPath)) {
                $variationImagePath = $heroImagePath;
              }
            }
            $variationImageSrc = $resolveImageUrl($variationImagePath);
          ?>
          <article class="variation-visual" role="listitem">
            <img src="<?= h($variationImageSrc) ?>" alt="<?= h((string) ($variation['title'] ?? 'Variation')) ?>" loading="lazy" decoding="async">
            <div class="variation-visual-name"><?= h((string) ($variation['label'] ?? $variation['title'] ?? 'Variation')) ?></div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

  <?php else: ?>
    <section class="products-hero products-hero--notfound hero-fx" data-hero-fx>
      <div class="container mx-auto px-6 lg:px-12">
        <h1 class="products-title hero-animate delay-100">Product Not Found</h1>
        <p class="products-copy hero-animate delay-200">The requested category or product does not exist.</p>
        <a class="product-detail-cta hero-animate delay-300" href="<?= h(products_url()) ?>">Back to Products</a>
      </div>
    </section>
  <?php endif; ?>
</main>

<?php
include __DIR__ . '/../includes/footer.php';
include __DIR__ . '/../includes/foot.php';
?>


