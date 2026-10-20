--TEST--
mb_strcut() with UTF-16 starts at the high surrogate when the offset points to the low surrogate
--EXTENSIONS--
mbstring
--FILE--
<?php
foreach (['BE', 'LE'] as $order) {
    $enc = "UTF-16$order";
    $s = mb_convert_encoding("a\u{1F389}b", $enc, 'UTF-8');
    var_dump(bin2hex(mb_strcut($s, 4, null, $enc)));
    var_dump(bin2hex(mb_strcut($s, 4, 2, $enc)));
}
?>
--EXPECT--
string(12) "d83cdf890062"
string(8) "d83cdf89"
string(12) "3cd889df6200"
string(8) "3cd889df"
