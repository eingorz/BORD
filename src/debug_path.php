<?php
header('Content-Type: text/plain');

echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'Not set') . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'Not set') . "\n";

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$baseDir = dirname($scriptName);
$basePath = ($baseDir === '/' || $baseDir === '\\') ? '' : rtrim($baseDir, '/');

echo "\nCalculated BASE_URL: '$basePath'\n";
