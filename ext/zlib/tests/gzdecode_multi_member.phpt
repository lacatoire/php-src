--TEST--
gzdecode(), zlib_decode(): all the members of a gzip stream are decoded
--EXTENSIONS--
zlib
--FILE--
<?php
$m = gzencode("first\n") . gzencode("second\n") . gzencode("third\n");
var_dump(gzdecode($m));
var_dump(zlib_decode($m));
var_dump(gzdecode(gzencode("x") . "garbage"));
var_dump(gzdecode($m, 19));
var_dump(@gzdecode($m, 18));
var_dump(@gzdecode(gzencode("a") . substr(gzencode("b"), 0, -4)));
var_dump(gzinflate(gzdeflate("raw")), gzuncompress(gzcompress("zlib")));
?>
--EXPECT--
string(19) "first
second
third
"
string(19) "first
second
third
"
string(1) "x"
string(19) "first
second
third
"
bool(false)
bool(false)
string(3) "raw"
string(4) "zlib"
