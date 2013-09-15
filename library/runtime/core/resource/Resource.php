<?php
namespace core\resource
{
    use core\request\Request;
    use core\response\Response;

    abstract class Resource
    {
        /**
         * Get response for the specified request
         * @param Request $request
         * @return Response
         */
        abstract public function getResponse(Request $request);
    }
}
