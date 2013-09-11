<?php

namespace core
{
    abstract class Template
    {
        /** @var mixed */
        protected $data;

        /**
         * Constructor
         * @param mixed $data
         */
        public function __construct($data)
        {
            $this->data = $data;
        }

        /**
         * Get template data
         * @return mixed
         */
        public function getData()
        {
            return $this->data;
        }

        /**
         * Generate template output
         * @return string
         */
        abstract public function generateOutput();
    }
}