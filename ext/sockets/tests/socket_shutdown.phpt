--TEST--
bool socket_shutdown ( resource $socket [, int $how = 2 ] ) ;
--CREDITS--
marcosptf - <marcosptf@yahoo.com.br> - #phparty7 - @phpsp - novatec/2015 - sao paulo - br
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (substr(PHP_OS, 0, 3) == 'WIN' ) {
    die('skip not for windows');
}
?>
--FILE--
<?php
$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($server, '127.0.0.1', 0);
socket_listen($server, 5);
socket_getsockname($server, $host, $port);

foreach ([SHUT_RD, SHUT_WR, SHUT_RDWR] as $mode) {
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    socket_connect($socket, $host, $port);
    var_dump(socket_shutdown($socket, $mode));
    socket_close($socket);
}

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
var_dump(socket_shutdown($socket, SHUT_RD));

socket_connect($socket, $host, $port);

try {
	socket_shutdown($socket, -1);
} catch (\ValueError $e) {
	echo $e->getMessage(), PHP_EOL;
}
socket_close($socket);
?>
--EXPECTF--
bool(true)
bool(true)
bool(true)

Warning: socket_shutdown(): Unable to shutdown socket [%d]: %s in %s on line %d
bool(false)
socket_shutdown(): Argument #2 ($mode) must be one of SHUT_RD, SHUT_WR or SHUT_RDWR
