<?php
namespace application\resource
{
    use application\entity\ItemEntity;
    use application\environment\DefaultEnvironment;
    use core\request\Request;
    use core\resource\CollectionElementResource;

    class ItemResource extends CollectionElementResource
    {
        private $environment;
        private $itemId;

        public function __construct(DefaultEnvironment $environment, $itemId)
        {
            $this->environment = $environment;
            $this->itemId = $itemId;
        }

        protected function isPutRequestValid(Request $request)
        {
            return $request->hasParameter('content');
        }

        protected function collectionElementExists(Request $request)
        {
            return $this->environment->getDatabaseService()->itemExists($this->itemId);
        }

        protected function deleteCollectionElement(Request $request)
        {
            $this->environment->getDatabaseService()->deleteItem($this->itemId);
        }

        protected function updateCollectionElement(Request $request)
        {
            $this->environment->getDatabaseService()->updateItem($this->itemId, $request->getParameter('content'));
        }

        protected function getCollectionElement(Request $request)
        {
            $item = $this->environment->getDatabaseService()->getItem($this->itemId);
            return new ItemEntity($item['id'], $item['content']);
        }
    }
}