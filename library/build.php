<?php
// auto-generated file, do not edit
spl_autoload_register(function($className) {
    static $map;
    if (!is_array($map)) {
        $map = array(

        );
    }
    if (isset($map[$className])) {
        /** @noinspection PhpIncludeInspection */
        require($map[$className]);
        return true;
    }
    return false;
});