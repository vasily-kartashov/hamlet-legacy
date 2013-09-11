<?php
namespace application
{
    use application\environment\DevelopmentEnvironment;
    use application\environment\ProductionEnvironment;
    use core\Application;
    use core\Request;

    class FrontendApplication extends Application
    {
        protected function findResource(Request $request)
        {
            if (is_null($request->getSessionParameter('userId'))) {
                return new LoginResource();
            }
            // get the todos list resource
            // get the redirect to the root
        }

        protected function getCacheServerLocation(Request $request)
        {
            return $this->getEnvironment($request)->getCacheServerLocation();
        }

        private function getEnvironment(Request $request)
        {
            if ($request->getEnvironmentName() == 'localhost') {
                return new DevelopmentEnvironment();
            }
            return new ProductionEnvironment();
        }
    }
}