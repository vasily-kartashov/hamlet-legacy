<?php
namespace application\repository
{
    use application\record\NodeClassRecord;
    use core\db\MySQLiProcedure;
    use mysqli;

    class NodeClassesRepository
    {
        /** @var \mysqli */
        private $database;

        /**
         * @param mysqli $database
         */
        public function __construct(mysqli $database) {
            $this->database = $database;
        }

        /**
         * @param int $nodeClassId
         * @return NodeClassRecord
         */
        public function findById($nodeClassId) {
            assert(is_int($nodeClassId));
            $query = '
                SELECT node_class_id, node_class_name, node_class_settings
                  FROM node_classes
                 WHERE node_class_id = ?
            ';
            $procedure = new MySQLiProcedure($this->database, $query);
            $procedure->bindInteger($nodeClassId);
            $row = $procedure->fetchOne();
            return new NodeClassRecord($row['node_class_id'], $row['node_class_name'], $row['node_class_settings']);
        }
    }
}