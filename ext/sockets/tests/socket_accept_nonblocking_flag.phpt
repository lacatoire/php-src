--TEST--
socket_accept(): the accepted socket inherits the blocking mode of the listener
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (strtolower(substr(PHP_OS, 0, 3)) == 'win') die('skip not for Windows');
?>
--FILE--
<?php
foreach ([true, false] as $nonblock) {
    $l = socket_create(AF_INET, SOCK_STREAM, 0);
    socket_bind($l, '127.0.0.1', 0);
    socket_listen($l);
    socket_getsockname($l, $addr, $port);
    if ($nonblock) {
        socket_set_nonblock($l);
    }
    $c = socket_create(AF_INET, SOCK_STREAM, 0);
    socket_connect($c, '127.0.0.1', $port);
    $a = socket_accept($l);
    $stream = socket_export_stream($a);
    $blocked = stream_get_meta_data($stream)['blocked'];
    echo $nonblock ? 'non-blocking listener' : 'blocking listener', ': accepted socket ', $blocked ? 'blocking' : 'non-blocking', "\n";
}
?>
--EXPECT--
non-blocking listener: accepted socket non-blocking
blocking listener: accepted socket blocking
