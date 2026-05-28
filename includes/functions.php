<?php


require_once __DIR__ . '/config.php';

function base_url(string $path = ''): string
{
    $base = rtrim(BASE_URL, '/');
    if ($base === '') {
        $base = '/';
    }
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

function full_url(string $pageId = 'home'): string
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $protocol . '://' . $host . page_url($pageId);
}

function load_json(string $relativePath): array
{
    $path = SITE_ROOT . '/' . ltrim($relativePath, '/');
    if (!is_file($path)) {
        return [];
    }
    $raw = file_get_contents($path);
    if ($raw === false) {
        return [];
    }
    // Strip UTF-8 BOM if present to avoid json_decode failures.
    $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function nav_items(): array
{
    static $items = null;
    if ($items === null) {
        $items = load_json('assets/data/nav.json');
    }
    return $items;
}

function categories(): array
{
    static $items = null;
    if ($items === null) {
        $items = load_json('assets/data/categories.json');
    }
    return $items;
}

function products(): array
{
    static $items = null;
    if ($items === null) {
        $items = load_json('assets/data/products.json');
    }
    return $items;
}

function page_url(string $id): string
{
    if ($id === 'home') {
        return base_url('index.php');
    }
    return base_url('pages/' . $id . '.php');
}

function products_url(?string $category = null): string
{
    if ($category === null || $category === '' || $category === 'All') {
        return page_url('products');
    }
    return page_url('products') . '?category=' . rawurlencode(slugify($category));
}

function category_products_url(string $categorySlug): string
{
    return page_url('products') . '?category=' . rawurlencode($categorySlug);
}

function product_detail_url(string $categorySlug, string $productSlug): string
{
    return page_url('products')
        . '?category=' . rawurlencode($categorySlug)
        . '&product=' . rawurlencode($productSlug);
}

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function product_count(string $category): int
{
    $data = products();
    $all = $data['products'] ?? [];
    if ($category === 'All') {
        return count($all);
    }
    return count(array_filter($all, fn($p) => ($p['category'] ?? '') === $category));
}

function slugify(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    if (function_exists('iconv')) {
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        if (is_string($converted) && $converted !== '') {
            $value = $converted;
        }
    }

    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '-', $value);
    return trim((string) $value, '-');
}

function normalize_measurement_text(string $value): string
{
    $value = str_replace(["\xc3\x97", "Ã—"], 'x', $value);
    $value = preg_replace('/\s*x\s*/i', ' x ', $value);
    $value = preg_replace('/\s+/', ' ', $value);
    return trim((string) $value);
}

function split_product_title(string $title): array
{
    $title = normalize_measurement_text(trim($title));
    $patterns = [
        '/^(.*?)\s+(\d+(?:\.\d+)?\s*-\s*\d+(?:\.\d+)?\s*(?:kg|mm|cm))$/i',
        '/^(.*?)\s+(\d+\s*x\s*\d+\s*x\s*\d+(?:\.\d+)?\s*cm)$/i',
        '/^(.*?)\s+(fine grain)$/i',
        '/^(.*?)\s+(coarse|medium|fine)$/i',
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $title, $matches)) {
            return [
                'base_title' => trim($matches[1]),
                'variation' => strtoupper(substr($matches[2], 0, 1)) . substr($matches[2], 1),
            ];
        }
    }

    return [
        'base_title' => $title,
        'variation' => '',
    ];
}

function catalog_data(): array
{
    static $catalog = null;
    if ($catalog !== null) {
        return $catalog;
    }

    $categoryData = categories();
    $categoryItems = $categoryData['categories'] ?? [];
    $categoryMap = [];

    foreach ($categoryItems as $item) {
        $id = trim((string) ($item['id'] ?? ''));
        if ($id === '') {
            continue;
        }
        $categoryMap[$id] = [
            'id' => $id,
            'slug' => slugify($id),
            'title' => (string) ($item['title'] ?? $id),
            'desc' => (string) ($item['desc'] ?? ''),
            'img' => (string) ($item['img'] ?? ''),
            'featured' => !empty($item['featured']),
            'products' => [],
            'product_count' => 0,
            'variation_count' => 0,
        ];
    }

    $data = products();
    $items = $data['products'] ?? [];

    foreach ($items as $item) {
        $categoryId = (string) ($item['category'] ?? '');
        if ($categoryId === '') {
            continue;
        }

        if (!isset($categoryMap[$categoryId])) {
            $categoryMap[$categoryId] = [
                'id' => $categoryId,
                'slug' => slugify($categoryId),
                'title' => $categoryId,
                'desc' => '',
                'img' => '',
                'featured' => false,
                'products' => [],
                'product_count' => 0,
                'variation_count' => 0,
            ];
        }

        $titleParts = split_product_title((string) ($item['title'] ?? ''));
        $baseTitle = $titleParts['base_title'];
        $variationLabel = $titleParts['variation'];
        $productSlug = slugify($baseTitle);

        if (!isset($categoryMap[$categoryId]['products'][$productSlug])) {
            $categoryMap[$categoryId]['products'][$productSlug] = [
                'slug' => $productSlug,
                'title' => $baseTitle,
                'category_id' => $categoryId,
                'category_slug' => $categoryMap[$categoryId]['slug'],
                'summary' => (string) ($item['desc'] ?? ''),
                'image' => (string) ($item['image'] ?? ''),
                'variations' => [],
            ];
        }

        $categoryMap[$categoryId]['products'][$productSlug]['variations'][] = [
            'id' => $item['id'] ?? null,
            'title' => (string) ($item['title'] ?? ''),
            'label' => $variationLabel !== '' ? $variationLabel : 'Standard',
            'description' => (string) ($item['desc'] ?? ''),
            'image' => (string) ($item['image'] ?? ''),
        ];
    }

    foreach ($categoryMap as &$category) {
        $category['products'] = array_values($category['products']);
        usort($category['products'], fn($a, $b) => strcmp($a['title'], $b['title']));
        $category['product_count'] = count($category['products']);

        $variationCount = 0;
        foreach ($category['products'] as $product) {
            $variationCount += count($product['variations']);
        }
        $category['variation_count'] = $variationCount;
    }
    unset($category);

    $catalog = ['categories' => array_values($categoryMap)];
    return $catalog;
}

function catalog_categories(): array
{
    $catalog = catalog_data();
    return $catalog['categories'] ?? [];
}

function find_category_by_slug(string $categorySlug): ?array
{
    foreach (catalog_categories() as $category) {
        if (($category['slug'] ?? '') === $categorySlug) {
            return $category;
        }
    }
    return null;
}

function find_category_by_identifier(string $value): ?array
{
    $needle = trim($value);
    if ($needle === '') {
        return null;
    }

    $needleSlug = slugify($needle);
    foreach (catalog_categories() as $category) {
        if (($category['slug'] ?? '') === $needleSlug || strtolower((string) ($category['id'] ?? '')) === strtolower($needle)) {
            return $category;
        }
    }
    return null;
}

function find_product_in_category(string $categorySlug, string $productSlug): ?array
{
    $category = find_category_by_slug($categorySlug);
    if ($category === null) {
        return null;
    }

    foreach ($category['products'] as $product) {
        if (($product['slug'] ?? '') === $productSlug) {
            return $product;
        }
    }

    return null;
}
