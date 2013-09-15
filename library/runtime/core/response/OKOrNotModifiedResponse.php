<?php
namespace core\response
{
    use core\entity\Entity;
    use core\request\Request;
    use Memcached;

    class OKORNotModifiedResponse extends Response
    {
        /**
         * @param Entity $entity
         * @param Request $request
         */
        public function __construct(Entity $entity, Request $request)
        {
            $this->setEntity($entity);
        }

        /**
         * @param Request $request
         * @param Memcached $cache
         */
        public function output(Request $request, Memcached $cache)
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
}