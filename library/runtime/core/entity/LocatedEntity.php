<?php
namespace core\entity
{
    class LocatedEntity
    {
        protected $location;
        protected $entity;

        public function __construct($location, Entity $entity)
        {
            $this->location = (string) $location;
            $this->entity = $entity;
        }

        public function getLocation()
        {
            return $this->location;
        }

        public function getEntity()
        {
            return $this->entity;
        }
    }
}