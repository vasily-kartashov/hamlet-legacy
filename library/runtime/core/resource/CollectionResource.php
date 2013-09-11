<?php
namespace core\resource
{
    use core\Request;
    use core\Resource;
    use core\response\CreatedResponse;
    use core\response\MethodNotAllowedResponse;
    use core\response\OKORNotModifiedResponse;
    use core\response\PreconditionFailedResponse;

    abstract class CollectionResource extends Resource
    {
        /**
         * @param \core\Request $request
         * @return \core\Entity
         */
        abstract protected function getCollection(Request $request);

        /**
         * Returns an array with url of the newly created item as well as the entity for this element
         *
         * @param \core\Request $request
         * @return \core\entity\LocatedEntity
         */
        abstract protected function createCollectionElement(Request $request);

        /**
         * @param \core\Request $request
         * @return bool
         */
        abstract protected function isPostRequestValid(Request $request);

        /**
         * @param \core\Request $request
         * @return \core\Response
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
            return new MethodNotAllowedResponse(array('GET', 'POST'));
        }
    }
}