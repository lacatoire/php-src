--TEST--
Emptying the autoloader table while a destructor reads or grows it
--FILE--
<?php
class Holder {
    public function load($class) {}
    public function __destruct() {
        var_dump(count(spl_autoload_functions()));
        var_dump(class_exists('Missing'));
        for ($i = 0; $i < 20; $i++) {
            spl_autoload_register(function ($class) {});
        }
    }
}

function load($class) {}

spl_autoload_register('load');
spl_autoload_register([new Holder(), 'load']);
@spl_autoload_unregister('spl_autoload_call');
var_dump(count(spl_autoload_functions()));
?>
--EXPECT--
int(0)
bool(false)
int(0)
