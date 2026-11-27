--TEST--
unserialize() handles a parent private property that became protected or public
--FILE--
<?php

class P
{
    protected int $protected = 1;

    public int $public = 2;
}

class C extends P
{
}

$object = unserialize("O:1:\"C\":2:{s:12:\"\0P\0protected\";i:7;s:9:\"\0P\0public\";i:8;}");
var_dump($object);

try {
    unserialize("O:1:\"C\":1:{s:12:\"\0P\0protected\";s:3:\"foo\";}");
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
?>
--EXPECT--
object(C)#1 (2) {
  ["protected":protected]=>
  int(7)
  ["public"]=>
  int(8)
}
TypeError: Cannot assign string to property P::$protected of type int
