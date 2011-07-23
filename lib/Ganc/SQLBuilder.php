<?php
class Ganc_SQLBuilder {
    public $driver = 'mysql';
    public $quote = '`';

    function __construct() {
    }

    function select($table, $columns, $where=null, $opt=null) {
        list($w, $binds) = $this->makeWhereClauses($where);
        $sql = sprintf(
            "SELECT %s FROM %s%s",
            implode(',', array_map(array($this, 'quote'), $columns)),
            $this->quote($table),
            $w
        );
        return array($sql, $binds);
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

    function update($table, $params, $where=null) {
        $values = array();
        $binds = array();
        foreach ($params as $column => $value) {
            $values[] = $this->quote($column) . '=?';
            $binds[] = $value;
        }

        list ($w, $_binds) = $this->makeWhereClauses($where);
        $binds = array_merge($binds, $_binds);
        $sql = sprintf(
            "UPDATE %s SET %s%s",
            $this->quote($table),
            implode(',', $values),
            $w
        );

        return array($sql, $binds);
    }

    protected function quote($str) {
        return $this->quote . $str . $this->quote;
    }

    protected function makeWhereClauses($args) {
        if (is_null($args)) {
            return array('', array());
        }

        $wheres = array();
        $binds = array();
        foreach ($args as $key => $val) {
            if (is_array($val)) {
                throw new Exception("not supported yet...");
            }
            $wheres[] = $this->quote($key) . '=?';
            $binds[] = $val;
        }
        $sql = " WHERE " . implode(' AND ', $wheres);
        return array($sql, $binds);
    }
}
