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
                $items[] = $item;
            }
            return $items;
        }

        public function insertItem($content)
        {
            $statement = $this->database->prepare('INSERT INTO items (content) VALUES (:content)');
            $statement->bindValue(':content', $content, SQLITE3_TEXT);
            $statement->execute();
            return $this->database->lastInsertRowid();
        }

        public function updateItem($itemId, $content)
        {
            $statement = $this->database->prepare('UPDATE items SET content = :content WHERE id = :id');
            $statement->bindValue(':content', $content, SQLITE3_TEXT);
            $statement->bindValue(':id', $itemId, SQLITE3_INTEGER);
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
            $statement = $this->database->prepare('SELECT id FROM items WHERE id = :id');
            $statement->bindValue(':id', $itemId, SQLITE3_INTEGER);
            $result = $statement->execute();
            return $result->fetchArray();
        }
    }
}