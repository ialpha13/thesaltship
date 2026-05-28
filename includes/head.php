<?php
require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? 'The Saltship | Himalayan Salt Manufacturer & Global Exporter';
$pageDescription = $pageDescription ?? 'FDA approved manufacturer and exporter of premium Himalayan salt products: lamps, tiles, edible salts, and animal licking blocks. Serving USA, Europe, and worldwide.';
$styles = $styles ?? [];
$currentPage = $currentPage ?? 'home';
$ogImage = $ogImage ?? base_url('assets/images/logo2.webp');
$headExtras = $headExtras ?? '';
$bodyClass = trim('bg-white text-gray-900 overflow-x-hidden page-' . preg_replace('/[^a-z0-9_-]/i', '', (string) $currentPage));
$canonicalUrl = $canonicalUrl ?? full_url($currentPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle) ?></title>
    <meta name="description" content="<?= h($pageDescription) ?>">
    <meta name="theme-color" content="#0f172a">
    <meta name="author" content="The Saltship">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= h($canonicalUrl) ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= h($pageTitle) ?>">
    <meta property="og:description" content="<?= h($pageDescription) ?>">
    <meta property="og:image" content="<?= h($ogImage) ?>">
    <meta property="og:site_name" content="The Saltship">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= h($pageTitle) ?>">
    <meta name="twitter:description" content="<?= h($pageDescription) ?>">
    <meta name="twitter:image" content="<?= h($ogImage) ?>">

    <meta name="base-url" content="<?= h(base_url('')) ?>">
    <base href="<?= h(base_url('')) ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= h(base_url('assets/images/logopakwest.webp')) ?>">
    <link rel="apple-touch-icon" href="<?= h(base_url('assets/images/logopakwest.webp')) ?>">

    <!-- Fonts & CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="<?= h(base_url('assets/css/global.css')) ?>">
    <link rel="stylesheet" href="<?= h(base_url('assets/css/components/whatsapp-chat.css')) ?>">
<?php foreach ($styles as $style): ?>
    <link rel="stylesheet" href="<?= h(base_url($style)) ?>">
<?php endforeach; ?>
    <link rel="stylesheet" href="<?= h(base_url('assets/css/theme-unify.css')) ?>">
<?php if ($headExtras !== ''): ?>
    <?= $headExtras . PHP_EOL ?>
<?php endif; ?>

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "The Saltship",
        "description": "FDA approved manufacturer and exporter of premium Himalayan salt products.",
        "url": "<?= h(base_url('')) ?>",
        "logo": "<?= h(base_url('assets/images/logopakwest.webp')) ?>",
        "contactPoint": {
            "@type": "ContactPoint",
            "email": "contact@thesaltship.com",
            "contactType": "sales"
        },
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Karachi Port Trust Area, Suite 405 Business Hub",
            "addressLocality": "Karachi",
            "addressCountry": "PK"
        },
        "sameAs": []
    }
    </script>
</head>
<body class="<?= h($bodyClass) ?>">

