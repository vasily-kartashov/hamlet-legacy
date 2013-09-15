<?php
namespace core\resource
{
    use core\entity\Entity;
    use core\request\Request;
    use core\response\MethodNotAllowedResponse;
    use core\response\NotFoundResponse;

    class NotFoundResource extends Resource
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
         * @return MethodNotAllowedResponse|NotFoundResponse
         */
        public function getResponse(Request $request)
        {
            if ($request->getMethod() == 'GET') {
                $response = new NotFoundResponse($this->entity);
                $response->setHeader('Cache-Control', 'private');
                return $response;
            }
            return new MethodNotAllowedResponse(['GET']);
        }
    }
}