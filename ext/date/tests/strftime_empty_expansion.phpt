--TEST--
strftime() and gmstrftime() return an empty string, not false, when a valid format expands to nothing
--SKIPIF--
<?php
error_reporting(E_ALL & ~E_DEPRECATED);
if (setlocale(LC_TIME, 'de_DE.ISO-8859-1', 'de_DE.iso88591', 'de_DE.UTF-8', 'de_DE.utf8') === false) {
    die('skip de_DE locale not available');
}
if (!in_array(strftime('%p', 0), ['', false], true)) {
    die('skip %p is not empty in this locale');
}
?>
--FILE--
<?php

error_reporting(E_ALL & ~E_DEPRECATED);
setlocale(LC_TIME, 'de_DE.ISO-8859-1', 'de_DE.iso88591', 'de_DE.UTF-8', 'de_DE.utf8');

var_dump(strftime('%p', 0));
var_dump(gmstrftime('%p', 0));
var_dump(strftime('[%p]', 0));
var_dump(strftime('', 0));

?>
--EXPECT--
string(0) ""
string(0) ""
string(2) "[]"
bool(false)
