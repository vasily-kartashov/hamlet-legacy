<?php
/**
 * Created by JetBrains PhpStorm.
 * User: danny
 * Date: 01/10/2013
 * Time: 17:39
 * To change this template use File | Settings | File Templates.
 */

namespace core\request;

{


    class PhpRequest extends Request {

        protected $environment;

        public function __construct($path, $request)
        {

            $defaults = array(

                'headers' => array(),
                'method' => 'GET',
                'parameters' => array(),
                'session' => array(),
                'locale' => 'en',
                'cookies' => array(),
                'ip' => '127.0.0.1',
                'environment' => 'localhost',

            );


            $request = array_merge($request,$defaults);


            $this->environment = $request['environment'];
            $this->headers = $request['headers'];
            $this->method = $request['method'];
            $this->parameters = $request['parameters'];
            $this->path = $path;
            $this->session = $request['session'];
            $this->locale = $request['locale'];
            $this->cookies = $request['cookies'];
            $this->ip = $request['ip'];

        }

        public function getEnvironmentName()
        {
            return $this->environment;
        }

        public function getLocaleNames()
        {
            return array($this->locale);
        }

        public function getLanguageCodes()
        {
            return array('en');
        }

    }

}