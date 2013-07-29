<?php
namespace application\entity
{
    use core\entity\JSONEntity;
    use core\Request;

    class ItemsListEntity extends JSONEntity
    {
        protected $request;

        public function __construct(Request $request)
        {
            $this->request = $request;
        }

        public function getKey()
        {
            return __CLASS__;
        }

        public function getData()
        {
            return $this->request->getSessionParameter('items', array());
        }
    }
}