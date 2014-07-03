<?php

namespace Phoundation\Resource;

use Phoundation\Request\RequestInterface;
use Phoundation\Response\MethodNotAllowedResponse;
use Phoundation\Response\TemporaryRedirectResponse;

class RedirectResource implements ResourceInterface
{
    protected $url;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        assert(is_string($url));
        $this->url = $url;
    }

    /**
     * @param \Phoundation\Request\RequestInterface $request
     * @return \Phoundation\Response\MethodNotAllowedResponse|\Phoundation\Response\TemporaryRedirectResponse
     */
    public function getResponse(RequestInterface $request)
    {
        if ($request->getMethod() == 'GET') {
            $response = new TemporaryRedirectResponse($this->url);
            $response->setHeader('Cache-Control', 'private');
            return $response;
        }
        return new MethodNotAllowedResponse(['GET']);
    }
}
