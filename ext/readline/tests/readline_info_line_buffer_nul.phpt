--TEST--
readline_info(): line_buffer with embedded NUL keeps buffer and end consistent (libedit)
--EXTENSIONS--
readline
--SKIPIF--
<?php
if (READLINE_LIB !== "libedit") die("skip libedit only");
?>
--FILE--
<?php
readline_info("line_buffer", "a\0bcdef");
var_dump(readline_info("line_buffer"));
var_dump(readline_info("end"));
?>
--EXPECT--
string(1) "a"
int(7)
