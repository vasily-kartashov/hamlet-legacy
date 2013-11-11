<?php
require_once(__DIR__ . '/../../library/runtime.php');
require_once(__DIR__ . '/../../library/build.php');

$dirPath = realpath(__DIR__ . '/../../public/js');

$parser = new \core\typescript\Parser();
$dependencies = $parser->collectDependencies($dirPath);

$content = '';
foreach ($dependencies as $dependency) {
    $content .= file_get_contents($dirPath . '/' . $dependency);
}

file_put_contents($dirPath . '/all.js', $content);