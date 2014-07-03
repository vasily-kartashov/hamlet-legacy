<?php
http_response_code(500); // Unexpected errors killing the php module should have the right response code set

require(__DIR__ . '/../library/runtime.php');
$application = new \application\FrontendApplication();
$request = new core\request\WebRequest();
$response = $application->run($request);
$application->output($request, $response);
