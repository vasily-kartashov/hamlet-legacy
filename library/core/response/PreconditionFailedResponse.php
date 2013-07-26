<?php

namespace core\response
{
    use core\Response;

    class PreconditionFailedResponse extends Response
    {
        public function __construct()
        {
            parent::__construct('412 Precondition Failed');
        }
    }
}