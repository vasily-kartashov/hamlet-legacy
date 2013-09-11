<?php
namespace core\template
{
    use core\Template;
    use Twig_Environment;
    use Twig_Loader_Filesystem;

    class TwigTemplate extends Template
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
            $environment = $this->getEnvironment();
            $data = (is_array($this->data)) ? $this->data : array('content' => $this->data);
            return $environment->render($this->path, $data);
        }

        /**
         * Get Twig environment
         * @return \Twig_Environment
         */
        protected function getEnvironment()
        {
            $loader = new Twig_Loader_Filesystem('');
            return new Twig_Environment($loader, array(
                'cache' => sys_get_temp_dir(),
            ));
        }
    }
}