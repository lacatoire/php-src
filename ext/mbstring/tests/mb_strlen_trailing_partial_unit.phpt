--TEST--
mb_strlen() counts a trailing partial unit of fixed-width encodings
--EXTENSIONS--
mbstring
--FILE--
<?php
var_dump(mb_strlen("a", "UCS-2"));
var_dump(mb_strlen("\x00a\x00", "UCS-2"));
var_dump(mb_strlen("\x00\x00\x00a\x00\x00", "UTF-32BE"));
var_dump(mb_strlen("\x00\x00\x00a\x00\x00", "UCS-4"));
var_dump(mb_strlen("\x00a\x00b", "UCS-2"));
var_dump(mb_strlen("abc", "ISO-8859-1"));
var_dump(count(mb_str_split("\x00a\x00", 1, "UCS-2")));
?>
--EXPECT--
int(1)
int(2)
int(2)
int(2)
int(2)
int(3)
int(2)
