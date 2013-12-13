<?php
namespace application\task
{
    use core\Application;
    use core\request\PhpRequest;
    use core\task\Task;

    class StaticPageGenerationTask extends Task
    {
        protected $application;
        protected $path;
        protected $request;
        protected $outputFilePath;

        public function __construct(Application $application, $path, $request, $outputFilePath)
        {
            $this->application = $application;
            $this->path = $path;
            $this->request = $request;
            $this->outputFilePath = $outputFilePath;
        }

        public function execute()
        {
            $request = new PhpRequest($this->path, $this->request);
            $response = $this->application->run($request);
            $entity = $response->getEntity();
            file_put_contents($this->outputFilePath,$entity->getContent());
        }
    }
}