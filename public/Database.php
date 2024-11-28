<?php

namespace BLBot;

class Database {
    private $collection;
    private $primaryKey;
    private $defaultValue;

    public function __construct($collectionName, $options = []) {
        global $Database;
        $this->collection = $Database->$collectionName;
        $this->primaryKey = $options['key'] ?? 'user_id';
        $this->defaultValue = $options['default'] ?? null;
    }

    public function set($key, $data, $upsert = true) {
        return $this->collection->updateOne(
            [$this->primaryKey => $key],
            ['$set' => $data],
            ['upsert' => $upsert],
        )->isAcknowledged();
    }

    public function get($key, $projection = null) {
        $options = [];
        if($projection) {
            if(gettype($projection) == 'string') {
                $projection = [$projection];
            }
            $options['projection'] = array_combine($projection, array_fill(0, count($projection), 1));
        }
        return $this->collection->findOne(
            [$this->primaryKey => $key],
            $options,
        ) ?? $this->defaultValue;
    }

    public function remove($key, $data, $upsert = true) {
        if(!is_array($data)) {
            $data = [$data];
        }
        return $this->collection->updateOne(
            [$this->primaryKey => $key],
            ['$unset' => array_flip($data)],
            ['upsert' => $upsert],
        )->isAcknowledged();
    }

    public function delete($key) {
        return $this->collection->deleteOne(
            [$this->primaryKey => $key],
        )->getDeletedCount();
    }
}
