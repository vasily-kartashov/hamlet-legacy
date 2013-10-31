<?php
namespace core\request
{
    use core\entity\Entity;
    use Memcached;

    abstract class Request
    {
        protected $headers;
        protected $method;
        protected $parameters;
        protected $path;
        protected $session;
        protected $cookies;
        protected $ip;

        /**
         * Get current environment name
         * @return string
         */
        abstract public function getEnvironmentName();

        /**
         * Get current IP address
         * @return string
         */
        public function getIPAddress()
        {
            return $this->ip;
        }

        /**
         * Get plain header value
         * @param string $name
         * @return string
         */
        public function getHeader($name)
        {
            assert(is_string($name));
            if (isset($this->headers[$name])) {
                return $this->headers[$name];
            }
            return null;
        }

        /**
         * Get accepted language codes
         * @return array
         */
        abstract public function getLanguageCodes();

        /**
         * Get HTTP method
         * @return string
         */
        public function getMethod()
        {
            return $this->method;
        }

        /**
         * Get request parameter value
         * @param string $name
         * @param string $defaultValue
         * @return null
         */
        public function getParameter($name, $defaultValue = null)
        {
            assert(is_string($name));
            if (isset($this->parameters[$name])) {
                return urldecode($this->parameters[$name]);
            }
            return $defaultValue;
        }

        /**
         * @param string $name
         * @return bool
         */
        public function hasParameter($name)
        {
            assert(is_string($name));
            return isset($this->parameters[$name]);
        }

        /**
         * Get all request parameters
         * @return string[]
         */
        public function getParameters()
        {
            return $this->parameters;
        }

        /**
         * Check if the path starts with specified string
         * @param string $prefix
         * @return bool
         */
        public function pathStartsWith($prefix)
        {
            assert(is_string($prefix));
            $length = strlen($prefix);
            return substr($this->path, 0, $length) == $prefix;
        }

        /**
         * Check if the path starts with specified pattern, Returns false if no match, true if match without capture,
         * and array with matched tokens if used with capturing pattern
         * @param $pattern
         * @return array|bool
         */
        public function pathStartsWithPattern($pattern)
        {
            assert(is_string($pattern));
            $pathTokens = explode('/', $this->path);
            $patternTokens = explode('/', $pattern);
            return $this->matchTokens($pathTokens, $patternTokens);
        }

        /**
         * Check if the request path exactly matches the specified path
         * @param string $path
         * @return bool
         */
        public function pathMatches($path)
        {
            assert(is_string($path));
            return $this->path == (string) $path;
        }

        /**
         * Check if the path matches specified pattern. Returns false if no match, true if match without capture,
         * and array with matched tokens if used with capturing pattern
         * @param string $pattern
         * @return array|bool
         */
        public function pathMatchesPattern($pattern)
        {
            assert(is_string($pattern));
            $pathTokens = explode('/', $this->path);
            $patternTokens = explode('/', $pattern);
            if (count($pathTokens) != count($patternTokens)) {
                return false;
            }
            return $this->matchTokens($pathTokens, $patternTokens);
        }

        /**
         * Compare path tokens side by side. Returns false if no match, true if match without capture,
         * and array with matched tokens if used with capturing pattern
         * @param array $pathTokens
         * @param array $patternTokens
         * @return array|bool
         */
        protected function matchTokens(array $pathTokens, array $patternTokens)
        {
            $matches = array();
            for ($i = 1; $i < count($patternTokens); $i++) {
                $pathToken = $pathTokens[$i];
                $patternToken = $patternTokens[$i];
                if ($patternToken == '*') {
                    continue;
                }
                if ($patternToken[0] == '{') {
                    $matches[substr($patternToken, 1, -1)] = $pathToken;
                } else {
                    if (urldecode($pathToken) != $patternToken) {
                        return false;
                    }
                }
            }
            return count($matches) == 0 ? true : array_map('urldecode', $matches);
        }

        /**
         * Check if current request path matches regular expression. Returns false if no match, true if match without capture,
         * and array with matched tokens if used with capturing pattern
         * @param string $pattern
         * @return array|bool
         */
        public function pathMatchesRegExp($pattern)
        {
            assert(is_string($pattern));
            $re = '#' . preg_replace('/\{([^\}]+)\}/', '(?<\1>[^\}]+)', $pattern) . '(\.(\w+)($|\?))?#';
            if (preg_match($re, $this->path, $matches)) {
                $result = array();
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        $result[$key] = $value;
                    }
                }
                return count($result) > 0 ? $result : true;
            }
            return false;
        }

        /**
         * Get session parameter
         * @param string $name
         * @param string $defaultValue
         * @return string
         */
        public function getSessionParameter($name, $defaultValue = null)
        {
            assert(is_string($name));
            if (isset($this->session[$name])) {
                return $this->session[$name];
            }
            return $defaultValue;
        }

        /**
         * Check if the request precondition is fulfilled
         * @param Entity $entity
         * @param Memcached $cache
         * @return bool
         */
        public function preconditionFulfilled(Entity $entity, Memcached $cache)
        {
            $matchHeader = $this->getHeader('If-Match');
            $modifiedSinceHeader = $this->getHeader('If-Modified-Since');
            $noneMatchHeader = $this->getHeader('If-None-Match');
            $unmodifiedSinceHeader = $this->getHeader('If-Unmodified-Since');

            if (is_null($matchHeader) and is_null($modifiedSinceHeader) and is_null($noneMatchHeader) and is_null($unmodifiedSinceHeader)) {
                return true;
            }

            $cacheEntry = $entity->load($cache);

            $tag = $cacheEntry['tag'];
            $lastModified = $cacheEntry['modified'];

            if (!is_null($matchHeader)) {
                if ($tag == $matchHeader) {
                    return true;
                }
            }

            if (!is_null($modifiedSinceHeader)) {
                if ($lastModified > strtotime($modifiedSinceHeader)) {
                    return true;
                }
            }

            if (!is_null($noneMatchHeader)) {
                if ($tag != $noneMatchHeader) {
                    return true;
                }
            }

            if (!is_null($unmodifiedSinceHeader)) {
                if ($lastModified < strtotime($unmodifiedSinceHeader)) {
                    return true;
                }
            }

            return false;
        }

        /**
         * Parse header
         * @param string $headerString
         * @return string[]
         */
        protected function parseHeader($headerString)
        {
            assert(is_string($headerString));
            $ranges = explode(',', trim(strtolower($headerString)));
            foreach ($ranges as $i => $range) {
                $tokens = explode(';', trim($range), 2);
                $type = trim(array_shift($tokens));
                $priority = 1000 - $i;
                foreach ($tokens as $token) {
                    if (($position = strpos($token, '=')) !== false) {
                        $key = substr($token, 0, $position);
                        $value = substr($token, $position + 1);
                        if (trim($key) == 'q') {
                            $priority = 1000 * $value - $i;
                            break;
                        }
                    }
                }
                $result[$type] = $priority;
            }
            arsort($result);
            return array_keys($result);
        }

        /**
         * Get cookie value
         * @param string $name
         * @param string $defaultValue
         * @return string
         */
        public function getCookie($name, $defaultValue = null)
        {
            assert(is_string($name));
            if (isset($this->cookies[$name])) {
                return $this->cookies[$name];
            }
            return $defaultValue;
        }
    }
}