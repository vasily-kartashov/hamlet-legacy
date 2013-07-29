<?php
namespace application\environment
{
    use application\locale\DefaultLocale;
    use application\locale\GermanLocale;

    class DefaultEnvironment
    {
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
    }
}