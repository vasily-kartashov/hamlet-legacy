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

        public function __construct(DefaultEnvironment $environment)
        {
            $this->environment = $environment;
        }

        public function isPostRequestValid(Request $request)
        {
            return $request->hasParameter('content');
        }

        protected function getCollection(Request $request)
        {
            $items = $this->environment->getDatabaseService()->getItems();
            return new ItemsListEntity($items);
        }

        protected function createCollectionElement(Request $request)
        {
            $content = $request->getParameter('content');
            $id = $this->environment->getDatabaseService()->insertItem($content);

            $location = $this->environment->getCanonicalDomain() . '/items/' . $id;
            $entity = new ItemEntity($id, $content);

            return new LocatedEntity($location, $entity);
        }
    }
}