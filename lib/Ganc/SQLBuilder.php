<?php
class Ganc_SQLBuilder {
    public $driver = 'mysql';
    public $quote = '`';

    protected $wheres = array();
    protected $binds = array();

    function __construct() {
    }

    function select($table, $columns, $where=null, $opt=null) {
        $this->initWheres();
        $sql = "SELECT ";
        foreach ($columns as $column) {
            $sql .= $this->quote($column) . ',';
        }
        $sql = rtrim($sql, ',');
        $sql .= " FROM " . $this->quote($table);

        if (!is_null($where)) {
            foreach ($where as $key => $val) {
                $this->addWhere($key, $val);
            }
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        return array($sql, $this->binds);
    }

    function insert($table, $params) {
        $prefix = "INSERT INTO";
        $columns = array();
        $values = array();
        $binds = array();
        foreach ($params as $column => $value) {
            $columns[] = $this->quote($column);
            $values[] = '?';
            $binds[] = $value;
        }

        $sql = sprintf(
            "%s %s (%s) VALUES (%s)",
            $prefix,
            $this->quote($table),
            implode(',', $columns),
            implode(',', $values)
        );

        return array($sql, $binds);
    }

    protected function quote($str) {
        return $this->quote . $str . $this->quote;
    }

    protected function initWheres() {
        $this->wheres = array();
        $this->binds = array();
    }

    protected function addWhere($key, $val) {
        if (is_array($val)) {
            throw new Exception("not supported yet...");
        }
        $this->wheres[] = $this->quote($key) . '=?';
        $this->binds[] = $val;
    }
}
