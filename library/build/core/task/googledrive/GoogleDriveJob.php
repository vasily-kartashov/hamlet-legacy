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
        protected function getTokens()
        {
            return file_get_contents($this->getTokensPath());
        }


        protected function getGoogleClientWithoutToken()
        {
            $client = new Google_Client();

            global $apiConfig;
            $apiConfig['use_objects'] = true;

            $client->setClientId($this->getClientId());
            $client->setClientSecret($this->getClientSecret());
            $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
            $client->setScopes(array('https://www.googleapis.com/auth/drive'));
            $client->setAccessType('offline');

            return $client;

        }


        protected function getTokensPath()
        {
            return __DIR__ . '/tokens.json';
        }


        protected function getGoogleClient()
        {
            $client = $this->getGoogleClientWithoutToken();
            $client->setAccessToken($this->getTokens());
            return $client;
        }


        public function refreshTokens()
        {
            $client = $this->getGoogleClientWithoutToken();
            $url = $client->createAuthUrl();
            print('Visit the following URL' . PHP_EOL);
            print($url . PHP_EOL . PHP_EOL);
            $authCode = trim(fgets(STDIN));
            $tokens = $client->authenticate($authCode);
            file_put_contents($this->getTokensPath(), $tokens);


        }
    }
}