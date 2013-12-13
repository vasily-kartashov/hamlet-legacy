<?php
namespace application\task
{
    use core\task\googledrive\GoogleDriveFolderTask;
    use Google_DriveService;

    class GoogleDriveImportCopyTask extends GoogleDriveFolderTask
    {
        public function execute()
        {
            $service = new Google_DriveService($this->client);
            $data = $this->readAllSpreadsheets($service, $this->getCopiesFolderId());

            $localeData = array();

            foreach ($data as $doc) {
                foreach ($doc as $sheet) {
                    $header = array_shift($sheet);
                    foreach ($header as $key => $localeNameWithVersion) {
                        if ($key == 'A') {
                            continue;
                        }
                        preg_match('/([^\s]+)\s*(\(\s*(\d+)\s*\))?/', $localeNameWithVersion, $matches);
                        if (!isset($matches[1])) {
                            continue;
                        }
                        $localeName = $matches[1];

                        foreach ($sheet as $row) {
                            $token = trim($row['A']);
                            if (!$token) {
                                continue;
                            }
                            $value = trim($row[$key]);
                            if (!$value) {
                                continue;
                            }
                            $localeData[$localeName][$token] = $value;
                        }

                    }
                }
            }

            $rootPath = $this->outputFolder;
            $this->clearDirectory($rootPath);
            foreach ($localeData as $localeName => $data) {
                $path = $rootPath . '/' . $localeName . '.php';
                $code = '<?php' . "\nreturn " . var_export($data, true) . ';';
                file_put_contents($path, $code);
            }
        }

        protected function getCopiesFolderId()
        {
            return $this->folderId;
        }
    }
}
