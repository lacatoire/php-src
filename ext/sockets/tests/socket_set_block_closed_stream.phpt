--TEST--
socket_set_block() and socket_set_nonblock(): the stream of the Socket was closed
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

// Whether accept() waits for the receive timeout (blocking) or returns at once
function is_blocking($socket): bool
{
    $start = hrtime(true);
    @socket_accept($socket);
    return (hrtime(true) - $start) > 150_000_000;
}

$a = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
$stream = socket_export_stream($a);
fclose($stream);

// Takes the descriptor number of $a
$b = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($b, '127.0.0.1', 0);
socket_listen($b);
socket_set_option($b, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 0, 'usec' => 300000]);

var_dump(socket_set_nonblock($a));
var_dump(socket_last_error($a) === SOCKET_EBADF);
var_dump(is_blocking($b));

var_dump(socket_set_block($a));
var_dump(socket_last_error($a) === SOCKET_EBADF);
var_dump(is_blocking($b));

?>
--EXPECTF--
Warning: socket_set_nonblock(): unable to set nonblocking mode [%d]: %s in %s on line %d
bool(false)
bool(true)
bool(true)

Warning: socket_set_block(): unable to set blocking mode [%d]: %s in %s on line %d
bool(false)
bool(true)
bool(true)
