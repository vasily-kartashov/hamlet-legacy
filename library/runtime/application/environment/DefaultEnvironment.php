<?php
namespace application\environment
{
    use application\db\DatabaseService;
    use application\locale\DefaultLocale;
    use application\locale\GermanLocale;
    use SQLite3;

    class DefaultEnvironment
    {
        protected $databaseService = null;

        public function getCacheServerLocation()
        {
            return array('localhost', 11211);
        }

        public function getCanonicalDomain()
        {
            return 'http://foundation.dev';
        }

        public function localeExists($localeName)
        {
            return $localeName == 'en' or $localeName == 'de';
        }

        public function getLocale($localeName)
        {
            switch ($localeName) {
                case 'de':
                    return new GermanLocale();
                default:
                case 'en':
                    return new DefaultLocale();
            }
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
    }
}