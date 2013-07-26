<?php
require(__DIR__ . '/../library/library.php');

$application = new \application\FrontendApplication();
$request = new core\request\WebRequest();
$response = $application->run($request);
$application->output($request, $response);