<?php
class Ganc_Logger {
    static $instances = array();

    public $name;
    public $logs = array();

    function __construct($name=null) {
        if (!is_null($name)) {
            $this->name = $name;
        }
    }

    static function getLogger($name) {
        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new Ganc_Logger($name);
        }
        return self::$instances[$name];
    }

    function append($message, $level=null) {
        $this->logs[] = new Ganc_Logger_Row($message, $level);
    }

    function  debug($message) { $this->append($message, __function__); }
    function   info($message) { $this->append($message, __function__); }
    function notice($message) { $this->append($message, __function__); }
    function   warn($message) { $this->append($message, __function__); }
    function  error($message) { $this->append($message, __function__); }
    function   crit($message) { $this->append($message, __function__); }

    function __toString() {
        $logs = '';
        foreach ($this->logs as $log) {
            $logs .= $log . PHP_EOL;
        }
        return $logs;
    }
}
