--TEST--
serialize(): a nested serialize() call from user code does not shift the back-references
--FILE--
<?php

class V
{
    public $x = 1;

    public function __sleep()
    {
        return 'nope';
    }
}

class E
{
    public function __destruct()
    {
        serialize([1, 2, 3]);
    }
}

class D
{
    public function __serialize(): array
    {
        return ['e' => new E()];
    }
}

$object = new stdClass();

set_error_handler(function () {
    serialize([1, 2, 3]);
    return true;
});
echo serialize([new V(), $object, $object]), "\n";
restore_error_handler();

echo serialize([new D(), $object, $object]), "\n";

?>
--EXPECT--
a:3:{i:0;N;i:1;O:8:"stdClass":0:{}i:2;r:3;}
a:3:{i:0;O:1:"D":1:{s:1:"e";O:1:"E":0:{}}i:1;O:8:"stdClass":0:{}i:2;r:4;}
