<?php
namespace application
{
    use application\entity\HomePageEntity;
    use application\environment\DefaultEnvironment;
    use application\environment\ProductionEnvironment;
    use core\Application;
    use core\request\Request;
    use core\resource\EntityResource;
    use core\resource\RedirectResource;

    class FrontendApplication extends Application
    {
        protected function findResource(Request $request)
        {
            $environment = $this->getEnvironment($request);

            if ($matches = $request->pathStartsWithPattern('/{localeName}')) {
                if ($environment->localeExists($matches['localeName'])) {
                    $locale = $environment->getLocale($matches['localeName']);
                    if ($request->pathMatchesPattern('/*')) {
                        return new EntityResource(new HomePageEntity($locale));
                    }
                }
            }
            return new RedirectResource('http://' . $request->getEnvironmentName() . '/en');
        }

        protected function getCacheServerLocation(Request $request)
        {
            return $this->getEnvironment($request)->getCacheServerLocation();
        }

        private function getEnvironment(Request $request)
        {
            if ($request->getEnvironmentName() == 'foundation.dev') {
                return new DefaultEnvironment();
            }
            return new ProductionEnvironment();
        }
    }
}