<?php

$docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
$siteRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));

$baseUrl = '/';
if ($docRoot && $siteRoot && strpos($siteRoot, $docRoot) === 0) {
    $baseUrl = '/' . trim(str_replace($docRoot, '', $siteRoot), '/');
}
if ($baseUrl === '') {
    $baseUrl = '/';
}

define('SITE_ROOT', $siteRoot);
define('BASE_URL', rtrim($baseUrl, '/') . '/');
define('CANONICAL_ORIGIN', 'https://thesaltship.com');

define('APP_NAME', 'The Saltship');
