<?php
namespace application\entity
{
    use core\entity\JSONEntity;
    use core\Request;

    class ItemEntity extends JSONEntity
    {
        protected $item;

        public function __construct($item)
        {
            $this->item = $item;
        }

        public function getKey()
        {
            return __CLASS__;
        }

        public function getData()
        {
            return $this->item;
        }
    }
}