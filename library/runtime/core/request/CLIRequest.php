<?php
namespace core\request
{
    class CLIRequest extends Request
    {
        protected $environment;

        public function __construct($argv)
        {
            $this->environment = 'localhost';
            $this->headers = array();
            $this->method = 'GET';
            $this->parameters = array();
            $this->path = '/';
            $this->session = array();
            $this->locale = 'en';
            $this->cookies = array();
            $this->id = '127.0.0.1';

            $parametersCount = count($argv);
            if (($parametersCount % 2) != 1) {
                die('Wrong parameter count' . PHP_EOL);
            }
            for ($i = 1; $i < $parametersCount; $i += 2) {
                $key = substr($argv[$i], 1);
                switch (strtolower($key)) {
                    case 'env':
                        $this->environment = $argv[$i + 1];
                        break;
                    case 'method':
                        $this->method = $argv[$i + 1];
                        break;
                    case 'params':
                        $this->parameters = (array) json_decode($argv[$i + 1]);
                        break;
                    case 'path':
                        $this->path = $argv[$i + 1];
                        break;
                    case 'session':
                        $this->session = (array) json_decode($argv[$i + 1]);
                        break;
                    case 'locale':
                        $this->locale = $argv[$i + 1];
                        break;
                    default:
                        die("Unknown parameter {$key}" . PHP_EOL);
                }
            }
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