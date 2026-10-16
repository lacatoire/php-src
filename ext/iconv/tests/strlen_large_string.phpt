--TEST--
iconv_strlen(), iconv_substr(): a large string is processed quickly
--EXTENSIONS--
iconv
--FILE--
<?php
$s = str_repeat("é", 1000000);
$t = microtime(true);
var_dump(iconv_strlen($s, "UTF-8"));
var_dump(iconv_substr($s, 5, 3, "UTF-8"));
var_dump(microtime(true) - $t < 3);
var_dump(iconv_strlen("", "UTF-8"), iconv_strlen("aé€𝄞", "UTF-8"));
?>
--EXPECT--
int(1000000)
string(6) "ééé"
bool(true)
int(0)
int(4)
