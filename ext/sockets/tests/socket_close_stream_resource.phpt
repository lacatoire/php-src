--TEST--
socket_close(): releases the stream resource of an exported or imported Socket
--EXTENSIONS--
sockets
--FILE--
<?php

$domain = PHP_OS_FAMILY == 'Windows' ? AF_INET : AF_UNIX;

$before = count(get_resources());
for ($i = 0; $i < 10; $i++) {
    $socket = socket_create($domain, SOCK_STREAM, 0);
    $stream = socket_export_stream($socket);
    socket_close($socket);
    unset($socket, $stream);
}
var_dump(count(get_resources()) - $before);

$before = count(get_resources());
for ($i = 0; $i < 10; $i++) {
    $socket = socket_create($domain, SOCK_STREAM, 0);
    $stream = socket_export_stream($socket);
    $imported = socket_import_stream($stream);
    socket_close($imported);
    unset($socket, $stream, $imported);
}
var_dump(count(get_resources()) - $before);

?>
--EXPECT--
int(0)
int(0)
