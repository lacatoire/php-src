--TEST--
mb_strcut() with UTF-16 and an odd offset keeps the last character
--EXTENSIONS--
mbstring
--FILE--
<?php
$be = "\x00a\x00b\x00c";
foreach ([0, 1, 2, 3] as $offset) {
    var_dump(bin2hex(mb_strcut($be, $offset, null, "UTF-16BE")));
}
$le = "a\x00b\x00c\x00";
foreach ([0, 1, 2, 3] as $offset) {
    var_dump(bin2hex(mb_strcut($le, $offset, null, "UTF-16LE")));
}
var_dump(bin2hex(mb_strcut($be, 3, null, "UTF-16")));
?>
--EXPECT--
string(12) "006100620063"
string(12) "006100620063"
string(8) "00620063"
string(8) "00620063"
string(12) "610062006300"
string(12) "610062006300"
string(8) "62006300"
string(8) "62006300"
string(8) "00620063"
