--TEST--
socket_select(): seconds and microseconds do not overflow when combined
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    die('skip not valid for Windows');
}
?>
--FILE--
<?php
$sockets = [];
socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $sockets);
socket_write($sockets[1], 'x');

$read = [$sockets[0]];
$write = null;
$except = null;
var_dump(socket_select($read, $write, $except, PHP_INT_MAX, 1000000));
var_dump(count($read));
?>
--EXPECT--
int(1)
int(1)
