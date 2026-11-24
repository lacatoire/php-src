--TEST--
spl_autoload_register() with an error handler that throws on a notice or a deprecation
--FILE--
<?php
class P {
    public static function __callStatic($name, $arguments) {}
}
class C extends P {}

set_error_handler(function ($errno, $errstr) {
    throw new ErrorException($errstr);
});

try {
    spl_autoload_register(['C', 'missing'], false);
} catch (ErrorException $e) {
    echo $e->getMessage(), "\n";
}

try {
    spl_autoload_register(['C', 'parent::missing']);
} catch (ErrorException $e) {
    echo $e->getMessage(), "\n";
}

var_dump(spl_autoload_functions());
?>
--EXPECT--
spl_autoload_register(): Argument #2 ($do_throw) has been ignored, spl_autoload_register() will always throw
Callables of the form ["C", "parent::missing"] are deprecated
array(0) {
}
