<?php
require_once(__DIR__ . '/../../library/runtime.php');
require_once(__DIR__ . '/../../library/test.php');

$root = realpath(__DIR__ . '/../../library/test');

$directoryIterator = new RecursiveDirectoryIterator($root);
$recursiveIterator = new RecursiveIteratorIterator($directoryIterator);
$fileIterator = new RegexIterator($recursiveIterator, '/^.+TestCase\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($fileIterator as $file) {
    $name = str_replace('/', '\\', substr($file[0], strlen($root), -4));
    try {
        $reflectionClass = new ReflectionClass($name);
        $object = $reflectionClass->newInstance();
        $object->run(new TextReporter());
    } catch (Exception $e) {}
}

// @todo find all test cases in the namespace application and execute them
