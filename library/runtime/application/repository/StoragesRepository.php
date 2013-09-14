<?php
namespace application\repository
{
    use application\record\AccountRecord;
    use application\record\StorageRecord;
    use core\db\MySQLiProcedure;
    use mysqli;

    class StoragesRepository
    {
        /** @var \mysqli */
        private $database;

        /**
         * @param mysqli $database
         */
        public function __construct(mysqli $database) {
            assert(!is_null($database));
            $this->database = $database;
        }

        /**
         * @return StorageRecord[]
         */
        public function findAll() {
            $query = '
                SELECT storage_id, storage_name, storage_enabled, storage_accounts
                  FROM storages
            ';
            $procedure = new MySQLiProcedure($this->database, $query);
            $storages = [];
            foreach ($procedure->fetchAll() as $row) {
                $accounts = [];
                foreach (json_decode($row['storage_accounts']) as $account) {
                    $accounts[] = new AccountRecord($account['email'], $account['password']);
                }
                $storages[] = new StorageRecord($row['storage_id'], $row['storage_name'], $row['storage_enabled'], $accounts);
            }
            return $storages;
        }

        /**
         * @param StorageRecord $storage
         * @return StorageRecord
         */
        public function insert(StorageRecord $storage) {
            assert($storage->getId() == 0);
            $query = '
                INSERT INTO storages (storage_name, storage_enabled, storage_accounts)
                     VALUES (?, ?, ?)
            ';
            $procedure = new MySQLiProcedure($this->database, $query);
            $procedure->bindString($storage->getName());
            $procedure->bindInteger((int) $storage->getEnabled());
            $procedure->bindString($this->serializeAccounts($storage->getAccounts()));
            $procedure->execute();
            assert($procedure->getInsertId() > 0);
            return new StorageRecord($procedure->getInsertId(), $storage->getName(), $storage->getEnabled(), $storage->getAccounts());
        }

        /**
         * @param StorageRecord $storage
         */
        public function save(StorageRecord $storage) {
            assert($storage->getId() > 0);
            $query = '
                UPDATE TABLE storages
                         SET storage_name = ?, storage_enabled = ?, storage_accounts = ?
                       WHERE storage_id = ?
            ';

            $procedure = new MySQLiProcedure($this->database, $query);
            $procedure->bindString($storage->getName());
            $procedure->bindInteger((int) $storage->getEnabled());
            $procedure->bindString($this->serializeAccounts($storage->getAccounts()));
            $procedure->bindInteger($storage->getId());
            $procedure->execute();
        }

        /**
         * @param AccountRecord[] $accounts
         * @return string
         */
        private function serializeAccounts(array $accounts) {
            assert(count($accounts) == 0 or $accounts[0] instanceof AccountRecord);
            $result = [];
            foreach ($accounts as $account) {
                $result[] = [
                    'email' => $account->getEmail(),
                    'password' => $account->getPassword(),
                ];
            }
            return json_encode($result);
        }
    }
}
