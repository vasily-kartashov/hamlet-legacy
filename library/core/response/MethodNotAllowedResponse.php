<?php

namespace core\response
{
    use core\Response;

    /**
     * The method specified in the Request-Line is not allowed for the resource identified by the Request-URI. The
     * provider MUST include an Allow header containing a list of valid methods for the requested resource.
     */
    class MethodNotAllowedResponse extends Response
    {
        public function __construct(array $allowedMethods)
        {
            Response::__construct('405 Method Not Allowed');
            $this->setHeader('Allow', join(', ', $allowedMethods));
        }
    }
}