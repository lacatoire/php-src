--TEST--
stream_select(): seconds and microseconds do not overflow when combined
--FILE--
<?php

[$reader, $writer] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, 0);
fwrite($writer, "x");

$read = [$reader];
$write = null;
$except = null;
var_dump(stream_select($read, $write, $except, PHP_INT_MAX, 1000000));
var_dump(count($read));

?>
--EXPECT--
int(1)
int(1)
