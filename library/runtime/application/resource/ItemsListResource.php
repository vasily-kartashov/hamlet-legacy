<?php
namespace application\resource
{
    use application\entity\ItemEntity;
    use application\entity\ItemsListEntity;
    use application\environment\DefaultEnvironment;
    use core\entity\LocatedEntity;
    use core\request\Request;
    use core\resource\CollectionResource;

    class ItemsListResource extends CollectionResource
    {
        private $environment;
        private $uid;

        public function __construct(DefaultEnvironment $environment, $uid)
        {
            $this->environment = $environment;
            $this->uid = $uid;
        }

        public function isPutRequestValid(Request $request)
        {
            return $request->hasParameter('content');
        }

        protected function getCollection(Request $request)
        {
            $items = $this->environment->getDatabaseService()->getItems($this->uid);
            return new ItemsListEntity($items);
        }

        protected function createCollectionElement(Request $request)
        {
            $content = $request->getParameter('content');
            $id = $this->environment->getDatabaseService()->insertItem($this->uid, $content, false);

            $location = $this->environment->getCanonicalDomain() . '/items/' . $id;
            $entity = new ItemEntity($id, $content, false);

            return new LocatedEntity($location, $entity);
        }
    }
}