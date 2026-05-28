<header class="site-navbar site-navbar--home is-transparent" data-navbar>
  <div class="site-navbar__inner">
    <a href="<?= h(page_url('home')) ?>" aria-label="The Saltship Home" class="site-navbar__logo-link">
      <img src="<?= h(base_url('assets/images/logopakwest.webp')) ?>" alt="The Saltship" class="site-navbar__logo">
    </a>

    <nav class="site-navbar__links" aria-label="Main navigation" data-nav-desktop>
      <a href="<?= h(page_url('home')) ?>" data-nav-link>Home</a>
      <a href="<?= h(page_url('products')) ?>" data-nav-link>Products</a>
      <a href="<?= h(page_url('categories')) ?>" data-nav-link>Categories</a>
      <a href="<?= h(page_url('contact')) ?>" data-nav-link>Contact</a>
    </nav>

    <a href="<?= h(page_url('contact')) ?>" class="site-navbar__cta">Get Quote</a>

    <button class="site-navbar__toggle" data-nav-toggle type="button" aria-expanded="false" aria-controls="site-mobile-menu" aria-label="Open menu">
      <span></span><span></span><span></span>
    </button>
  </div>

  <div class="site-navbar__overlay" data-nav-overlay hidden></div>
  <nav class="site-navbar__mobile" id="site-mobile-menu" data-nav-mobile hidden aria-label="Mobile navigation">
    <a href="<?= h(page_url('home')) ?>" data-nav-link>Home</a>
    <a href="<?= h(page_url('products')) ?>" data-nav-link>Products</a>
    <a href="<?= h(page_url('categories')) ?>" data-nav-link>Categories</a>
    <a href="<?= h(page_url('contact')) ?>" data-nav-link>Contact</a>
    <a href="<?= h(page_url('contact')) ?>" class="site-navbar__mobile-cta">Get Quote</a>
  </nav>
</header>
