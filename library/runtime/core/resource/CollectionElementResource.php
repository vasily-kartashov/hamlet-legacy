<?php
namespace core\resource
{
    use core\request\Request;
    use core\entity\Entity;
    use core\response\MethodNotAllowedResponse;
    use core\response\NoContentResponse;
    use core\response\NotFoundResponse;
    use core\response\OKORNotModifiedResponse;
    use core\response\PreconditionFailedResponse;

    abstract class CollectionElementResource extends Resource
    {
        /**
         * @param Request $request
         * @return bool
         */
        abstract protected function collectionElementExists(Request $request);

        /**
         * @param Request $request
         * @return Entity
         */
        abstract protected function getCollectionElement(Request $request);

        /**
         * @param Request $request
         * @return void
         */
        abstract protected function deleteCollectionElement(Request $request);

        /**
         * @param Request $request
         * @return void
         */
        abstract protected function updateCollectionElement(Request $request);

        /**
         * @param Request $request
         * @return bool
         */
        abstract protected function isPutRequestValid(Request $request);

        public function getResponse(Request $request)
        {
            if (!$this->collectionElementExists($request)) {
                return new NotFoundResponse();
            }
            if ($request->getMethod() == 'DELETE' or $request->getParameter('_method' == 'DELETE')) {
                $this->deleteCollectionElement($request);
                return new NoContentResponse();
            }
            if ($request->getMethod() == 'PUT' or $request->getParameter('_method') == 'PUT') {
                if (!$this->isPutRequestValid($request)) {
                    return new PreconditionFailedResponse();
                }
                $this->updateCollectionElement($request);
                return new NoContentResponse();
            }
            if ($request->getMethod() == 'GET') {
                $entity = $this->getCollectionElement($request);
                return new OKORNotModifiedResponse($entity, $request);
            }
            return new MethodNotAllowedResponse(['GET', 'PUT', 'DELETE']);
        }
    }
}