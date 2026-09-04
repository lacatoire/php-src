--TEST--
Stack limit 009 - The stack helpers declare the null they return when the stack cannot be read
--SKIPIF--
<?php
if (!function_exists('zend_test_zend_call_stack_use_all')) die("skip zend_test_zend_call_stack_use_all() is not available");
?>
--EXTENSIONS--
zend_test
--FILE--
<?php

/* Both helpers return null when zend_call_stack_get() fails. */
var_dump((string) (new ReflectionFunction('zend_test_zend_call_stack_use_all'))->getReturnType());
var_dump((string) (new ReflectionFunction('zend_test_zend_call_stack_get'))->getReturnType());

?>
--EXPECT--
string(4) "?int"
string(6) "?array"
