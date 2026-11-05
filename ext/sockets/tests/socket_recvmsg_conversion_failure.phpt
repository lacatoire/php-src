--TEST--
socket_recvmsg(): failed output conversion keeps typed properties valid, does not read freed memory and does not leak descriptors
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (PHP_OS_FAMILY !== 'Linux') {
    die('skip Linux only');
}
?>
--FILE--
<?php

const SO_TIMESTAMP_LINUX = 29;

class Holder
{
    public array $message = [];
}

function udp_pair(): array
{
    $rx = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_bind($rx, '127.0.0.1', 0);
    socket_getsockname($rx, $address, $port);
    // The control message this enables is not supported by the conversion
    socket_set_option($rx, SOL_SOCKET, SO_TIMESTAMP_LINUX, 1);
    $tx = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    socket_sendto($tx, 'hello', 5, 0, '127.0.0.1', $port);
    return [$rx, $tx];
}

echo "Typed property\n";
[$rx, $tx] = udp_pair();
$holder = new Holder();
$holder->message = ['name' => [], 'buffer_size' => 64, 'controllen' => 64];
try {
    var_dump(@socket_recvmsg($rx, $holder->message, 0));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
var_dump(is_array($holder->message));

echo "Error handler\n";
[$rx, $tx] = udp_pair();
$message = ['name' => [], 'buffer_size' => 64, 'controllen' => 64, 'pad' => range(1, 100)];
set_error_handler(function () use (&$message) {
    var_dump(count($message['pad']));
    return true;
});
var_dump(socket_recvmsg($rx, $message, 0));
restore_error_handler();
var_dump($message);

echo "Descriptors\n";
function fd_count(): int
{
    return count(scandir('/proc/self/fd'));
}

function receive_rights(): void
{
    socket_create_pair(AF_UNIX, SOCK_DGRAM, 0, $pair);
    socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $payload);
    socket_set_option($pair[1], SOL_SOCKET, SO_TIMESTAMP_LINUX, 1);
    $out = [
        'iov' => ['x'],
        'control' => [['level' => SOL_SOCKET, 'type' => SCM_RIGHTS, 'data' => [$payload[0], $payload[1]]]],
    ];
    socket_sendmsg($pair[0], $out, 0);
    $in = ['name' => [], 'buffer_size' => 16, 'controllen' => socket_cmsg_space(SOL_SOCKET, SCM_RIGHTS, 2) + 64];
    @socket_recvmsg($pair[1], $in, 0);
}

receive_rights();
$before = fd_count();
for ($i = 0; $i < 10; $i++) {
    receive_rights();
}
var_dump(fd_count() === $before);

?>
--EXPECT--
Typed property
TypeError: Cannot assign false to reference held by property Holder::$message of type array
bool(true)
Error handler
int(100)
int(5)
bool(false)
Descriptors
bool(true)
