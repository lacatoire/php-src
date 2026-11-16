--TEST--
socket_import_stream(): the stream cannot be closed from an error handler run during the import
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

$server = stream_socket_server('tcp://127.0.0.1:0');
$address = stream_socket_get_name($server, false);
$client = stream_socket_client($address);
$peer = stream_socket_accept($server);

fwrite($peer, 'abcdef');
fread($client, 2);

$spare = [];
set_error_handler(function () use (&$client, &$spare, $address) {
    var_dump(fclose($client));
    $spare[] = stream_socket_client($address);
    return true;
});

var_dump(socket_import_stream($client) instanceof Socket);

?>
--EXPECTF--
Warning: fclose(): cannot close the provided stream, as it must not be manually closed in %s on line %d
bool(false)
bool(true)
