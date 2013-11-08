<?php
namespace application
{
    use application\entity\HomePageEntity;
    use application\environment\DefaultEnvironment;
    use application\environment\ProductionEnvironment;
    use application\resource\ItemResource;
    use application\resource\ItemsListResource;
    use core\Application;
    use core\request\Request;
    use core\resource\EntityResource;
    use core\resource\RedirectResource;

    class FrontendApplication extends Application
    {
        protected function findResource(Request $request)
        {
            $environment = $this->getEnvironment($request);

            if ($authorization = $request->getHeader('Authorization')) {
                list($_, $accessToken) = explode(' ', $authorization, 2);
                $uid = $this->getUid($accessToken);
                if ($uid) {
                    if ($request->pathMatchesPattern('/items')) {
                        return new ItemsListResource($environment, $uid);
                    }
                    if ($matches = $request->pathMatchesPattern('/items/{itemId}')) {
                        return new ItemResource($environment, $matches['itemId'], $uid);
                    }
                    if ($matches = $request->pathMatchesPattern('/items/{itemId}/{operation}')) {
                        return new ItemResource($environment, $matches['itemId'], $uid, $matches['operation']);
                    }
                } else {
                    // @todo add not allowed response
                }
            }

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

        protected function getUid($accessToken) {
            $content = json_decode(file_get_contents("https://graph.facebook.com/me?access_token={$accessToken}"));
            return $content->id;
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