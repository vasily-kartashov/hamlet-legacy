<?php
namespace core\resource
{
    use core\entity\Entity;
    use core\request\Request;
    use core\response\MethodNotAllowedResponse;
    use core\response\OKORNotModifiedResponse;

    class EntityResource extends Resource
    {
        protected $entity;

        /**
         * @param Entity $entity
         */
        public function __construct(Entity $entity)
        {
            $this->entity = $entity;
        }

        /**
         * @param Request $request
         * @return MethodNotAllowedResponse|OKORNotModifiedResponse
         */
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