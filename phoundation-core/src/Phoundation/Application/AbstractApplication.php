<?php
namespace Phoundation\Application
{
    use Phoundation\Request\RequestInterface;
    use Phoundation\Response\ResponseInterface;

    abstract class AbstractApplication
    {
        /** @var \Memcached */
        private $cache = null;

        /**
         * Find requested resource
         * @param \Phoundation\Request\RequestInterface $request
         * @return \Phoundation\Resource\ResourceInterface
         */
        abstract protected function findResource(RequestInterface $request);

        /**
         * Find response for the specified request
         * @param \Phoundation\Request\RequestInterface $request
         * @return \Phoundation\Response\ResponseInterface
         */
        public function run(RequestInterface $request)
        {
            $resource = $this->findResource($request);
            $response = $resource->getResponse($request);
            return $response;
        }

        /**
         * Return cache server location, for example
         * array('localhost', 11211)
         * @param \Phoundation\Request\RequestInterface $request
         * @return \Phoundation\Cache\CacheInterface
         */
        abstract protected function getCache(RequestInterface $request);

        /**
         * Output the response to the standard output stream
         * @param \Phoundation\Request\RequestInterface $request
         * @param \Phoundation\Response\ResponseInterface $response
         * @return void
         */
        public function output(RequestInterface $request, ResponseInterface $response)
        {
            $response->output($request, $this->getCache($request));
        }
    }
}