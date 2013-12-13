<?php
namespace application\task
{
    use core\task\googledrive\GoogleDriveJob;

    class GoogleDriveImportJob extends GoogleDriveJob
    {
        protected function getClientId()
        {
            return 'REPLACE_WITH_CLIENT_ID';
        }

        protected function getClientSecret()
        {
            return 'REPLACE_WITH_CLIENT_SECRET';
        }

        protected function getTasks()
        {
            $client = $this->getGoogleClient();
            return array(
                new GoogleDriveImportCopyTask($client, '0B-v7Z2aA2a5nSWNqVl9JZDJVSzQ', __DIR__  . '/../../../runtime/application/data/locale'),
                new GoogleDriveImportImagesTask($client, '0B-v7Z2aA2a5nVlFhRnprQWo1RUU', __DIR__ . '/../../../../public/images/content'),
            );
        }
    }
}