PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE record(
user_id BIGINT NOT NULL,
command TEXT NOT NULL,
count NOT NULL
);
COMMIT;
