<?php
namespace application\task
{
    use core\task\googledrive\GoogleDriveFolderTask;
    use Google_DriveService;

    class GoogleDriveImportImagesTask extends GoogleDriveFolderTask
    {
        public function execute()
        {
            $rootPath = $this->outputFolder;
            $this->clearDirectory($rootPath);

            $service = new Google_DriveService($this->client);

            $folders = array(
                $this->getImagesFolderId() => $rootPath,
            );
            $subfolders = $this->getSubfolders($service, $this->getImagesFolderId());
            foreach ($subfolders as $subfolder) {
                $folders[$subfolder->getId()] = $rootPath . '/' . $subfolder->getTitle();
            }

            foreach ($folders as $folderId => $folderPath) {
                if (!file_exists($folderPath)) {
                    mkdir($folderPath);
                }
                $images = $this->getFiles($service, $folderId);
                foreach ($images as $image) {
                    echo $folderPath . '/' . $image->getTitle();
                    $filePath = $folderPath . '/' . $image->getTitle();
                    $fileContent = $this->downloadFile($image);
                    echo ' (' . strlen($fileContent) . ')' . PHP_EOL;
                    file_put_contents($filePath, $fileContent);
                }
            }
        }

        protected function getImagesFolderId()
        {
            return $this->folderId;
        }
    }
}