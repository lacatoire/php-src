--TEST--
stream_socket_accept() gives an empty $peer_name for an unnamed unix socket client
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip not for Windows');
?>
--FILE--
<?php
$path = sys_get_temp_dir() . '/stream_socket_accept_unnamed_' . getmypid() . '.sock';
$server = stream_socket_server("unix://$path");
$client = stream_socket_client("unix://$path");
$accepted = stream_socket_accept($server, 1, $peerName);
var_dump($peerName);
unlink($path);
?>
--EXPECT--
string(0) ""
