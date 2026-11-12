--TEST--
Destroying the autoloader table at shutdown while a destructor reads or grows it
--FILE--
<?php
class Holder {
    public function load($class) {}
    public function __destruct() {
        var_dump(count(spl_autoload_functions()));
        for ($i = 0; $i < 20; $i++) {
            spl_autoload_register(function ($class) {});
        }
    }
}

function load($class) {}

spl_autoload_register('load');
ob_start(function ($buffer) {
    spl_autoload_register([new Holder(), 'load']);
    return $buffer;
});
echo "main\n";
?>
--EXPECT--
main
int(0)
