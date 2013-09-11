<?php
namespace core\entity
{
    class InlineJSONEntity extends JSONEntity
    {
        protected $data;
        
        public function __construct($data) 
        {
            $this->data = $data;
        }
    
        /**
         * Get entity data
         * @return mixed
         */
        protected function getData()
        {
            return $this->data;
        }
    }
}
