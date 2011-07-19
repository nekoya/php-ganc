<?php
class Ganc_Logger_Row {
    protected $levels = array(
        'debug'  => 10,
        'info'   => 20,
        'notice' => 30,
        'warn'   => 40,
        'error'  => 50,
        'crit'   => 60,
    );

    public $time;
    public $level;
    public $message;

    function __construct($message, $level=null) {
        $this->time = new DateTime();
        $this->level = $this->getLevel($level);
        $this->message = $message;
    }

    protected function getLevel($level=null) {
        if (is_null($level)) {
            $level = 'info';
        }
        if (!isset($this->levels[$level])) {
            throw new Exception("$level is undefined log level");
        }
        return $level;
    }

    function __toString() {
        return sprintf('[%s] %s', strtoupper($this->level), $this->message);
    }
}
