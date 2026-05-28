<?php
require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? 'The Saltship | Himalayan Salt Manufacturer & Global Exporter';
$pageDescription = $pageDescription ?? 'The Saltship exports premium Himalayan salt products for decor, culinary, wellness and animal nutrition markets worldwide with trusted pricing, quality control and fast quote support.';
$styles = $styles ?? [];
$currentPage = $currentPage ?? 'home';
$ogImage = $ogImage ?? absolute_url('assets/images/logopakwest.webp');
$ogImageAlt = $ogImageAlt ?? 'The Saltship Himalayan Salt Products';
$ogType = $ogType ?? 'website';
$metaRobots = $metaRobots ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
$metaKeywords = $metaKeywords ?? 'Himalayan salt exporter, salt lamps, gourmet salt, wellness salt tiles, livestock salt blocks, bulk salt supplier';
$twitterSite = $twitterSite ?? '@thesaltship';
$siteLocale = $siteLocale ?? 'en_US';
$headExtras = $headExtras ?? '';
$bodyClass = trim('bg-white text-gray-900 overflow-x-hidden page-' . preg_replace('/[^a-z0-9_-]/i', '', (string) $currentPage));
$canonicalUrl = $canonicalUrl ?? full_url($currentPage);
$schemaData = $schemaData ?? null;

if (!is_array($schemaData)) {
    $schemaData = [
        [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => absolute_url('#organization'),
            'name' => 'The Saltship',
            'url' => absolute_url(''),
            'logo' => absolute_url('assets/images/logopakwest.webp'),
            'description' => 'The Saltship exports premium Himalayan salt products worldwide.',
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+923169396919',
                'email' => 'info@thesaltship.com',
                'contactType' => 'sales',
                'areaServed' => 'Worldwide',
            ],
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => absolute_url('#website'),
            'url' => absolute_url(''),
            'name' => 'The Saltship',
            'inLanguage' => 'en',
            'publisher' => ['@id' => absolute_url('#organization')],
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            '@id' => $canonicalUrl . '#webpage',
            'url' => $canonicalUrl,
            'name' => $pageTitle,
            'description' => $pageDescription,
            'isPartOf' => ['@id' => absolute_url('#website')],
            'inLanguage' => 'en',
        ],
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle) ?></title>
    <meta name="description" content="<?= h($pageDescription) ?>">
    <meta name="keywords" content="<?= h($metaKeywords) ?>">
    <meta name="theme-color" content="#0f172a">
    <meta name="author" content="The Saltship">
    <meta name="robots" content="<?= h($metaRobots) ?>">
    <link rel="canonical" href="<?= h($canonicalUrl) ?>">
    <link rel="alternate" hreflang="en" href="<?= h($canonicalUrl) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= h(absolute_url('')) ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="<?= h($ogType) ?>">
    <meta property="og:title" content="<?= h($pageTitle) ?>">
    <meta property="og:description" content="<?= h($pageDescription) ?>">
    <meta property="og:url" content="<?= h($canonicalUrl) ?>">
    <meta property="og:image" content="<?= h($ogImage) ?>">
    <meta property="og:image:alt" content="<?= h($ogImageAlt) ?>">
    <meta property="og:locale" content="<?= h($siteLocale) ?>">
    <meta property="og:site_name" content="The Saltship">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= h($pageTitle) ?>">
    <meta name="twitter:description" content="<?= h($pageDescription) ?>">
    <meta name="twitter:image" content="<?= h($ogImage) ?>">
    <meta name="twitter:url" content="<?= h($canonicalUrl) ?>">
    <meta name="twitter:site" content="<?= h($twitterSite) ?>">

    <meta name="base-url" content="<?= h(base_url('')) ?>">
    <base href="<?= h(base_url('')) ?>">

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="<?= h(base_url('assets/images/favicons/dark-background-alternative/favicon-dark.ico')) ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= h(base_url('assets/images/favicons/dark-background-alternative/favicon-dark-16x16.png')) ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= h(base_url('assets/images/favicons/dark-background-alternative/favicon-dark-32x32.png')) ?>">
    <link rel="icon" type="image/png" sizes="48x48" href="<?= h(base_url('assets/images/favicons/dark-background-alternative/favicon-dark-48x48.png')) ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= h(base_url('assets/images/favicons/dark-background-alternative/favicon-dark-96x96.png')) ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= h(base_url('assets/images/favicons/dark-background-alternative/favicon-dark-180x180.png')) ?>">
    <link rel="manifest" href="<?= h(base_url('assets/images/favicons/site.webmanifest')) ?>">
    <meta name="msapplication-TileColor" content="#0f172a">
    <meta name="msapplication-TileImage" content="<?= h(base_url('assets/images/favicons/mstile-150x150.png')) ?>">

    <!-- Fonts & CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="<?= h(base_url('assets/css/global.css')) ?>">
    <link rel="stylesheet" href="<?= h(base_url('assets/css/components/whatsapp-chat.css')) ?>">
<?php foreach ($styles as $style): ?>
<?php
    $stylePath = SITE_ROOT . '/' . ltrim((string) $style, '/');
    $styleUrl = base_url($style);
    if (is_file($stylePath)) {
        $styleUrl .= (strpos($styleUrl, '?') === false ? '?' : '&') . 'v=' . filemtime($stylePath);
    }
?>
    <link rel="stylesheet" href="<?= h($styleUrl) ?>">
<?php endforeach; ?>
    <link rel="stylesheet" href="<?= h(base_url('assets/css/theme-unify.css')) ?>">
<?php if ($headExtras !== ''): ?>
    <?= $headExtras . PHP_EOL ?>
<?php endif; ?>

    <!-- Structured Data -->
    <script type="application/ld+json">
<?= json_encode(['@graph' => array_values($schemaData)], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL ?>
    </script>
</head>
<body class="<?= h($bodyClass) ?>">
