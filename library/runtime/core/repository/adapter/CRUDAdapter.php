<?php
namespace core\repository\adapter
{
    use core\repository\Adapter;
    use ReflectionClass;

    class CRUDAdapter extends Adapter
    {
        protected $recordClass;

        protected $tableName;

        public function __construct(ReflectionClass $recordClass, $tableName)
        {
            $this->recordClass = $recordClass;
            $this->tableName = $tableName;
        }

        // @todo add all possible object mappers including field serialization here
    }
}