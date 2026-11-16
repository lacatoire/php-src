--TEST--
stream_set_read_buffer() on a directory handle does not break readdir()
--FILE--
<?php

$dir = __DIR__ . '/stream_set_read_buffer_dir';
mkdir($dir);
touch($dir . '/file');

$handle = opendir($dir);
var_dump(stream_set_read_buffer($handle, 4096));
$entries = [];
while (($entry = readdir($handle)) !== false) {
    $entries[] = $entry;
}
sort($entries);
var_dump($entries);
closedir($handle);

?>
--CLEAN--
<?php
$dir = __DIR__ . '/stream_set_read_buffer_dir';
@unlink($dir . '/file');
@rmdir($dir);
?>
--EXPECT--
int(-1)
array(3) {
  [0]=>
  string(1) "."
  [1]=>
  string(2) ".."
  [2]=>
  string(4) "file"
}
