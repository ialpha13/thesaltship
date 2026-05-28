<?php
require_once __DIR__ . '/../../includes/functions.php';

http_response_code(500);
$errorRef = 'SS-' . date('Ymd-His') . '-' . strtoupper(substr((string) dechex(mt_rand(0, 0xFFFFFF)), 0, 6));
error_log('TheSaltship 500 Ref ' . $errorRef . ' URI: ' . ($_SERVER['REQUEST_URI'] ?? 'unknown'));
$pageTitle = '500 Server Error | The Saltship';
$pageDescription = 'Something went wrong on our side. Please try again shortly.';
$currentPage = 'error-500';
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
      <p class="error-code" data-error-reveal>500</p>
      <h1 class="hero-typewriter">Server Error</h1>
      <p class="error-copy" data-error-reveal>
        We hit an unexpected issue while loading this page. Please refresh or try again in a moment.
      </p>
      <p class="error-ref" data-error-reveal>Reference ID: <strong><?= h($errorRef) ?></strong></p>
      <div class="error-actions" data-error-reveal>
        <a class="error-btn" href="<?= h(page_url('home')) ?>">Back to Home</a>
        <a class="error-btn error-btn--ghost" href="<?= h(page_url('contact')) ?>">Report Issue</a>
      </div>
    </div>
  </section>
</main>
<?php
include __DIR__ . '/../../includes/footer.php';
include __DIR__ . '/../../includes/foot.php';
?>
