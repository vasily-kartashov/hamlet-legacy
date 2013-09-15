<?php
namespace core\entity
{
    use core\entity\Entity;
    use core\Request;
    use core\template\SmartyTemplate;

    abstract class HTMLEntity extends Entity
    {
        /**
         * Get absolute path to entity template
         * @return mixed
         */
        abstract public function getTemplatePath();

        /**
         * Get template data
         * @return mixed
         */
        abstract public function getTemplateData();

        /**
         * Get content
         * @return string
         */
        public function getContent()
        {
            $template = new SmartyTemplate($this->getTemplateData(), $this->getTemplatePath());
            return $template->generateOutput();
        }

        /**
         * Get media type
         * @return string
         */
        public function getMediaType()
        {
            return 'text/html;charset=UTF-8';
        }
    }
}