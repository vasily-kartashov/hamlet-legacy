<?php
namespace core\request
{
    class PHPRequestTestCase extends RequestTestCase
    {
        protected function createRequest($path)
        {
            return new PHPRequest($path);
        }
    }
}