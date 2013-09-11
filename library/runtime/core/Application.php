<?php
namespace core
{
    use core\Request;
    use core\Response;
    use Memcached;

    abstract class Application
    {
        /** @var \Memcached */
        private $cache = null;

        /**
         * Find requested resource
         * @param \core\Request $request
         * @return \core\Resource
         */
        abstract protected function findResource(Request $request);

        /**
         * Find response for the specified request
         * @param \core\Request $request
         * @return \core\Response
         */
        public function run(Request $request)
        {
            $resource = $this->findResource($request);
            $response = $resource->getResponse($request);
            return $response;
        }

        /**
         * Return cache server location, for example
         * array('localhost', 11211)
         * @param \core\Request $request
         * @return array
         */
        abstract protected function getCacheServerLocation(Request $request);

        /**
         * Return the connection to memcached server
         * @param \core\Request $request
         * @return \Memcached
         */
        protected function getCache(Request $request)
        {
            if (is_null($this->cache)) {
                $this->cache = new Memcached();
                $cacheLocation = $this->getCacheServerLocation($request);
                $this->cache->addServer($cacheLocation[0], $cacheLocation[1]);
            }
            return $this->cache;
        }

        /**
         * Output the response to the standard output stream
         * @param \core\Request $request
         * @param \core\Response $response
         * @return void
         */
        public function output(Request $request, Response $response)
        {
            $response->output($request, $this->getCache($request));
        }
    }
}