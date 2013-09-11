<?php
namespace application\locale
{
    class GermanLocale extends DefaultLocale
    {
        public function getGreeting($name)
        {
            return "Guten Tag, {$name}";
        }
    }
}