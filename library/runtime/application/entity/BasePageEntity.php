<?php
namespace application\entity
{
    use application\locale\Locale;
    use core\entity\HTMLEntity;

    abstract class BasePageEntity extends HTMLEntity
    {
        protected $locale;


        public function __construct(Locale $locale)
        {
            $this->locale = $locale;
        }


        protected function translate($token)
        {
            return $this->locale->translate($token);
        }



        protected function getMetaData()
        {
            return array(
                'description' => $this->translate('token-meta-description'),
                'keywords' => $this->translate('token-meta-keywords'),
            );
        }

        protected function getBaseData()
        {
            return array (
                'siteTitle' => $this->getPageTitle(),
                'currentLanguage' => $this->locale->getLanguageCode(),
                'textDirection' => $this->locale->getTextDirection(),
            );
        }


        protected function getPageTitle()
        {
            return $this->translate('token-site-title');
        }

        public function getTemplateData()
        {
            return array(
                'base' => $this->getBaseData(),
                'meta' => $this->getMetaData(),
            );
        }

        public function getKey()
        {
            return __CLASS__ . get_class($this->locale);
        }
    }
}