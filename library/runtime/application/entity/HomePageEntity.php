<?php
namespace application\entity
{
    use application\environment\Environment;
    use application\locale\Locale;

    class HomePageEntity extends BasePageEntity
    {
        protected $locale;

        public function __construct(Locale $locale, Environment $environment)
        {
            parent::__construct($locale,$environment);
            $this->locale = $locale;
        }

        public function getTemplatePath()
        {
            return __DIR__ . '/template/home.tpl';
        }

        public function getKey()
        {
            return __CLASS__ . get_class($this->locale);
        }

        /**
         * @return array
         */
        protected function getContentData()
        {
            return array(
                'greeting' => $this->locale->translate('token-hello'),
            );
        }

        /**
         * @return string
         */
        protected function getPageTitle()
        {
            return 'Homepage';
        }
    }
}