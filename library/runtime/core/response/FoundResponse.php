<?php
namespace core\response
{

    class FoundResponse extends Response
    {
        /**
         * @param string $url
         */
        public function __construct($url)
        {
            assert(is_string($url));
            parent::__construct('302 Found');
            $this->setHeader('Location', $url);
        }
    }
}