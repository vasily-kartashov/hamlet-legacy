<?php

namespace Phoundation\Response;

use Phoundation\Cache\CacheInterface;
use Phoundation\Request\RequestInterface;

interface ResponseInterface
{
    /**
     * @param \Phoundation\Request\RequestInterface $request
     * @param \Phoundation\Cache\CacheInterface $cache
     * @return void
     */
    public function output(RequestInterface $request, CacheInterface $cache);
}