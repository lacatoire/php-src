--TEST--
stream_filter_remove() writes the flushed tail of a write filter at the stream position
--FILE--
<?php
$file = __DIR__ . '/stream_filter_remove_write_flush_position.txt';
file_put_contents($file, '0123456789ABCDEFGHIJ');
$stream = fopen($file, 'r+');
$filter = stream_filter_append($stream, 'convert.base64-encode', STREAM_FILTER_WRITE);
fread($stream, 4);
fwrite($stream, 'ab');
var_dump(ftell($stream));
stream_filter_remove($filter);
var_dump(ftell($stream), fread($stream, 5));
fclose($stream);
var_dump(file_get_contents($file));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/stream_filter_remove_write_flush_position.txt');
?>
--EXPECT--
int(4)
int(8)
string(5) "89ABC"
string(20) "0123YWI=89ABCDEFGHIJ"
