<?php
namespace application\record
{
    class AccountRecord
    {
        /** @var string */
        private $email;

        /** @var string */
        private $password;

        /**
         * @param string $email
         * @param string $password
         */
        public function __construct($email, $password) {
            assert(is_string($email) and is_string($password));
            $this->email = $email;
            $this->password = $password;
        }

        /**
         * @param string $email
         */
        public function setEmail($email)
        {
            $this->email = $email;
        }

        /**
         * @return string
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * @param string $password
         */
        public function setPassword($password)
        {
            $this->password = $password;
        }

        /**
         * @return string
         */
        public function getPassword()
        {
            return $this->password;
        }
    }
}