--TEST--
stream_set_write_buffer() does not change the buffering of the process standard streams
--SKIPIF--
<?php
if (PHP_SAPI !== 'cli') die('skip CLI only');
?>
--FILE--
<?php
var_dump(stream_set_write_buffer(STDERR, 4096));
var_dump(stream_set_write_buffer(STDOUT, 4096));
var_dump(stream_set_write_buffer(STDIN, 4096));

$file = fopen('php://memory', 'w');
var_dump(stream_set_write_buffer($file, 4096));
?>
--EXPECT--
int(-1)
int(-1)
int(-1)
int(-1)
