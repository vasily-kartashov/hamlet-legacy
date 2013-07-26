<?php
namespace core\geo
{
    use SQLite3;

    class GeoIPStore
    {
        /**
         * Get two letter country code provided IP
         * @param string $ip
         * @return string
         */
        public function getCountryCode($ip)
        {
            if (!preg_match('/\d{1,3}(\.\d{1,3}){3}/', $ip)) {
                return null;
            }
            $tokens = explode('.', $ip);
            $key = (int) $tokens[0];
            $id = 65536 * ((int) $tokens[1]) + 256 * ((int) $tokens[2]) + ((int) $tokens[3]);

            $query = "SELECT country FROM ranges_{$key} WHERE {$id} BETWEEN lb AND ub LIMIT 1";
            $db = new SQLite3(__DIR__ . '/data/geoip.db', SQLITE3_OPEN_READONLY);
            $result = $db->query($query)->fetchArray();

            return $result ? $result['country'] : null;
        }
    }
}
