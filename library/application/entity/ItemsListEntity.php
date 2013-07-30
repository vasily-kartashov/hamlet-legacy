<?php
namespace application\entity
{
    use core\entity\JSONEntity;
    use core\Request;

    class ItemsListEntity extends JSONEntity
    {
        protected $items;

        public function __construct(array $items)
        {
            $this->items = $items;
        }

        public function getKey()
        {
            return __CLASS__;
        }

        public function getData()
        {
            return $this->items;
        }
    }
}