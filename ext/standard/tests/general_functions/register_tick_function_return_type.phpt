--TEST--
register_tick_function(): declared return type matches the only possible outcome
--FILE--
<?php

var_dump((string) (new ReflectionFunction('register_tick_function'))->getReturnType());

$callback = function () {};

var_dump(register_tick_function($callback));
var_dump(register_tick_function($callback, 'an argument'));

unregister_tick_function($callback);

?>
--EXPECT--
string(4) "true"
bool(true)
bool(true)
