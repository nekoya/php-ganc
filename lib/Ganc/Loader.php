<?php
class Ganc_Loader
{
    static public function __load_class($class)
    {
        if (class_exists($class, false)) {
            return true;
        }
        if (preg_match('/^(Ganc_\w+)$/', $class, $matches)) {
            $filename = str_replace('_', '/', $matches[1]);
            $path = dirname(__FILE__) . '/../';
            include $path . $filename . '.php';
        }
        return class_exists($class, false);
    }
}
spl_autoload_register(array('Ganc_Loader', '__load_class'));
