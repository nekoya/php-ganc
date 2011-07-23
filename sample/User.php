<?php
require_once 'lib/Ganc/Loader.php';

class Ganc_Object {
    public $dsn = 'sqlite::memory:';

    function __construct() {
        $this->builder = new Ganc_SQLBuilder();
        $conn = new Ganc_ConnectionManager();
        $this->engine = new Ganc_Engine($conn->getConnection($this->dsn));
    }

    function find($id) {
        list($sql, $binds) = $this->builder->select($this->table, $this->columns, array('id' => $id));
        $row = $this->engine->find($sql, $binds);
        foreach ($row as $key => $val) {
            $this->$key = $val;
        }
        return $this;
    }
}

class User extends Ganc_Object {
    public $table = 'users';
    public $columns = array('id', 'name', 'password');
}

$conn = new Ganc_ConnectionManager();
$dsn = 'sqlite::memory:';
$conn->connect(array('dsn' => $dsn));
$engine = new Ganc_Engine($conn->getConnection($dsn));
$engine->query(
    'CREATE TABLE users (' .
    'id integer primary key autoincrement,' .
    'name string not null,' .
    'password string not null' .
    ');'
);
$engine->insert('users', array('id' => 1, 'name' => 'admin', 'password' => 'default'));
$engine->insert('users', array('id' => 2, 'name' => 'user', 'password' => 'password'));

$user = new User();
$user->find(2);

echo 'id       : ' . $user->id . PHP_EOL;
echo 'name     : ' . $user->name . PHP_EOL;
echo 'password : ' . $user->password . PHP_EOL;
