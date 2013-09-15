<?php

namespace core\response
{
    class PreconditionFailedResponse extends Response
    {
        public function __construct()
        {
            parent::__construct('412 Precondition Failed');
        }
    }
}