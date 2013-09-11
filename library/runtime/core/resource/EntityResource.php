<?php
namespace core\resource
{
    use core\Entity;
    use core\Request;
    use core\Resource;
    use core\response\MethodNotAllowedResponse;
    use core\response\OKORNotModifiedResponse;

    class EntityResource extends Resource
    {
        protected $entity;

        public function __construct(Entity $entity)
        {
            $this->entity = $entity;
        }

        public function getResponse(Request $request)
        {
            if ($request->getMethod() == 'GET') {
                $response = new OKOrNotModifiedResponse($this->entity, $request);
                return $response;
            }
            return new MethodNotAllowedResponse(array('GET'));
        }
    }
}