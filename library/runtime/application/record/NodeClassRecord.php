<?php
namespace application\record
{
    class NodeClassRecord
    {
        /** @var int|null */
        private $id;

        /** @var string */
        private $name;

        /** @var array */
        private $settings;

        /**
         * @param int|null $id
         * @param string $name
         * @param array $settings
         */
        public function __construct($id, $name, array $settings) {
            assert(is_null($id) or is_int($id));
            assert(is_string($name));
            $this->id = $id;
            $this->name = $name;
            $this->settings = $settings;
        }

        /**
         * @return int|null
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @param string $name
         */
        public function setName($name)
        {
            $this->name = $name;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @param array $settings
         */
        public function setSettings($settings)
        {
            $this->settings = $settings;
        }

        /**
         * @return array
         */
        public function getSettings()
        {
            return $this->settings;
        }
    }
}