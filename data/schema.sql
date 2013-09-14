CREATE TABLE nodes (
  node_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  storage_id INTEGER NOT NULL,
  parent_node_id INTEGER,
  node_class_id INTEGER,
  attributes TEXT,
  current_version INTEGER DEFAULT 0
);

CREATE TABLE node_classes (
  node_class_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  node_class_name VARCHAR(255),
  node_class_settings TEXT
);

CREATE TABLE storages (
  storage_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  storage_name VARCHAR(255),
  storage_enabled INT(1) DEFAULT 1,
  storage_accounts TEXT
);

