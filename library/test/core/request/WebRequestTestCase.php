<?php
namespace core\request
{
    class WebRequestTestCase extends RequestTestCase
    {
        protected function createRequest($path)
        {
            $_SERVER['SERVER_NAME'] = 'localhost';
            $_SERVER['REQUEST_METHOD'] = 'GET';
            $_SERVER['REMOTE_ADDR'] = '0.0.0.0';
            $_SERVER['REQUEST_URI'] = $path;

            return new WebRequest();
        }
    }
}
