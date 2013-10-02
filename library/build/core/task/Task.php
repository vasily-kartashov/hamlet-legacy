<?php
namespace core\task
{
    abstract class Task
    {
        /**
         * @return void
         */
        abstract public function execute();
    }
}