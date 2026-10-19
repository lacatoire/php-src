--TEST--
mb_decode_numericentity() keeps decimal entities that overflow 32 bits literal
--EXTENSIONS--
mbstring
--FILE--
<?php
$map = [0, 0x10ffff, 0, 0xffffff];
foreach (['4294967295', '4294967296', '4294967299', '4294967300', '4294967301'] as $n) {
    var_dump(bin2hex(mb_decode_numericentity("&#$n;", $map, 'UTF-8')));
}
?>
--EXPECT--
string(26) "2623343239343936373239353b"
string(26) "2623343239343936373239363b"
string(26) "2623343239343936373239393b"
string(26) "2623343239343936373330303b"
string(26) "2623343239343936373330313b"
