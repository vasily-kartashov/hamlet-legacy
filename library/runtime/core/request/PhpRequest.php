<?php
namespace core\request
{
    class PHPRequest extends Request 
    {
        protected $environment;

        public function __construct($path, $request)
        {
            $defaults = array(
                'headers' => array(),
                'method' => 'GET',
                'parameters' => array(),
                'session' => array(),
                'locale' => 'en',
                'cookies' => array(),
                'ip' => '127.0.0.1',
                'environment' => 'localhost',
            );

            $request = array_merge($request,$defaults);

            $this->environment = $request['environment'];
            $this->headers = $request['headers'];
            $this->method = $request['method'];
            $this->parameters = $request['parameters'];
            $this->path = $path;
            $this->session = $request['session'];
            $this->locale = $request['locale'];
            $this->cookies = $request['cookies'];
            $this->ip = $request['ip'];
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
