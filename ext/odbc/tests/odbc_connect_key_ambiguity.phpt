--TEST--
odbc_connect(): connection lookup key must not be ambiguous when values contain the separator
--EXTENSIONS--
odbc
--SKIPIF--
<?php
include 'skipif.inc';
?>
--FILE--
<?php

include 'config.inc';

/* "a_b" + "c" and "a" + "b_c" used to share the cache key "a_b_c". */
$c1 = odbc_connect($dsn, 'a_b', 'c');
$c2 = odbc_connect($dsn, 'a', 'b_c');
var_dump(is_object($c1) && $c1 === $c2);

?>
--EXPECT--
bool(false)
