--TEST--
pos(): alias of current()
--FILE--
<?php

$a = [1, 2, 3];
var_dump(pos($a));
next($a);
var_dump(pos($a));
end($a);
next($a);
var_dump(pos($a));

var_dump(pos([]));

$o = new stdClass;
$o->a = 'x';
$o->b = 'y';
var_dump(pos($o));

var_dump((new ReflectionFunction('pos'))->getName());

?>
--EXPECTF--
int(1)
int(2)
bool(false)
bool(false)

Deprecated: pos(): Calling %s on an object is deprecated in %s on line %d
string(1) "x"
string(3) "pos"
