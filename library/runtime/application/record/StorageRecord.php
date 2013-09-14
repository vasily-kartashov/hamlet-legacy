<?php
namespace application\record
{
    class StorageRecord
    {
        /** @var int|null */
        private $id;

        /** @var string */
        private $name;

        /** @var boolean */
        private $enabled;

        /** @var AccountRecord[] */
        private $accounts;

        /**
         * @param int|null $id
         * @param string $name
         * @param boolean $enabled
         * @param AccountRecord[] $accounts
         */
        public function __construct($id, $name, $enabled, array $accounts) {
            assert(is_null($id) or is_int($id));
            assert(is_string($name));
            assert(is_bool($enabled));
            assert(count($accounts) == 0 or $accounts[0] instanceof AccountRecord);
            $this->id = $id;
            $this->name = $name;
            $this->enabled = $enabled;
            $this->accounts = $accounts;
        }

        public function setAccounts($accounts)
        {
            $this->accounts = $accounts;
        }

        public function getAccounts()
        {
            return $this->accounts;
        }

        /**
         * @param boolean $enabled
         */
        public function setEnabled($enabled)
        {
            $this->enabled = $enabled;
        }

        /**
         * @return boolean
         */
        public function getEnabled()
        {
            return $this->enabled;
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
    }
}