<?php

namespace Phoundation\Resource;

use Phoundation\Entity\EntityInterface;
use Phoundation\Request\RequestInterface;
use Phoundation\Response\MethodNotAllowedResponse;
use Phoundation\Response\NotFoundResponse;

class NotFoundResource implements ResourceInterface
{
    protected $entity;

    /**
     * @param \Phoundation\Entity\EntityInterface $entity
     */
    public function __construct(EntityInterface $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param \Phoundation\Request\RequestInterface $request
     * @return \Phoundation\Response\MethodNotAllowedResponse|\Phoundation\Response\NotFoundResponse
     */
    public function getResponse(RequestInterface $request)
    {
        if ($request->getMethod() == 'GET') {
            $response = new NotFoundResponse($this->entity);
            $response->setHeader('Cache-Control', 'private');
            return $response;
        }
        return new MethodNotAllowedResponse(['GET']);
    }
}
