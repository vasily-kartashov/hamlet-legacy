<?php
namespace core\resource
{
    use core\entity\Entity;
    use core\entity\LocatedEntity;
    use core\request\Request;
    use core\response\CreatedResponse;
    use core\response\MethodNotAllowedResponse;
    use core\response\OKORNotModifiedResponse;
    use core\response\PreconditionFailedResponse;

    abstract class CollectionResource extends Resource
    {
        /**
         * @param Request $request
         * @return Entity
         */
        abstract protected function getCollection(Request $request);

        /**
         * Returns an array with url of the newly created item as well as the entity for this element
         *
         * @param Request $request
         * @return LocatedEntity
         */
        abstract protected function createCollectionElement(Request $request);

        /**
         * @param Request $request
         * @return bool
         */
        abstract protected function isPostRequestValid(Request $request);

        /**
         * @param Request $request
         * @return CreatedResponse|MethodNotAllowedResponse|OKORNotModifiedResponse|PreconditionFailedResponse
         */
        public function getResponse(Request $request)
        {
            if ($request->getMethod() == 'GET') {
                return new OKORNotModifiedResponse($this->getCollection($request), $request);
            }
            if ($request->getMethod() == 'POST') {
                if (!$this->isPostRequestValid($request)) {
                    return new PreconditionFailedResponse();
                }
                $locatedEntity = $this->createCollectionElement($request);
                return new CreatedResponse($locatedEntity->getLocation(), $locatedEntity->getEntity());
            }
            return new MethodNotAllowedResponse(['GET', 'POST']);
        }
    }
}