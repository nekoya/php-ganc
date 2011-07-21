<?php
require_once 'lib/Ganc/Loader.php';
class Ganc_EngineTest extends PHPUnit_Framework_TestCase {
    function testQuery() {
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
        $admin = array('id' => 1, 'name' => 'admin', 'password' => 'default');
        $user  = array('id' => 2, 'name' => 'user', 'password' => 'password');
        $engine->query("INSERT INTO users (id,name,password) VALUES (:id,:name,:password)", $admin);
        $engine->query("INSERT INTO users (id,name,password) VALUES (:id,:name,:password)", $user);
        $this->assertEquals(2, $engine->lastInsertId());

        $this->assertEquals($admin, $engine->find("SELECT * FROM users"));
        $this->assertEquals($user, $engine->find("SELECT * FROM users WHERE id=?", array(2)));

        $this->assertEquals(
            array($admin, $user),
            $engine->findAll("SELECT * FROM users")
        );
        $this->assertEquals(
            array($admin, $user),
            $engine->findAll("SELECT * FROM users WHERE id IN (?,?)", array(1, 2))
        );

        $engine->beginTransaction();
        $engine->query("DELETE FROM users");
        $this->assertEquals(
            array(),
            $engine->findAll("SELECT * FROM users")
        );
        $engine->rollBack();
        $this->assertEquals(
            array($admin, $user),
            $engine->findAll("SELECT * FROM users")
        );

        $engine->beginTransaction();
        $engine->query("DELETE FROM users");
        $engine->commit();
        $this->assertEquals(
            array(),
            $engine->findAll("SELECT * FROM users")
        );

        //echo (string)$engine->logger;
    }
}
