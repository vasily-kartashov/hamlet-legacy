<?php
namespace application\environment
{
    class DevelopmentEnvironment extends Environment
    {

        public function useConcatenatedJavascript()
        {
            return false;
        }
    }
}