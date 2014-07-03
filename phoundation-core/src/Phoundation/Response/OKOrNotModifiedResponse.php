<?php

namespace Phoundation\Response;

use Phoundation\Cache\CacheInterface;
use Phoundation\Entity\EntityInterface;
use Phoundation\Request\RequestInterface;

class OKORNotModifiedResponse extends AbstractResponse
{
    /**
     * @param \Phoundation\Entity\EntityInterface $entity
     * @param \Phoundation\Request\RequestInterface $request
     */
    public function __construct(EntityInterface $entity, RequestInterface $request)
    {
        $this->setEntity($entity);
    }

    /**
     * @param \Phoundation\Request\RequestInterface $request
     * @param \Phoundation\Cache\CacheInterface $cache
     */
    public function output(RequestInterface $request, CacheInterface $cache)
    {
        if ($request->preconditionFulfilled($this->entity, $cache)) {
            $this->setStatus('200 OK');
            $this->setEmbedEntity(true);
        } else {
            $this->setStatus('304 Not Modified');
            $this->setEmbedEntity(false);
        }
        parent::output($request, $cache);
    }
}
