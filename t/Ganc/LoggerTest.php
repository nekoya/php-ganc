<?php
require_once 'lib/Ganc/Loader.php';
class Ganc_LoggerTest extends PHPUnit_Framework_TestCase {
    function testNew() {
        $logger = new Ganc_Logger();
        $logger->append('info log');
        $logger->append('warning', 'warn');
        $this->assertEquals('[INFO] info log' . PHP_EOL . '[WARN] warning' . PHP_EOL, (string)$logger);
    }

    function testGetLogger() {
        $default = Ganc_Logger::getLogger('default');
        $this->assertInstanceOf('Ganc_Logger', $default);
        $this->assertEquals('default', $default->name);

        $queries = Ganc_Logger::getLogger('queries');
        $this->assertEquals('queries', $queries->name);

        $default->append('default log');
        $this->assertEquals('[INFO] default log' . PHP_EOL, (string)$default);
        $this->assertEquals('', (string)$queries);
    }
}
