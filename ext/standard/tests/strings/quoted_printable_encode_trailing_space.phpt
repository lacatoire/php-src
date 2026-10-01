--TEST--
quoted_printable_encode(): trailing space at the end of the input is encoded
--FILE--
<?php
var_dump(quoted_printable_encode("a "));
var_dump(quoted_printable_encode("a \r\nb"));
var_dump(quoted_printable_encode("a\t"));
var_dump(quoted_printable_encode("a b"));
?>
--EXPECT--
string(4) "a=20"
string(7) "a=20
b"
string(4) "a=09"
string(3) "a b"
