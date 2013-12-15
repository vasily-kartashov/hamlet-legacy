<?php
require_once(__DIR__ . '/../../library/runtime.php');
require_once(__DIR__ . '/../../library/test.php');

$root = realpath(__DIR__ . '/../../library/test');

$directoryIterator = new RecursiveDirectoryIterator($root);
$recursiveIterator = new RecursiveIteratorIterator($directoryIterator);
$fileIterator = new RegexIterator($recursiveIterator, '/^.+TestCase\.php$/i', RecursiveRegexIterator::GET_MATCH);

$success = true;
foreach ($fileIterator as $file) {
    $name = str_replace('/', '\\', substr($file[0], strlen($root), -4));
    try {
        $reflectionClass = new ReflectionClass($name);
        if (!$reflectionClass->isAbstract() and $reflectionClass->isSubclassOf('UnitTestCase')) {
            $object = $reflectionClass->newInstance();
            $success = $object->run(new TextReporter()) and $success;
        }
    } catch (Exception $e) {}
}
exit($success ? 0 : 1);
