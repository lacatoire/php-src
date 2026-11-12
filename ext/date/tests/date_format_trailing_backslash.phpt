--TEST--
date(), DateTime::format(): a trailing backslash is a literal backslash, not a NUL byte
--FILE--
<?php
date_default_timezone_set('UTC');
$d = new DateTime('2024-01-02');

var_dump(date("Y\\", 0));
var_dump(date("\\", 0));
var_dump(gmdate("\\", 0));
var_dump($d->format("Y-m-d\\"));
// A valid escape pair is unchanged
var_dump(date("Y\\\\", 0));
var_dump(date("\\Y", 0));
?>
--EXPECT--
string(5) "1970\"
string(1) "\"
string(1) "\"
string(11) "2024-01-02\"
string(5) "1970\"
string(1) "Y"
