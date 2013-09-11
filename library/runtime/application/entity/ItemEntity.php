<?php
namespace application\entity
{
    use core\entity\JSONEntity;
    use core\Request;

    class ItemEntity extends JSONEntity
    {
        private $id;
        private $content;

        public function __construct($id, $content)
        {
            $this->id = $id;
            $this->content = $content;
        }

        public function getKey()
        {
            return __CLASS__;
        }

        public function getData()
        {
            return array(
                'id' => $this->id,
                'content' => $this->content,
            );
        }
    }
}