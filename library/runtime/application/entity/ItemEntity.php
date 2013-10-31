<?php
namespace application\entity
{
    use core\entity\JSONEntity;
    use core\Request;

    class ItemEntity extends JSONEntity
    {
        private $id;
        private $content;
        private $done;

        public function __construct($id, $content, $done)
        {
            $this->id = $id;
            $this->content = (string) $content;
            $this->done = (bool) $done;
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
                'done' => $this->done,
            );
        }
    }
}