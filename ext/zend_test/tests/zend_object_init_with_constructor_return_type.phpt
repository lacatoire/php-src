--TEST--
Zend: zend_object_init_with_constructor() declares the object it always returns
--EXTENSIONS--
zend_test
--FILE--
<?php

var_dump((string) (new ReflectionFunction('zend_object_init_with_constructor'))->getReturnType());

class Test {
    public function __construct(public int $value = 0) {}
}

$object = zend_object_init_with_constructor(Test::class, 42);
var_dump($object instanceof Test, $object->value);

try {
    zend_object_init_with_constructor(Test::class, 'not an int');
} catch (TypeError $e) {
    echo $e::class, "\n";
}

?>
--EXPECT--
string(6) "object"
bool(true)
int(42)
TypeError
