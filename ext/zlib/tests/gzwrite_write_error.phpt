--TEST--
gzwrite(), fwrite() on compress.zlib://: a write error is reported
--EXTENSIONS--
zlib
--SKIPIF--
<?php
if (!file_exists('/dev/full')) die('skip needs /dev/full');
?>
--FILE--
<?php
$data = random_bytes(300000);
$w = gzopen('/dev/full', 'w');
var_dump(gzwrite($w, $data));
$f = fopen('compress.zlib:///dev/full', 'w');
var_dump(fwrite($f, $data));
?>
--EXPECTF--
Notice: gzwrite(): Write of %d bytes failed with errno=28 %s in %s on line %d
bool(false)

Notice: fwrite(): Write of %d bytes failed with errno=28 %s in %s on line %d
bool(false)
