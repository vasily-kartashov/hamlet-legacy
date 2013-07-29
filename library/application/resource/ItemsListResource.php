<?php
namespace application\resource
{
    use application\entity\ItemsListEntity;
    use core\Request;
    use core\Resource;
    use core\response\MethodNotAllowedResponse;
    use core\response\OKResponse;
    use core\response\PreconditionFailedResponse;

    class ItemsListResource extends Resource
    {
        private function getItems(Request $request)
        {
            return new OKResponse(new ItemsListEntity($request));
        }

        private function addItem(Request $request, $content)
        {
            $items = $request->getSessionParameter('items', array());
            $keys = array_keys($items);
            $id = end($keys) + 1;

            $items[$id] = $content;

            $response = new OKResponse();
            $response->setSessionParameter('items', $items);

            return $response;
        }

        public function getResponse(Request $request)
        {
            if ($request->getMethod() == 'GET') {
                return $this->getItems($request);
            }
            if ($request->getMethod() == 'POST') {
                $content = $request->getParameter('content');
                if (is_null($content)) {
                    return new PreconditionFailedResponse();
                }
                return $this->addItem($request, $content);
            }
            return new MethodNotAllowedResponse(array('GET', 'POST'));
        }
    }
}