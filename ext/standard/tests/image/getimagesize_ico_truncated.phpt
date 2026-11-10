--TEST--
getimagesizefromstring(): an ICO without a complete directory entry has no size
--FILE--
<?php
// ICONDIR: reserved, type 1, one image
$header = "\x00\x00\x01\x00\x01\x00";
// Entry: 16x16, 0 colours, planes 1, 32 bits, size, offset
$entry = "\x10\x10\x00\x00\x01\x00\x20\x00\x00\x00\x00\x00\x16\x00\x00\x00";

var_dump(getimagesizefromstring($header));
var_dump(getimagesizefromstring($header . substr($entry, 0, 9)));
$info = getimagesizefromstring($header . $entry);
var_dump($info[0], $info[1], $info['bits']);
// A zero width and height mean 256
$info = getimagesizefromstring($header . "\x00\x00" . substr($entry, 2));
var_dump($info[0], $info[1]);
?>
--EXPECT--
bool(false)
bool(false)
int(16)
int(16)
int(32)
int(256)
int(256)
