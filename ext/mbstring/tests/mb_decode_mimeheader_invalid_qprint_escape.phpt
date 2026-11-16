--TEST--
mb_decode_mimeheader() keeps characters following "=" in Q encoding when not a hex pair
--EXTENSIONS--
mbstring
--FILE--
<?php
var_dump(mb_decode_mimeheader("=?UTF-8?Q?a=Zzb?="));
var_dump(mb_decode_mimeheader("=?UTF-8?Q?a=4Gb?="));
var_dump(mb_decode_mimeheader("=?UTF-8?Q?a==41?="));
var_dump(mb_decode_mimeheader("=?UTF-8?Q?a=Z?="));
?>
--EXPECT--
string(5) "a=Zzb"
string(5) "a=4Gb"
string(3) "a=A"
string(3) "a=Z"
