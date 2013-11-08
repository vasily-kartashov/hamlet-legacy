<?php
namespace application\resource
{
    use application\entity\ItemEntity;
    use application\environment\DefaultEnvironment;
    use core\request\Request;
    use core\resource\CollectionElementResource;
    use core\response\BadRequestResponse;
    use core\response\MethodNotAllowedResponse;
    use core\response\NoContentResponse;

    class ItemResource extends CollectionElementResource
    {
        private $environment;
        private $itemId;
        private $operation;

        public function __construct(DefaultEnvironment $environment, $itemId, $uid, $operation = null)
        {
            $this->environment = $environment;
            $this->itemId = $itemId;
            $this->uid = $uid;
            $this->operation = $operation;
        }

        protected function isPutRequestValid(Request $request)
        {
            return $request->hasParameter('content');
        }

        protected function collectionElementExists(Request $request)
        {
            return $this->environment->getDatabaseService()->itemExists($this->itemId, $this->uid);
        }

        protected function deleteCollectionElement(Request $request)
        {
            $this->environment->getDatabaseService()->deleteItem($this->itemId, $this->uid);
        }

        protected function updateCollectionElement(Request $request)
        {
            $this->environment->getDatabaseService()->updateItemContent($this->itemId, $this->uid, $request->getParameter('content'));
        }

        protected function getCollectionElement(Request $request)
        {
            $item = $this->environment->getDatabaseService()->getItem($this->itemId, $this->uid);
            return new ItemEntity($item['id'], $item['content'], $item['done']);
        }

        protected function updateItem($itemId, $done)
        {
            $this->environment->getDatabaseService()->updateItemStatus($itemId, $this->uid, $done);
            var_dump($itemId);
            var_dump($this->uid);
            var_dump($done);
            die();
            return new NoContentResponse();
        }

        public function getResponse(Request $request)
        {
            if ($this->operation == null) {
                return parent::getResponse($request);
            }
            if ($request->getMethod() == 'POST') {
                switch ($this->operation) {
                    case 'do':
                        return $this->updateItem($this->itemId, true);
                    case 'undo':
                        return $this->updateItem($this->itemId, false);
                    default:
                        return new BadRequestResponse();
                }
            } else {
                return new MethodNotAllowedResponse(['POST']);
            }
        }
    }
}