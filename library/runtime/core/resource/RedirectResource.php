<?php
namespace core\resource
{
    use core\request\Request;
    use core\response\MethodNotAllowedResponse;
    use core\response\TemporaryRedirectResponse;

    class RedirectResource extends Resource
    {
        protected $url;

        /**
         * @param string $url
         */
        public function __construct($url)
        {
            assert(is_string($url));
            $this->url = $url;
        }

        /**
         * @param Request $request
         * @return MethodNotAllowedResponse|TemporaryRedirectResponse
         */
        public function getResponse(Request $request)
        {
            if ($request->getMethod() == 'GET') {
                $response = new TemporaryRedirectResponse($this->url);
                $response->setHeader('Cache-Control', 'private');
                return $response;
            }
            return new MethodNotAllowedResponse(array('GET'));
        }
    }
}