<?php
require_once __DIR__ . '/includes/functions.php';
$all = catalog_categories();
$paths = [];
foreach ($all as $c) {
  $paths[] = ['type' => 'category', 'name' => $c['title'] ?? '', 'path' => $c['img'] ?? ''];
  foreach (($c['products'] ?? []) as $p) {
    $paths[] = ['type' => 'product', 'name' => $p['title'] ?? '', 'path' => $p['image'] ?? ''];
  }
}
foreach ($paths as $row) {
  $p = trim((string)$row['path']);
  $ok = ($p !== '' && is_file(__DIR__ . '/' . ltrim($p, '/')));
  echo ($ok ? 'OK   ' : 'MISS ') . $row['type'] . ' | ' . $row['name'] . ' | ' . $p . PHP_EOL;
}
?>
