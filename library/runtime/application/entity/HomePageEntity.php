<?php
namespace application\entity
{
    use application\locale\Locale;
    use core\entity\HTMLEntity;

    class HomePageEntity extends BasePageEntity
    {
        protected $locale;

        public function __construct(Locale $locale)
        {
            $this->locale = $locale;
        }

        public function getTemplateData()
        {
            $data = parent::getTemplateData();
            $data['greeting'] = $this->locale->translate('token-hello');
            return $data;
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