<?php
namespace core\request
{
    use UnitTestCase;

    abstract class RequestTestCase extends UnitTestCase
    {
        /**
         * @param $path
         * @return \core\request\Request
         */
        protected abstract function createRequest($path);

        public function testPathMatches()
        {
            $request = $this->createRequest('/');
            $this->assertTrue($request->pathMatches('/'));

            $request = $this->createRequest('/test');
            $this->assertTrue($request->pathMatches('/test'));

            $request = $this->createRequest('/test/');
            $this->assertFalse($request->pathMatches('/'));

            $request = $this->createRequest('/привет из ниоткуда');
            $this->assertTrue($request->pathMatches('/привет из ниоткуда'));

            $request = $this->createRequest('/%D0%BF%D1%80%D0%B8%D0%B2%D0%B5%D1%82%20%D0%B8%D0%B7%20%D0%BD%D0%B8%D0%BE%D1%82%D0%BA%D1%83%D0%B4%D0%B0');
            $this->assertTrue($request->pathMatches('/привет из ниоткуда'));
        }

        public function testPathStartsWith()
        {
            $request = $this->createRequest('/hello');
            $this->assertTrue($request->pathStartsWith('/'));

            $request = $this->createRequest('/test?city=LON');
            $this->assertTrue($request->pathStartsWith('/test'));

            $request = $this->createRequest('/test');
            $this->assertTrue($request->pathStartsWith('/test'));

            $request = $this->createRequest('/привет из ниоткуда hello');
            $this->assertTrue($request->pathStartsWith('/привет из ниоткуда'));

            $request = $this->createRequest('/%D0%BF%D1%80%D0%B8%D0%B2%D0%B5%D1%82%20%D0%B8%D0%B7%20%D0%BD%D0%B8%D0%BE%D1%82%D0%BA%D1%83%D0%B4%D0%B0/?refresh');
            $this->assertTrue($request->pathStartsWith('/привет из ниоткуда'));
        }
    }
}