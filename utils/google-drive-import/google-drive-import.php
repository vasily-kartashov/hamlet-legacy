<?php
require_once(__DIR__ . '/../../library/build.php');


// change credentials in library/build/application/task/GoogleDriveImportJob.php
// then run refresh-tokens.php
// before running this script

$job = new \application\task\GoogleDriveImportJob();
$job->execute();

