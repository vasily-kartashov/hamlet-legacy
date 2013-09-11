<?php
namespace core
{
    use core\Request;
    use Memcached;

    abstract class Entity
    {
        /** @var array */
        private $cacheEntry = null;

        /**
         * Get string representation of the entity
         * @return string
         */
        abstract public function getContent();

        /**
         * Get cache key of the entity
         * @return string
         */
        abstract public function getKey();

        /**
         * Get media type of the entity
         * @return string
         */
        abstract public function getMediaType();

        /**
         * Get caching time in seconds. Default caching time is 0
         * @return int
         */
        public function getCachingTime()
        {
            return 0;
        }

        /**
         * Get content language
         * @return null
         */
        public function getContentLanguage()
        {
            return null;
        }

        /**
         * Load entity cache entry
         * @param Memcached $cache
         * @return array
         */
        public function load(Memcached $cache)
        {
            if (!is_null($this->cacheEntry)) {
                return $this->cacheEntry;
            }

            $key = $this->getKey();
            $this->cacheEntry = $cache->get($key);
            $found = $cache->getResultCode() != Memcached::RES_NOTFOUND;
            $now = time();
            $expires = isset($this->cacheEntry['expires']) ? $this->cacheEntry['expires'] : 0;

            if (!$found or $now >= $expires) {
                $content = $this->getContent();
                $tag = md5($content);
                if (is_array($this->cacheEntry) and $tag == $this->cacheEntry['tag']) {
                    $this->cacheEntry['expires'] = $now + $this->getCachingTime();
                } else {
                    $this->cacheEntry = array(
                        'content' => $content,
                        'tag' => $tag,
                        'digest' => base64_encode(pack('H*', md5($content))),
                        'length' => strlen($content),
                        'modified' => $now,
                        'expires' => $now + $this->getCachingTime(),
                    );
                }
                $cache->set($key, $this->cacheEntry);
            }

            return $this->cacheEntry;
        }
    }
}