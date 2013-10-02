<?php
namespace application\task
{
    use core\task\googledrive\GoogleDriveJob;

    class GoogleDriveImportJob extends GoogleDriveJob
    {


        protected function getClientId()
        {
            return '671582737219.apps.googleusercontent.com';
        }

        protected function getClientSecret()
        {
            return '_fY-SQgeXI7KbsJ2zMBo8IwO';
        }


        protected function getTasks()
        {
            $client = $this->getGoogleClient();

            return array(
                new GoogleDriveImportCopyTask($client,'0B-v7Z2aA2a5nSWNqVl9JZDJVSzQ',__DIR__  . '/../../../runtime/application/data/locale'),
                new GoogleDriveImportImagesTask($client,'0B-v7Z2aA2a5nVlFhRnprQWo1RUU',__DIR__ . '/../../../../public/images/content'),
            );
        }
    }
}