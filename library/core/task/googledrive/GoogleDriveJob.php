<?php
namespace core\task\googledrive
{
    use Google_AssertionCredentials;
    use Google_Client;
    use core\task\Job;

    abstract class GoogleDriveJob extends Job
    {
        /**
         * @return string
         */
        abstract protected function getClientId();

        /**
         * @return string
         */
        abstract protected function getClientSecret();

        /**
         * @return string
         */
        abstract protected function getTokens();

        protected function getGoogleClient()
        {
            $client = new Google_Client();

            global $apiConfig;
            $apiConfig['use_objects'] = true;

            $client->setClientId($this->getClientId());
            $client->setClientSecret($this->getClientSecret());
            $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
            $client->setScopes(array('https://www.googleapis.com/auth/drive'));
            $client->setAccessType('offline');
            $client->setAccessToken($this->getTokens());

            return $client;
        }
    }
}