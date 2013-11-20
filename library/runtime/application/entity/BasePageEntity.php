<?php
namespace application\entity
{
    use application\environment\Environment;
    use application\locale\Locale;
    use core\entity\HTMLEntity;

    abstract class BasePageEntity extends HTMLEntity
    {
        protected $locale;
        protected $environment;

        /**
         * @return array
         */
        abstract protected function getContentData();

        /**
         * @return string
         */
        abstract protected function getPageTitle();

        public function __construct(Locale $locale, Environment $environment)
        {
            $this->locale = $locale;
            $this->environment = $environment;
        }

        /**
         * @return array|mixed
         */
        public function getTemplateData()
        {
            return array(
                'base' => $this->getBaseData(),
                'content' => $this->getContentData(),
            );
        }

        protected function translate($token)
        {
            return $this->locale->translate($token);
        }

        protected function translateOr($token, $else="")
        {
            $trans = $this->locale->translate($token);
            if ($trans === $token) {
                return $else;
            }
            return $trans;
        }

        protected function translateButton($token,$url=null)
        {
            if(is_null($url)){
                $url =  $this->locale->translate($token . '-url');
            }
            return $this->createButton($this->locale->translate($token . '-copy'),$url,$this->locale->translate($token . '-title'));

        }

        protected function createButton($copy,$url,$title)
        {
            $button = array(
                'copy' => $copy,
                'title' => $title,
                'url' => $url,
            );
            return $button;
        }

        protected function translateImage($token)
        {
            return array(
                'src' => $this->translateImageSrc($token . '-src'),
                'alt' => $this->locale->translate($token . '-alt')
            );
        }

        protected function translateImageSrc($token)
        {
            return  '/images/content/' . $this->locale->translate($token);
        }

        protected function getMetaData()
        {
            return array(
                'description' => $this->getPageDescription(),
                'keywords' => $this->translate('token-meta-keywords'),
            );
        }

        protected function getBaseData()
        {
            return array (
                'siteTitle' => $this->getPageTitle(),
                'currentLanguage' => $this->locale->getLanguageCode(),
                'textDirection' => $this->locale->getTextDirection(),
                'jsEnvironment' => $this->getJsEnvironmentData(),
                'meta' => $this->getMetaData(),
                'body' => array(
                    'className' => $this->getBodyClassName(),
                    'dataClass' => $this->getBodyDataClass(),
                ),
                'concatenateJs' => $this->environment->useConcatenatedJavascript()
            );
        }

        protected function getPageDescription()
        {
            return '';
        }


        protected function getBodyClassName()
        {
            return '';
        }

        protected function getBodyDataClass()
        {
            return '';
        }



        protected function getJsEnvironmentData()
        {
            return array(
                'facebookAppId' => $this->environment->getFacebookAppId()
            );
        }

        public function getKey()
        {
            return __CLASS__ . get_class($this->locale);
        }
    }
}