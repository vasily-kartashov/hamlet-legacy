<?php

namespace core\response
{
    use core\Response;

    class PreconditionFailedResponse extends Response
    {
        public function __construct()
        {
            Response::__construct('412 Precondition Failed');
        }
    }
}