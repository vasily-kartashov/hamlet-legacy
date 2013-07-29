<?php
namespace application
{
    use application\entity\HomePageEntity;
    use application\environment\DefaultEnvironment;
    use application\environment\ProductionEnvironment;
    use application\resource\ItemResource;
    use application\resource\ItemsListResource;
    use core\Application;
    use core\Request;
    use core\resource\EntityResource;
    use core\resource\RedirectResource;

    class FrontendApplication extends Application
    {
        protected function findResource(Request $request)
        {
            if ($request->pathMatchesPattern('/items')) {
                return new ItemsListResource();
            }
            if ($matches = $request->pathMatchesPattern('/items/{itemId}')) {
                return new ItemResource($matches['itemId']);
            }
            $environment = $this->getEnvironment($request);
            if ($matches = $request->pathStartsWithPattern('/{localeName}')) {
                if ($environment->localeExists($matches['localeName'])) {
                    $locale = $environment->getLocale($matches['localeName']);
                    if ($request->pathMatchesPattern('/*')) {
                        return new EntityResource(new HomePageEntity($locale));
                    }
                }
            }
            return new RedirectResource($environment->getCanonicalDomain() . '/en');
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