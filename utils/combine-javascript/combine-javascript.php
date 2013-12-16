<?php
require_once(__DIR__ . '/../../library/runtime.php');
require_once(__DIR__ . '/../../library/build.php');

$basePath = realpath(__DIR__ . '/../../public/js');

$parser = new \core\typescript\Parser($basePath);
$dependencies = $parser->collectDependencies();

$content = '';
foreach ($dependencies as $dependency) {
    $content .= file_get_contents($basePath . '/' . $dependency);
}

file_put_contents($basePath . '/all.js', $content);