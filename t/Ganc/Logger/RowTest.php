<?php
require_once 'lib/Ganc/Loader.php';
class Ganc_Logger_RowTest extends PHPUnit_Framework_TestCase {
    function testDefaultLevelRow() {
        $row = new Ganc_Logger_Row('test');
        $this->assertEquals('info', $row->level);
        $this->assertEquals('[INFO] test', (string)$row);
    }

    function testLeveledRow() {
        $row = new Ganc_Logger_Row('test', 'warn');
        $this->assertEquals('warn', $row->level);
        $this->assertEquals('[WARN] test', (string)$row);
    }
}
