<?php
require_once __DIR__ . '/../includes/functions.php';
$categoriesData = categories();
$productsData = products();

$allCategories = $categoriesData['categories'] ?? [];
$allProducts = $productsData['products'] ?? [];

$homeCategories = array_slice($allCategories, 0, 4);

$categorySlugMap = [];
foreach ($allCategories as $cat) {
    $catId = (string) ($cat['id'] ?? '');
    if ($catId === '') {
        continue;
    }
    $categorySlugMap[$catId] = slugify($catId);
}

$homeProducts = [];
$homeProductMap = [];
foreach ($allProducts as $prod) {
    $titleParts = split_product_title((string) ($prod['title'] ?? ''));
    $baseTitle = (string) ($titleParts['base_title'] ?? '');
    $title = $baseTitle !== '' ? $baseTitle : (string) ($prod['title'] ?? '');
    $categoryId = (string) ($prod['category'] ?? '');
    $categorySlug = $categorySlugMap[$categoryId] ?? slugify($categoryId);
    $productSlug = slugify($title);
    $dedupeKey = $categorySlug . '|' . $productSlug;

    if ($productSlug === '' || isset($homeProductMap[$dedupeKey])) {
        continue;
    }

    $homeProductMap[$dedupeKey] = true;
    $homeProducts[] = [
        'title' => $title,
        'summary' => (string) ($prod['desc'] ?? 'Premium quality salt product.'),
        'image' => (string) ($prod['image'] ?? ''),
        'slug' => $productSlug,
        'category_slug' => $categorySlug,
    ];
}
$homeProducts = array_slice($homeProducts, 0, 5);

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

$pageTitle = 'The Saltship | Home';
$pageDescription = 'Source premium edible and industrial salt with export-grade packing and dependable global shipment timelines.';
$currentPage = 'home';
$forceSolid = false;

$styles = [
    'assets/css/navbar.css',
    'assets/css/footer.css',
    'assets/css/home.css'
];

$scripts = [
    'assets/js/navbar.js',
    'assets/js/home.js',
    'assets/js/footer.js'
];

include __DIR__ . '/../includes/head.php';
?>
<main class="stage">
  <?php include __DIR__ . '/../includes/navbar.php'; ?>
  <div class="hero-divider" aria-hidden="true"></div>

  <section class="hero-card" id="hero">
    <div class="frame-shell" data-parallax-section>
      <div class="hero-body">
        <section class="hero-copy" aria-label="Hero message">
          <h1>TASTE THE PURITY<br>OF SALT SHIP</h1>
          <p id="hero-mode-subtext">
            Source premium edible and industrial salt with export-grade packing,
            dependable shipment timelines, and quality you can scale with confidence.
          </p>
          <div class="hero-cta-row">
            <a class="order-btn" href="/thesaltship/pages/contact.php">REQUEST QUOTE</a>
            <a class="order-btn order-btn--ghost" href="/thesaltship/pages/products.php">VIEW PRODUCTS</a>
          </div>
        </section>

        <section class="center-panel" aria-label="Product preview panel">
          <img id="hero-lamp" class="hero-lamp" src="/thesaltship/assets/images/hero/pinksaltlampoff.webp" alt="Lamp in off state">
          <p id="hero-mode-title" class="hero-mode-title">Pink Salt Lamp - Glow Preview</p>

          <div class="hero-variants" role="group" aria-label="Select hero product">
            <button type="button" class="hero-variant is-active" data-product="pinksaltlamp">
              <span class="hero-variant__thumb hero-variant__thumb--pink" aria-hidden="true"></span>
              <span>Pink Lamp</span>
            </button>
            <button type="button" class="hero-variant" data-product="whitesaltlamp">
              <span class="hero-variant__thumb hero-variant__thumb--white" aria-hidden="true"></span>
              <span>White Lamp</span>
            </button>
            <button type="button" class="hero-variant" data-product="saltcandle">
              <span class="hero-variant__thumb hero-variant__thumb--candle" aria-hidden="true"></span>
              <span>Salt Candle</span>
            </button>
          </div>

          <label class="lamp-switch" for="lamp-switch-input" aria-label="Lamp power switch">
            <input id="lamp-switch-input" class="lamp-switch__input" type="checkbox">
            <span class="lamp-switch__track"><span class="lamp-switch__thumb"></span></span>
            <span id="lamp-switch-label" class="lamp-switch__text">OFF</span>
          </label>
        </section>
      </div>
    </div>
  </section>

  <section class="home-categories" data-parallax-section>
    <div class="home-wrap">
      <header class="home-section-head">
        <span class="home-kicker">SHOP BY CATEGORY</span>
        <h2>Handcrafted Salt Categories</h2>
        <p>Discover premium salt ranges crafted for wellness, gifting, and everyday living.</p>
      </header>
      <div class="home-categories-static">
        <?php foreach ($homeCategories as $cat): ?>
          <?php
            $catId = (string) ($cat['id'] ?? '');
            $imgPath = (string) ($cat['img'] ?? '');
            if ($imgPath === '') {
              foreach ($allProducts as $productItem) {
                if ((string) ($productItem['category'] ?? '') === $catId && !empty($productItem['image'])) {
                  $imgPath = (string) $productItem['image'];
                  break;
                }
              }
            }
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
            $desc = (string) ($cat['desc'] ?? 'Explore this category.');
            $categorySlug = $categorySlugMap[$catId] ?? slugify($catId);
          ?>
          <article class="home-category-slide" data-parallax-item>
            <a class="home-category-slide__link" href="<?= h(category_products_url($categorySlug)) ?>" aria-label="Explore <?= h($title) ?>">
              <div class="home-category-slide__media">
                <img src="<?= h($imgSrc) ?>" alt="<?= h($title) ?>" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='/thesaltship/assets/images/hero/herobackground4.webp';">
              </div>
              <div class="home-category-slide__body">
                <span class="home-category-slide__kicker">Category</span>
                <h3><?= h($title) ?></h3>
                <p><?= h($desc) ?></p>
                <span class="home-category-slide__cta">Explore -></span>
              </div>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="home-products" data-parallax-section>
    <div class="home-wrap">
      <header class="home-section-head">
        <span class="home-kicker">OUR BESTSELLERS</span>
        <h2>Top Picks from The Saltship</h2>
        <span class="home-products__divider" aria-hidden="true"></span>
        <p>Loved by thousands for purity, quality and wellness benefits.</p>
      </header>
      <div class="home-products-grid">
        <?php foreach ($homeProducts as $prod): ?>
          <?php
            $imgPath = (string) ($prod['image'] ?? '');
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
            $title = (string) ($prod['title'] ?? 'Product');
          ?>
          <article class="product-card home-product-card-shell" data-parallax-item>
            <a class="product-card-hit" href="<?= h(product_detail_url((string) ($prod['category_slug'] ?? ''), (string) ($prod['slug'] ?? ''))) ?>" aria-label="View <?= h($title) ?>">
              <div class="product-card-image-wrap">
                <img class="product-card-image" src="<?= h($imgSrc) ?>" alt="<?= h($title) ?>" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='/thesaltship/assets/images/hero/herobackground4.webp';">
              </div>
              <div class="product-card-body">
                <h3 class="product-card-title"><?= h($title) ?></h3>
                <div class="product-card-footer">
                  <span class="home-product-add">View Details</span>
                </div>
              </div>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="home-quote" id="home-quote">
    <div class="home-wrap">
      <div class="home-quote__panel">
        <aside class="home-quote__side">
          <span class="home-kicker">GET A CUSTOM QUOTE</span>
          <h2>TELL US YOUR<br>REQUIREMENT</h2>
          <p>Share your quantity, product type and delivery details. Our team will get back to you shortly.</p>
        </aside>
        <form class="home-quote__form" action="/thesaltship/pages/contact.php" method="post">
          <input type="hidden" name="form_type" value="home_quote">
          <label>
            <span>Full Name *</span>
            <input type="text" name="fullName" placeholder="Your full name" required>
          </label>
          <label>
            <span>Email Address *</span>
            <input type="email" name="email" placeholder="name@example.com" required>
          </label>
          <label>
            <span>Phone Number</span>
            <input type="text" name="phone" placeholder="(+92) Enter your number">
          </label>
          <label>
            <span>Purpose of Enquiry *</span>
            <input type="text" name="purpose" placeholder="Describe your enquiry" required>
          </label>
          <label class="home-quote__form-full">
            <span>Message *</span>
            <textarea name="message" rows="4" placeholder="Share details about your requirement" required></textarea>
          </label>
          <button type="submit">SEND QUOTE REQUEST</button>
        </form>
      </div>
    </div>
  </section>

  <?php include __DIR__ . '/../includes/footer.php'; ?>
</main>

<?php include __DIR__ . '/../includes/foot.php'; ?>
