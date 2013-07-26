<?php
namespace core\response
{
    use core\Response;

    class FoundResponse extends Response
    {
        public function __construct($url)
        {
            Response::__construct('302 Found');
            $this->setHeader('Location', $url);
        }
    }
}