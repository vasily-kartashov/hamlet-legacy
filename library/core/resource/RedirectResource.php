<?php
namespace core\resource
{
    use core\Request;
    use core\Resource;
    use core\response\MethodNotAllowedResponse;
    use core\response\TemporaryRedirectResponse;

    class RedirectResource extends Resource
    {
        protected $url;

        public function __construct($url)
        {
            $this->url = $url;
        }

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