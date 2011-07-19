<?php
class Ganc_ConnectionManager {
    protected static $connections = array();

    function __construct() {
    }

    function connect($options) {
        $db = new PDO(
            $options['dsn'],
            isset($options['username']) ? $options['username'] : null,
            isset($options['password']) ? $options['password'] : null,
            array(PDO::ATTR_PERSISTENT => false)
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$connections[$options['dsn']] = $db;
    }

    function getConnections() {
        return self::$connections;
    }

    function resetConnections() {
        self::$connections = array();
    }

    function getConnection($dsn) {
        return self::$connections[$dsn];
    }
}
