--TEST--
socket_select(): a descriptor above FD_SETSIZE records an error code
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (PHP_OS_FAMILY !== 'Linux') {
    die('skip Linux only');
}
if ((int) shell_exec('ulimit -n') < 2200) {
    die('skip the limit of open files is too low');
}
?>
--FILE--
<?php

$pairs = [];
for ($i = 0; $i < 530; $i++) {
    socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $pair);
    $pairs[] = $pair;
}

$read = [];
foreach ($pairs as $pair) {
    $read[] = $pair[0];
    $read[] = $pair[1];
}
$write = null;
$except = null;

socket_clear_error();
var_dump(@socket_select($read, $write, $except, 0));
var_dump(socket_last_error() === SOCKET_EINVAL);

?>
--EXPECT--
bool(false)
bool(true)
