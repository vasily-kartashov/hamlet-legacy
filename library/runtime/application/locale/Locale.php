<?php
namespace application\locale
{
    class Locale 
    {
        protected $data;
        protected $languageCode;
        protected $localeName;
        const SKIP = '[SKIP]';

        public function __construct($data, $languageCode,$localeName)
        {
            $this->data = $data;
            $this->languageCode = $languageCode;
            $this->localeName = $localeName;
        }

        public function translate($token)
        {
            if ($this->isToken($token)) {
                return $this->data[$token];
            }
            return $token;
        }

        public function isToken($token) {
            return isset($this->data[$token]);
        }

        public function translateOrIgnore($token)
        {
            if (isset($this->data[$token])) {
                if (substr($this->data[$token], 0, strlen(Locale::SKIP)) == Locale::SKIP) {
                    return '';
                }
                return $this->data[$token];
            }
            return '';
        }

        public function getLanguageCode()
        {
            return $this->languageCode;
        }

        public function getTextDirection()
        {
            return 'ltr';
        }

        public function getLocaleName()
        {
            return $this->localeName;
        }

    }
}
