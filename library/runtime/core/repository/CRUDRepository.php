<?php
namespace core\repository
{
    use core\repository\adapter\CRUDAdapter;
    use ReflectionClass;

    class CRUDRepository
    {
        protected $recordClass;

        protected $adapter;

        public function __construct(ReflectionClass $recordClass, CRUDAdapter $adapter)
        {
            $this->recordClass = $recordClass;
            $this->adapter = $adapter;
        }
    }
}