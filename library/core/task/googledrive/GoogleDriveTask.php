<?php
namespace core\task\googledrive
{
    use Exception;
    use Google_Client;
    use Google_DriveFile;
    use Google_DriveService;
    use Google_HttpRequest;
    use PHPExcel_IOFactory;
    use core\io\Directory;
    use core\task\Task;

    abstract class GoogleDriveTask extends Task
    {
        /** @var \Google_Client */
        protected $client;

        public function __construct(Google_Client $client)
        {
            $this->client = $client;
        }

        /**
         * @param \Google_DriveFile $file
         * @return array
         * @throws \Exception
         */
        protected function readSpreadsheet(Google_DriveFile $file)
        {
            $tempPath = sys_get_temp_dir() . '/' . $file->getId() . '.' . md5($file->getEtag()) . '.txt';
            if (file_exists($tempPath)) {
                // unlink($tempPath); // disable the caching of spreadsheets
            }

            if (!file_exists($tempPath)) {
                echo $file->getTitle() . ': ' . $file->getEtag() . PHP_EOL;

                $exportLinks = $file->getExportLinks();
                $url = $exportLinks['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
                $request = new Google_HttpRequest($url, 'GET', null, null);
                $httpRequest = Google_Client::$io->authenticatedRequest($request);
                if ($httpRequest->getResponseHttpCode() == 200) {
                    $content = $httpRequest->getResponseBody();
                    $tempDownloadPath = sys_get_temp_dir() . '/' . $file->getId() . '.' . md5($file->getEtag()) . '.xlsx';
                    file_put_contents($tempDownloadPath, $content);
                    $excel = PHPExcel_IOFactory::load($tempDownloadPath);
                    $result = array();
                    $sheets = $excel->getAllSheets();
                    foreach ($sheets as $sheet) {
                        $result[$sheet->getTitle()] = $sheet->toArray(null, true, true, true);
                    }
                    file_put_contents($tempPath, serialize($result));
                    unlink($tempDownloadPath);
                } else {
                    throw new Exception("Cannot download file " . $file->getTitle());
                }
            }
            return unserialize(file_get_contents($tempPath));
        }

        /**
         * @param \Google_DriveService $service
         * @param string $folderId
         * @param string[] $includingMimeTypes
         * @param string[] $excludingMimeTypes
         * @return \Google_DriveFile[]
         */
        protected function getFilesByMimeType(Google_DriveService $service, $folderId, $includingMimeTypes = null, $excludingMimeTypes = null)
        {
            $result = array();
            $pageToken = null;
            do {
                $list = $service->children->listChildren($folderId, array(
                    'maxResults' => 1000,
                    'pageToken' => $pageToken,
                ));
                foreach ($list->getItems() as $child) {
                    /** @var $child \Google_ChildReference */
                    /** @var $file \Google_DriveFile */
                    $file = null;
                    while (is_null($file)) {
                        try {
                            $file = $service->files->get($child->getId());
                        } catch (Exception $e) {
                            echo 'cannot fetch file data ' . $child->getId() . PHP_EOL;
                            sleep(1);
                        }
                    }
                    /** @var $labels \Google_DriveFileLabels */
                    $labels = $file->getLabels();
                    $mimeType = $file->getMimeType();

                    if (!$labels->getTrashed() and
                        (is_null($includingMimeTypes) or in_array($mimeType, $includingMimeTypes)) and
                        (is_null($excludingMimeTypes) or !in_array($mimeType, $excludingMimeTypes))) {

                        $result[$file->getTitle()] = $file;
                    }
                }
                $pageToken = $list->getNextPageToken();
            } while ($pageToken);
            return $result;
        }

        /**
         * @param \Google_DriveService $service
         * @param string $folderId
         * @return \Google_DriveFile[]
         */
        public function getSubfolders(Google_DriveService $service, $folderId)
        {
            $mimeType = 'application/vnd.google-apps.folder';
            return $this->getFilesByMimeType($service, $folderId, array($mimeType));
        }

        /**
         * @param \Google_DriveService $service
         * @param string $folderId
         * @return \Google_DriveFile[]
         */
        public function getFiles(Google_DriveService $service, $folderId)
        {
            $mimeType = 'application/vnd.google-apps.folder';
            return $this->getFilesByMimeType($service, $folderId, null, array($mimeType));
        }

        public function getSpreadsheets(Google_DriveService $service, $folderId)
        {
            $mimeType = 'application/vnd.google-apps.spreadsheet';
            return $this->getFilesByMimeType($service, $folderId, array($mimeType));
        }

        public function getVideoFiles(Google_DriveService $service, $folderId)
        {
            $mimeTypes = array(
                'video/webm',
                'application/ogg',
                'video/mp4',
            );
            return $this->getFilesByMimeType($service, $folderId, $mimeTypes);
        }

        public function getImageFiles(Google_DriveService $service, $folderId)
        {
            $mimeTypes = array(
                'image/jpeg',
                'image/png',
            );
            return $this->getFilesByMimeType($service, $folderId, $mimeTypes);
        }

        /**
         * @param \Google_DriveService $service
         * @param string $folderId
         * @return array
         */
        protected function readAllSpreadsheets(Google_DriveService $service, $folderId)
        {
            $files = $this->getSpreadsheets($service, $folderId);
            $result = array();
            foreach ($files as $file) {
                $result[$file->getTitle()] = $this->readSpreadsheet($file);
            }
            return $result;
        }

        protected function copyFolder(Google_DriveService $service, $folderId, $targetPath)
        {
            // copy all the data, don't clean the target as it's the job for the caller
        }

        protected function downloadFile(Google_DriveFile $file)
        {
            $tempPath = sys_get_temp_dir() . '/' . $file->getId();
            $downloadUrl = $file->getDownloadUrl();

            if (!$downloadUrl) {
                throw new Exception('The files ' . $file->getId() . ' is not stored on drive');
            }

            if (!file_exists($tempPath) or $file->getMd5Checksum() != md5_file($tempPath)) {
                $request = new Google_HttpRequest($downloadUrl, 'GET', null, null);
                $httpRequest = Google_Client::$io->authenticatedRequest($request);
                if ($httpRequest->getResponseHttpCode() == 200) {
                    $content = $httpRequest->getResponseBody();
                    file_put_contents($tempPath, $content);
                } else {
                    throw new Exception('Cannot download the file ' . $file->getId() . ' (' . $file->getTitle() . ')');
                }
            }

            return file_get_contents($tempPath);
        }

        protected function clearDirectory($path)
        {
            $directory = new Directory($path);
            $directory->clear();
        }
    }
}
