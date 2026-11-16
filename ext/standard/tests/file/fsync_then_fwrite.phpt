--TEST--
fsync() and fdatasync() do not buffer later fwrite() calls
--FILE--
<?php

$file = __DIR__ . '/fsync_then_fwrite.txt';
$handle = fopen($file, 'w');

fwrite($handle, 'a');
fsync($handle);
fwrite($handle, 'b');
clearstatcache();
var_dump(filesize($file));

fdatasync($handle);
fwrite($handle, 'c');
clearstatcache();
var_dump(filesize($file));

fclose($handle);

?>
--CLEAN--
<?php
@unlink(__DIR__ . '/fsync_then_fwrite.txt');
?>
--EXPECT--
int(2)
int(3)
