<?php
namespace application\environment
{
    use application\db\DatabaseService;
    use application\locale\Locale;
    use SQLite3;

    class Environment
    {
        protected $databaseService = null;

        public function getCacheServerLocation()
        {
            return array('localhost', 11211);
        }

        public function getCanonicalDomain()
        {
            return $this->getDomain();
        }

        public function getDomain()
        {

            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $domainName = $_SERVER['SERVER_NAME'];
            return $protocol . $domainName;
        }

        public function localeExists($localeName)
        {
            return $localeName == 'en' or $localeName == 'de';
        }

        public function getLocaleNames()
        {
            return array(
                'int' => 'en',
                'en' => 'en',
                'gb' => 'en-gb',
                'us' => 'en-us',
                'au' => 'en-au',
                'at' => 'de-at',
                'be-nl' => 'nl-be',
                'be-fr' => 'fr-be',
                'br' => 'pt-br',
                'ca-en' => 'en-ca',
                'ca-fr' => 'fr-ca',
                'cn' => 'zh-cn',
                'cz' => 'cs-cz',
                'fr' => 'fr-fr',
                'de' => 'de-de',
                'in' => 'en-in',
                'it' => 'it-it',
                'jp' => 'ja-jp',
                'me-ar' => 'ar',
                'me-en' => 'en',
                'nl' => 'nl-nl',
                'pt' => 'pt-pt',
                'ru' => 'ru-ru',
                'za' => 'en-za',
                'es' => 'es-es',
                'ch-fr' => 'fr-ch',
                'ch-de' => 'de-ch',
            );
        }



        public function getLocale($localeName)
        {
            $data = array();
            if ($this->localeExists($localeName)) {
                $localePath = __DIR__ . '/../data/locale/' . $localeName . '.php';
                if (file_exists($localePath)) {
                    /** @noinspection PhpIncludeInspection */
                    $data = require($localePath);
                }
            }
            $localeNames = $this->getLocaleNames();
            return new Locale($data,$localeNames[$localeName]);
        }

        protected function getDatabase()
        {
            $path = __DIR__ . '/../../../../data/todo.db';
            $databaseExists = file_exists($path);
            $db = new SQLite3($path, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
            if (!$databaseExists) {
                $db->exec('
                    CREATE TABLE items (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        uid INTEGER,
                        content TEXT,
                        done INTEGER
                    )
                ');
            }
            return $db;
        }

        public function getDatabaseService()
        {
            if (is_null($this->databaseService)) {
                $this->databaseService = new DatabaseService($this->getDatabase());
            }
            return $this->databaseService;
        }

        public function getFacebookAppId()
        {
            return '543290215752753';
        }

        public function useConcatenatedJavascript()
        {
            return true;
        }
    }
}