<?php
namespace application\entity
{
    use application\locale\DefaultLocale;
    use core\entity\HTMLEntity;

    class HomePageEntity extends HTMLEntity
    {
        protected $locale;

        public function __construct(DefaultLocale $locale)
        {
            $this->locale = $locale;
        }

        public function getTemplateData()
        {
            return array(
                'greeting' => $this->locale->getGreeting('User'),
            );
        }

        public function getTemplatePath()
        {
            return __DIR__ . '/template/home.tpl';
        }

        public function getKey()
        {
            return __CLASS__ . get_class($this->locale);
        }
    }
}