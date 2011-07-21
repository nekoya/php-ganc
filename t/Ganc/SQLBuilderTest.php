<?php
require_once 'lib/Ganc/Loader.php';
class Ganc_SQLBuilderTest extends PHPUnit_Framework_TestCase {
    function testNew() {
        $builder = new Ganc_SQLBuilder();
        $this->assertInstanceOf('Ganc_SQLBuilder', $builder);
    }

    function testSelect() {
        $builder = new Ganc_SQLBuilder();

        // no conditions
        list($sql, $binds) = $builder->select(
            'users',
            array('id', 'name', 'password')
        );
        $this->assertEquals('SELECT `id`,`name`,`password` FROM `users`', $sql);
        $this->assertEquals(array(), $binds);

        // single condition
        list($sql, $binds) = $builder->select(
            'users',
            array('id', 'name', 'password'),
            array('name' => 'admin')
        );
        $this->assertEquals('SELECT `id`,`name`,`password` FROM `users` WHERE `name`=?', $sql);
        $this->assertEquals(array('admin'), $binds);

        // multi condition
        list($sql, $binds) = $builder->select(
            'users',
            array('id', 'name', 'password'),
            array('id' => 1, 'name' => 'admin', 'password' => 'default')
        );
        $this->assertEquals('SELECT `id`,`name`,`password` FROM `users` WHERE `id`=? AND `name`=? AND `password`=?', $sql);
        $this->assertEquals(array(1, 'admin', 'default'), $binds);
    }

    function testInsert() {
        $builder = new Ganc_SQLBuilder();

        list($sql, $binds) = $builder->insert(
            'users',
            array('name' => 'admin', 'password' => 'default')
        );
        $this->assertEquals('INSERT INTO `users` (`name`,`password`) VALUES (?,?)', $sql);
        $this->assertEquals(array('admin', 'default'), $binds);
    }
}
