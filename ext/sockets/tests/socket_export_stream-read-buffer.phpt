--TEST--
socket_export_stream(): the exported stream does not buffer reads behind the Socket
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') {
    die('skip not for Windows');
}
?>
--FILE--
<?php

socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $pair);
[$socket, $peer] = $pair;

$stream = socket_export_stream($socket);
socket_write($peer, '0123456789');

var_dump(fread($stream, 2));
var_dump(stream_get_meta_data($stream)['unread_bytes']);

socket_set_nonblock($socket);
$read = [$socket];
$write = null;
$except = null;
var_dump(socket_select($read, $write, $except, 0));
var_dump(socket_recv($socket, $buffer, 100, 0));
var_dump($buffer);

?>
--EXPECT--
string(2) "01"
int(0)
int(1)
int(8)
string(8) "23456789"
