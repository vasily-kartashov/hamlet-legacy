<?php

require_once(__DIR__ . '/../../library/build.php');

$job = new \application\task\GoogleDriveImportJob();
$job->refreshTokens();