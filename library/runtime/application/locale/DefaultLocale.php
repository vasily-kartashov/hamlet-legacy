<?php
namespace application\locale
{
    class DefaultLocale
    {
        public function getGreeting($name)
        {
            return "Hello, {$name}";
        }
    }
}