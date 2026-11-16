--TEST--
mb_encode_numericentity(): invalid input is not captured by a map reaching 0xFFFFFFFF
--EXTENSIONS--
mbstring
--FILE--
<?php
$all = [0, 0xffffffff, 0, 0xffffffff];
var_dump(mb_encode_numericentity("a\xffb\xc3", $all, "UTF-8"));
var_dump(mb_encode_numericentity("a\xffb", $all, "UTF-8", true));
var_dump(mb_encode_numericentity("a\xffb", [0x80, 0x10ffff, 0, 0xffffff], "UTF-8"));
mb_substitute_character("long");
var_dump(mb_encode_numericentity("a\xffb", $all, "UTF-8"));
mb_substitute_character("none");
var_dump(mb_encode_numericentity("a\xffb", $all, "UTF-8"));
?>
--EXPECT--
string(12) "&#97;?&#98;?"
string(13) "&#x61;?&#x62;"
string(3) "a?b"
string(11) "&#97;?&#98;"
string(10) "&#97;&#98;"