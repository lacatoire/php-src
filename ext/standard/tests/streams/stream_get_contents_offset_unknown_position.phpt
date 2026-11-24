--TEST--
stream_get_contents() with an offset on a stream whose position is unknown
--SKIPIF--
<?php
if (!function_exists('proc_open')) die('skip proc_open() is not available');
?>
--FILE--
<?php

$command = [getenv('TEST_PHP_EXECUTABLE'), '-n', '-r', 'echo "0123456789";'];

/* A pipe cannot seek: the offset is refused instead of being ignored */
$process = proc_open($command, [1 => ['pipe', 'w']], $pipes);
var_dump(stream_get_contents($pipes[1], null, 3));
fclose($pipes[1]);
proc_close($process);

/* An offset of 0 is still accepted */
$process = proc_open($command, [1 => ['pipe', 'w']], $pipes);
var_dump(stream_get_contents($pipes[1], null, 0));
fclose($pipes[1]);
proc_close($process);

/* php://memory loses its position after a failed fseek() but can still seek */
$memory = fopen('php://memory', 'w+');
fwrite($memory, '0123456789');
var_dump(@fseek($memory, -100, SEEK_END));
var_dump(stream_get_contents($memory, null, 3));

?>
--EXPECTF--
Warning: stream_get_contents(): Stream does not support seeking in %s on line %d

Warning: stream_get_contents(): Failed to seek to position 3 in the stream in %s on line %d
bool(false)
string(10) "0123456789"
int(-1)
string(7) "3456789"
