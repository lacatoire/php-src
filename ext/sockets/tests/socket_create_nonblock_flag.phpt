--TEST--
socket_create() and socket_create_pair(): SOCK_NONBLOCK is reflected by the Socket
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (!defined('SOCK_NONBLOCK')) {
    die('skip SOCK_NONBLOCK is not available');
}
if (!function_exists('socket_create_pair')) {
    die('skip socket_create_pair() is not available');
}
?>
--FILE--
<?php
function blocked($socket): bool {
    return stream_get_meta_data(socket_export_stream($socket))['blocked'];
}

$socket = socket_create(AF_INET, SOCK_STREAM | SOCK_NONBLOCK, SOL_TCP);
var_dump(blocked($socket));

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
var_dump(blocked($socket));

socket_create_pair(AF_UNIX, SOCK_STREAM | SOCK_NONBLOCK, 0, $pair);
var_dump(blocked($pair[0]), blocked($pair[1]));

socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $pair);
var_dump(blocked($pair[0]), blocked($pair[1]));
?>
--EXPECT--
bool(false)
bool(true)
bool(false)
bool(false)
bool(true)
bool(true)
