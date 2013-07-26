<?php

namespace core\response
{
    use core\Response;

    /**
     * The server is refusing to service the provider because the entity of the provider is in a format not supported by
     * the requested resource for the requested method.
     */
    class UnsupportedMediaTypeResponse extends Response
    {
        public function __construct()
        {
            Response::__construct('415 Unsupported Media Type');
        }
    }
}