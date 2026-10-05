--TEST--
md5_file() and sha1_file() return false when reading fails
--FILE--
<?php
$dir = __DIR__;
var_dump(@md5_file($dir));
var_dump(@sha1_file($dir));
var_dump(@hash_file('md5', $dir));
?>
--EXPECT--
bool(false)
bool(false)
bool(false)
