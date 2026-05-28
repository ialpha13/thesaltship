<?php
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/xml; charset=UTF-8');

$today = date('Y-m-d');
$urls = [];

$urls[] = [
  'loc' => full_url('home'),
  'changefreq' => 'weekly',
  'priority' => '1.0',
  'lastmod' => $today,
];
$urls[] = [
  'loc' => full_url('products'),
  'changefreq' => 'daily',
  'priority' => '0.9',
  'lastmod' => $today,
];
$urls[] = [
  'loc' => full_url('categories'),
  'changefreq' => 'weekly',
  'priority' => '0.8',
  'lastmod' => $today,
];
$urls[] = [
  'loc' => full_url('contact'),
  'changefreq' => 'monthly',
  'priority' => '0.7',
  'lastmod' => $today,
];

foreach (catalog_categories() as $category) {
  $categorySlug = (string) ($category['slug'] ?? '');
  if ($categorySlug === '') {
    continue;
  }

  $urls[] = [
    'loc' => full_url('products') . '?category=' . rawurlencode($categorySlug),
    'changefreq' => 'weekly',
    'priority' => '0.8',
    'lastmod' => $today,
  ];

  foreach (($category['products'] ?? []) as $product) {
    $productSlug = (string) ($product['slug'] ?? '');
    if ($productSlug === '') {
      continue;
    }

    $urls[] = [
      'loc' => full_url('products') . '?category=' . rawurlencode($categorySlug) . '&product=' . rawurlencode($productSlug),
      'changefreq' => 'weekly',
      'priority' => '0.7',
      'lastmod' => $today,
    ];
  }
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $url): ?>
  <url>
    <loc><?= h($url['loc']) ?></loc>
    <lastmod><?= h($url['lastmod']) ?></lastmod>
    <changefreq><?= h($url['changefreq']) ?></changefreq>
    <priority><?= h($url['priority']) ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
