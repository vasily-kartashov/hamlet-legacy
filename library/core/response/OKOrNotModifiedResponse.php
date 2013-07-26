<?php
namespace core\response
{
    use core\Entity;
    use core\Request;
    use core\Response;
    use Memcached;

    class OKORNotModifiedResponse extends Response
    {
        public function __construct(Entity $entity, Request $request)
        {
            $this->setEntity($entity);
        }

        public function output(Request $request, Memcached $cache)
        {
            if ($request->preconditionFulfilled($this->entity, $cache)) {
                $this->setStatus('200 OK');
                $this->setEmbedEntity(true);
            } else {
                $this->setStatus('304 Not Modified');
                $this->setEmbedEntity(false);
            }
            Response::output($request, $cache);
        }
    }
}