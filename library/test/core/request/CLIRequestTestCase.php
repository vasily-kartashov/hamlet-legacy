<?php
namespace core\request
{
    class CLIRequestTestCase extends RequestTestCase
    {
        protected function createRequest($path)
        {
            $argv = array(null, '-path', $path);
            return new CLIRequest($argv);
        }
    }
}