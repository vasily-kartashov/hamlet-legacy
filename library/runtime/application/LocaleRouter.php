<?php
namespace application
{

    use application\entity\HomePageEntity;
    use application\environment\Environment;
    use core\request\Request;
    use core\resource\EntityResource;
    use core\resource\RedirectResource;
    use application\locale\Locale;

    class LocaleRouter
    {
        protected $locale;
        protected $localeName;
        protected $environment;

        public function __construct(Locale $locale,Environment $environment)
        {
            $this->locale = $locale;
            $this->localeName = $locale->getLocaleName();
            $this->environment = $environment;
        }

        /**
         * @param Request $request
         * @return Resource
         */

        public function findResource(Request $request)
        {

            if ($request->pathMatches($this->getHomepagePattern())) {
                return new EntityResource(new HomePageEntity($this->locale,$this->environment));
            }

            return new RedirectResource($this->getHomepagePath());
        }

        public function getHomepagePath()
        {
            return $this->getHomepagePattern();
        }

        protected function getHomepagePattern()
        {
            return '/' . $this->localeName ;
        }

        /**
         * Currently just lower case and remove spaces. should be urlencoded, but this ruins path matching
         * @param $token
         * @return String
         */
        protected function translatePath($token)
        {
            return strtolower(str_replace(' ','-',$this->locale->translate($token)));
        }

    }
}