--TEST--
socket_recv(): the error code is not changed by a destructor of the previous $data
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

class Logger
{
    public function __destruct()
    {
        @fopen('/nonexistent/file', 'r');
    }
}

socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $pair);
socket_set_nonblock($pair[1]);

$data = new Logger();
var_dump(socket_recv($pair[1], $data, 10, 0));
var_dump(socket_last_error($pair[1]) === SOCKET_EAGAIN);

?>
--EXPECT--
bool(false)
bool(true)
