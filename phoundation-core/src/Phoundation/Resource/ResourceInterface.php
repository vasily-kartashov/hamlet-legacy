<?php

namespace Phoundation\Resource;

use Phoundation\Request\RequestInterface;

interface ResourceInterface
{
    /**
     * Get response object
     * @param \Phoundation\Request\RequestInterface $request
     * @return \Phoundation\Response\ResponseInterface
     */
    public function getResponse(RequestInterface $request);
}