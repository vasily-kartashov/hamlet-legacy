<?php
namespace core\request
{
    class PHPRequest extends Request 
    {
        protected $environment;

        public function __construct($path, array $parameters = array())
        {
            $this->path = urldecode($path);

            $defaultParameters = array(
                'headers' => array(),
                'method' => 'GET',
                'parameters' => array(),
                'session' => array(),
                'locale' => 'en',
                'cookies' => array(),
                'ip' => '127.0.0.1',
                'environment' => 'localhost',
            );
            $allParameters = array_merge($parameters, $defaultParameters);

            $this->environment = $allParameters['environment'];
            $this->headers = $allParameters['headers'];
            $this->method = $allParameters['method'];
            $this->parameters = $allParameters['parameters'];
            $this->session = $allParameters['session'];
            $this->locale = $allParameters['locale'];
            $this->cookies = $allParameters['cookies'];
            $this->ip = $allParameters['ip'];
        }

        public function getEnvironmentName()
        {
            return $this->environment;
        }

        public function getLocaleNames()
        {
            return array($this->locale);
        }

        public function getLanguageCodes()
        {
            return array('en');
        }
    }
}
