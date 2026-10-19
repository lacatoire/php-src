--TEST--
quoted_printable_decode() does not truncate the input at NUL bytes
--FILE--
<?php
foreach (["a\0b", "a=41\0b", "a=00b", "a\0=\r\nb", "a=\0b", "a=4"] as $s) {
    var_dump(bin2hex(quoted_printable_decode($s)));
}
?>
--EXPECT--
string(6) "610062"
string(8) "61410062"
string(6) "610062"
string(6) "610062"
string(8) "613d0062"
string(6) "613d34"
