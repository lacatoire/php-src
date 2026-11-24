--TEST--
str_getcsv() and fputcsv() honour an escape character above 0x7f
--FILE--
<?php
var_dump(bin2hex(str_getcsv("\"a\xff\"b\",c", ',', '"', "\xff")[0]));
var_dump(bin2hex(str_getcsv("\"a\x7f\"b\",c", ',', '"', "\x7f")[0]));

$stream = fopen('php://memory', 'w+');
fputcsv($stream, ["a\xff\"b"], ',', '"', "\xff");
rewind($stream);
var_dump(bin2hex(stream_get_contents($stream)));
?>
--EXPECT--
string(8) "61ff2262"
string(8) "617f2262"
string(14) "2261ff2262220a"
