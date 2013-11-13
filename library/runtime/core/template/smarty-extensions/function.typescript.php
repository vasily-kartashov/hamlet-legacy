<?php
function smarty_function_typescript($params, Smarty_Internal_Template &$smarty)
{
    if (!$params['isDevelopmentMode']) {
        $code = '<script type="text/javascript" src="' . $params['urlPrefix'] . '/all.js"></script>';
    } else {
        $dirPath = realpath(dirname($smarty->template_resource) . '/' . $params['fileSystemPath']);
        $parser = new \core\typescript\Parser();
        $code = '';
        foreach ($parser->collectDependencies($dirPath) as $dependency) {
            $code .= '<script type="text/javascript" src="' . $params['urlPrefix'] . '/' . $dependency . '?' . time()  . '"></script>' . PHP_EOL;
        }
    }
    return $code;
}