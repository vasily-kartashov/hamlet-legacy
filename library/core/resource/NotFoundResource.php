<?php
namespace core\resource
{
    use core\Entity;
    use core\Request;
    use core\Resource;
    use core\response\MethodNotAllowedResponse;
    use core\response\NotFoundResponse;

    class NotFoundResource extends Resource
    {
        protected $entity;

        public function __construct(Entity $entity)
        {
            $this->entity = $entity;
        }

        public function getResponse(Request $request)
        {
            if ($request->getMethod() == 'GET') {
                $response = new NotFoundResponse($this->entity);
                $response->setHeader('Cache-Control', 'private');
                return $response;
            }
            return new MethodNotAllowedResponse(array('GET'));
        }
    }
}