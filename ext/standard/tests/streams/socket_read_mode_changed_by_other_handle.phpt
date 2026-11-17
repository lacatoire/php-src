--TEST--
A non-blocking socket stream does not block when another handle makes the socket blocking
--SKIPIF--
<?php
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    die('skip not valid for Windows');
}
?>
--FILE--
<?php
[$socket, $peer] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, 0);
stream_set_blocking($socket, false);

$code = 'stream_set_blocking(STDIN, true); echo "ready"; sleep(10);';
$process = proc_open([PHP_BINARY, '-n', '-r', $code], [
    0 => $socket,
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w'],
], $pipes);

// The child has made the shared socket blocking once it prints "ready".
fread($pipes[1], 5);

stream_set_timeout($socket, 1);
var_dump(fread($socket, 10));

proc_terminate($process);
proc_close($process);
?>
--EXPECT--
string(0) ""
