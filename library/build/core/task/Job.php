<?php
namespace core\task
{
    abstract class Job
    {
        /**
         * @return \core\task\Task[]
         */
        abstract protected function getTasks();

        /**
         * @return void
         */
        public function execute()
        {
            $tasks = $this->getTasks();
            foreach ($tasks as $task) {
                $task->execute();
            }
        }
    }
}