<?php
require_once 'lib/Ganc/Loader.php';
class Ganc_ConnectionManagerTest extends PHPUnit_Framework_TestCase {
    function testNew() {
        $conn = new Ganc_ConnectionManager();
        $this->assertEquals(array(), $conn->getConnections());
    }

    function testConnect() {
        $conn = new Ganc_ConnectionManager();
        $dsn = 'sqlite::memory:';
        $conn->connect(array('dsn' => $dsn));
        $this->assertInstanceOf('PDO', $conn->getConnection($dsn));
        $conn->resetConnections();
        $this->assertEquals(array(), $conn->getConnections());
    }

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
        $stmt = $engine->connection->prepare("INSERT INTO users (id,name,password) VALUES (:id,:name,:password)");
        $stmt->execute($admin);
        $stmt->execute($user);
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
    }
}
