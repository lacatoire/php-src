--TEST--
ucwords() checks letter separators on the input, not on uppercased bytes
--FILE--
<?php
var_dump(ucwords('abc', 'a'));
var_dump(ucwords('aab', 'A'));
var_dump(ucwords('mcdonald', 'M'));
var_dump(ucwords('hello world-foo', ' -'));
?>
--EXPECT--
string(3) "ABc"
string(3) "Aab"
string(8) "Mcdonald"
string(15) "Hello World-Foo"
