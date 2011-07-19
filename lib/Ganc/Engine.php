<?php
class Ganc_Engine {
    public $connection;
    public $logger;

    function __construct($connection) {
        if (!is_null($connection)) {
            $this->connection = $connection;
        }
        $this->logger = Ganc_Logger::getLogger('queries');
    }

    function query($sql, $params=array()) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        $logParams = '';
        if ($params) {
            $logParams = preg_replace('/\s+/', ' ', print_r($params, true));
            $logParams = ' : ' . preg_replace('/^Array /', '', $logParams);
        }
        $this->logger->info(sprintf('"%s"%s', $sql, $logParams));
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
