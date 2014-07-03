<?php

namespace Phoundation\Resource;

use Phoundation\Entity\EntityInterface;
use Phoundation\Request\RequestInterface;
use Phoundation\Response\MethodNotAllowedResponse;
use Phoundation\Response\OKORNotModifiedResponse;

class EntityResource implements ResourceInterface
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
     * @return \Phoundation\Response\MethodNotAllowedResponse|\Phoundation\Response\OKOrNotModifiedResponse
     */
    public function getResponse(RequestInterface $request)
    {
        if ($request->getMethod() == 'GET') {
            $response = new OKOrNotModifiedResponse($this->entity, $request);
            return $response;
        }
        return new MethodNotAllowedResponse(array('GET'));
    }
}

