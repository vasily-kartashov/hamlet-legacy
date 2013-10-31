<?php
namespace application\db
{
    use SQLite3;

    class DatabaseService
    {
        protected $database;

        public function __construct(SQLite3 $database)
        {
            $this->database = $database;
        }

        public function getItems()
        {
            $result = $this->database->query('SELECT * FROM items');
            $items = array();
            while ($item = $result->fetchArray(SQLITE3_ASSOC)) {
                $items[] = $this->formatItem($item);
            }
            return $items;
        }

        public function insertItem($content, $done)
        {
            $statement = $this->database->prepare('INSERT INTO items (content, done) VALUES (:content, :done)');
            $statement->bindValue(':content', (string) $content, SQLITE3_TEXT);
            $statement->bindValue(':done', (int) $done, SQLITE3_INTEGER);
            $statement->execute();
            return $this->database->lastInsertRowid();
        }

        public function updateItemContent($itemId, $content)
        {
            $statement = $this->database->prepare('UPDATE items SET content = :content WHERE id = :id');
            $statement->bindValue(':content', (string) $content, SQLITE3_TEXT);
            $statement->bindValue(':id', (int) $itemId, SQLITE3_INTEGER);
            $statement->execute();
        }

        public function updateItemStatus($itemId, $done) {
            $statement = $this->database->prepare('UPDATE items SET done = :done WHERE id = :id');
            $statement->bindValue(':done', (int) $done, SQLITE3_INTEGER);
            $statement->bindValue(':id', (int) $itemId, SQLITE3_INTEGER);
            $statement->execute();
        }

        public function itemExists($itemId)
        {
            return is_array($this->getItem($itemId));
        }

        public function deleteItem($itemId)
        {
            $statement = $this->database->prepare('DELETE FROM items WHERE id = :id');
            $statement->bindValue(':id', $itemId, SQLITE3_INTEGER);
            $statement->execute();
        }

        public function getItem($itemId)
        {
            $statement = $this->database->prepare('SELECT * FROM items WHERE id = :id');
            $statement->bindValue(':id', $itemId, SQLITE3_INTEGER);
            $result = $statement->execute();
            return $this->formatItem($result->fetchArray(SQLITE3_ASSOC));
        }

        private function formatItem($item) {
            return array(
                'id' => (int) $item['id'],
                'content' => (string) $item['content'],
                'done' => (bool) $item['done'],
            );
        }
    }
}