<?php
namespace core\template
{
    use Smarty;
    use core\Template;

    class SmartyTemplate extends Template
    {
        /** @var string */
        protected $path;

        /**
         * @param mixed $data
         * @param string $path
         */
        public function __construct($data, $path)
        {
            Template::__construct($data);
            $this->path = $path;
        }

        /**
         * Generate template output
         * @return string
         */
        public function generateOutput()
        {
            $data = (is_array($this->data)) ? $this->data : array('content' => $this->data);
            $smarty = new Smarty();
            $smarty->setCacheDir(sys_get_temp_dir());
            $smarty->setCompileDir(sys_get_temp_dir());
            $smarty->addPluginsDir(__DIR__ . '/smarty-extensions/');

            $smarty->assign($data);
            return $smarty->fetch($this->path);
        }
    }
}