<?php
namespace core
{
    abstract class Resource
    {
        /**
         * Get response for the specified request
         * @param \core\Request $request
         * @return \core\Response
         */
        abstract public function getResponse(Request $request);
    }
}
