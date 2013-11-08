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

        public function getItems($uid)
        {
            $statement = $this->database->prepare('SELECT * FROM items WHERE uid = :uid');
            $statement->bindValue(':uid', (int) $uid, SQLITE3_TEXT);
            $result = $statement->execute();

            $items = array();
            while ($item = $result->fetchArray(SQLITE3_ASSOC)) {
                $items[] = $this->formatItem($item);
            }
            return $items;
        }

        public function insertItem($uid, $content, $done)
        {
            $statement = $this->database->prepare('INSERT INTO items (uid, content, done) VALUES (:uid, :content, :done)');
            $statement->bindValue(':uid', (string) $uid, SQLITE3_TEXT);
            $statement->bindValue(':content', (string) $content, SQLITE3_TEXT);
            $statement->bindValue(':done', (int) $done, SQLITE3_INTEGER);
            $statement->execute();
            return $this->database->lastInsertRowid();
        }

        public function updateItemContent($itemId, $uid, $content)
        {
            $statement = $this->database->prepare('UPDATE items SET content = :content WHERE id = :id AND uid = :uid');
            $statement->bindValue(':content', (string) $content, SQLITE3_TEXT);
            $statement->bindValue(':id', (int) $itemId, SQLITE3_INTEGER);
            $statement->bindValue(':uid', (int) $uid, SQLITE3_TEXT);
            $statement->execute();
        }

        public function updateItemStatus($itemId, $uid, $done) {
            $statement = $this->database->prepare('UPDATE items SET done = :done WHERE id = :id AND uid = :uid');
            $statement->bindValue(':done', (int) $done, SQLITE3_INTEGER);
            $statement->bindValue(':id', (int) $itemId, SQLITE3_INTEGER);
            $statement->bindValue(':uid', (int) $uid, SQLITE3_TEXT);
            $statement->execute();
        }

        public function itemExists($itemId, $uid)
        {
            return is_array($this->getItem($itemId, $uid));
        }

        public function deleteItem($itemId, $uid)
        {
            $statement = $this->database->prepare('DELETE FROM items WHERE id = :id AND uid = :uid');
            $statement->bindValue(':id', (int) $itemId, SQLITE3_INTEGER);
            $statement->bindValue(':uid', (string) $uid, SQLITE3_TEXT);
            $statement->execute();
        }

        public function getItem($itemId, $uid)
        {
            $statement = $this->database->prepare('SELECT * FROM items WHERE id = :id AND uid = :uid');
            $statement->bindValue(':id', (int) $itemId, SQLITE3_INTEGER);
            $statement->bindValue(':uid', (string) $uid, SQLITE3_TEXT);
            $result = $statement->execute();
            return $this->formatItem($result->fetchArray(SQLITE3_ASSOC));
        }

        private function formatItem($item) {
            return array(
                'id' => (int) $item['id'],
                'uid' => (int) $item['uid'],
                'content' => (string) $item['content'],
                'done' => (bool) $item['done'],
            );
        }
    }
}