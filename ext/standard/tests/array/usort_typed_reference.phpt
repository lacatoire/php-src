--TEST--
usort(), uasort() and uksort() respect typed reference sources
--FILE--
<?php

class Test
{
    public int $prop = 0;
}

foreach (['usort', 'uasort', 'uksort'] as $function) {
    $object = new Test();
    $array = [2, 1];
    $done = false;
    try {
        $function($array, function ($a, $b) use ($object, &$array, &$done) {
            if (!$done) {
                $done = true;
                $array = 42;
                $object->prop = &$array;
            }
            return $a <=> $b;
        });
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
    var_dump($object->prop);
    unset($array);
}

?>
--EXPECT--
TypeError: Cannot assign array to reference held by property Test::$prop of type int
int(42)
TypeError: Cannot assign array to reference held by property Test::$prop of type int
int(42)
TypeError: Cannot assign array to reference held by property Test::$prop of type int
int(42)
