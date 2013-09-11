<?php
namespace application\environment
{
    class DevelopmentEnvironment
    {
        public function getCacheServerLocation()
        {
            return array('localhost', 11211);
        }
    }
}