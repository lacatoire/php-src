--TEST--
header_register_callback() always returns true
--FILE--
<?php
$result = header_register_callback(function () {});
var_dump($result);                  // bool(true)
var_dump($result === true);         // bool(true)

// Reflection confirms the declared return type is true
$rf = new ReflectionFunction('header_register_callback');
$rt = $rf->getReturnType();
var_dump((string) $rt);             // string(4) "true"
?>
--EXPECT--
bool(true)
bool(true)
string(4) "true"
