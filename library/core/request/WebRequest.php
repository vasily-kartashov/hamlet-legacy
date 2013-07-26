<?php
namespace core\request
{
    use core\Request;

    class WebRequest extends Request
    {
        protected $serverName;

        public function __construct()
        {
            $this->parameters = $_REQUEST;
            $this->serverName = $_SERVER['SERVER_NAME'];
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->session = isset($_SESSION) ? $_SESSION : array();
            $this->headers = getallheaders();
            $this->cookies = $_COOKIE;
            $this->ip = isset($this->headers['X-Forwarded-For']) ? $this->headers['X-Forwarded-For'] : $_SERVER['REMOTE_ADDR'];

            $completePath = urldecode($_SERVER['REQUEST_URI']);
            $questionMarkPosition = strpos($completePath, '?');
            if ($questionMarkPosition === false) {
                $this->path = $completePath;
            } else {
                $this->path = substr($completePath, 0, $questionMarkPosition);
            }
        }

        public function getEnvironmentName()
        {
            return $this->serverName;
        }

        public function getLanguageCodes()
        {
            return $this->parseHeader($this->getHeader('Accept-Language'));
        }
    }
}