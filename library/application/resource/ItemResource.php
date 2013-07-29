<?php
namespace application\resource
{
    use application\entity\ItemEntity;
    use application\entity\ItemsListEntity;
    use core\Request;
    use core\Resource;
    use core\response\MethodNotAllowedResponse;
    use core\response\NotFoundResponse;
    use core\response\OKResponse;
    use core\response\PreconditionFailedResponse;

    class ItemResource extends Resource
    {
        private $itemId;

        public function __construct($itemId)
        {
            $this->itemId = $itemId;
        }

        private function getItem(Request $request)
        {
            $items = $request->getSessionParameter('items', array());
            if (!isset($items[$this->itemId])) {
                return new NotFoundResponse(new ItemsListEntity($request));
            }
            return new OKResponse(new ItemEntity($items[$this->itemId]));
        }

        private function deleteItem(Request $request)
        {
            $items = $request->getSessionParameter('items', array());
            if (!isset($items[$this->itemId])) {
                return new NotFoundResponse(new ItemsListEntity($request));
            }
            unset($items[$this->itemId]);

            $response = new OKResponse();
            $response->setSessionParameter('items', $items);

            return $response;
        }

        private function putItem(Request $request, $content)
        {
            $items = $request->getSessionParameter('items', array());
            if (!isset($items[$this->itemId])) {
                return new NotFoundResponse(new ItemsListEntity($request));
            }
            $items[$this->itemId] = $content;

            $response = new OKResponse();
            $response->setSessionParameter('items', $items);

            return $response;
        }

        public function getResponse(Request $request)
        {
            if ($request->getMethod() == 'GET') {
                return $this->getItem($request);
            }
            if ($request->getMethod() == 'DELETE') {
                return $this->deleteItem($request);
            }
            if ($request->getMethod() == 'PUT') {
                $content = $request->getParameter('content');
                if (is_null($content)) {
                    return new PreconditionFailedResponse();
                }
                return $this->putItem($request, $content);
            }
            return new MethodNotAllowedResponse(array('GET', 'PUT', 'DELETE'));
        }
    }
}