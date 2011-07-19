<?php
class Ganc_Engine {
    public $connection;

    function __construct($connection) {
        if (!is_null($connection)) {
            $this->connection = $connection;
        }
    }

    function query($sql, $params=array()) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    function find($sql, $params=array()) {
        $stmt = $this->query($sql, $params);
        $row = $stmt->fetch(PDO::FETCH_NAMED);
        $stmt->closeCursor();
        return $row;
    }

    function findAll($sql, $params=array()) {
        $stmt = $this->query($sql, $params);
        $rows = $stmt->fetchAll(PDO::FETCH_NAMED);
        $stmt->closeCursor();
        return $rows;
    }

    function lastInsertId() {
        return $this->connection->lastInsertId();
    }

    function beginTransaction() {
        return $this->connection->beginTransaction();
    }

    function commit() {
        return $this->connection->commit();
    }

    function rollBack() {
        return $this->connection->rollBack();
    }
}
