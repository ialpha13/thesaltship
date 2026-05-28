<?php
require_once __DIR__ . '/../../includes/functions.php';

http_response_code(404);
$pageTitle = '404 Not Found | The Saltship';
$pageDescription = 'The page you requested could not be found.';
$currentPage = 'error-404';
$metaRobots = 'noindex, nofollow';
$forceSolid = true;
$canonicalUrl = full_url('home');

$styles = [
  'assets/css/navbar.css',
  'assets/css/footer.css',
  'assets/css/pages/error.css'
];

$scripts = [
  'assets/js/navbar.js',
  'assets/js/footer.js',
  'assets/js/error.js'
];

include __DIR__ . '/../../includes/head.php';
include __DIR__ . '/../../includes/navbar.php';
?>
<main class="error-page">
  <section class="error-hero" data-error-hero>
    <div class="error-shell">
      <p class="error-code" data-error-reveal>404</p>
      <h1 class="hero-typewriter">Page Not Found</h1>
      <p class="error-copy" data-error-reveal>
        The page you are looking for does not exist, may have been moved, or the link may be broken.
      </p>
      <div class="error-actions" data-error-reveal>
        <a class="error-btn" href="<?= h(page_url('home')) ?>">Back to Home</a>
        <a class="error-btn error-btn--ghost" href="<?= h(page_url('products')) ?>">Browse Products</a>
      </div>
    </div>
  </section>
</main>
<?php
include __DIR__ . '/../../includes/footer.php';
include __DIR__ . '/../../includes/foot.php';
?>
