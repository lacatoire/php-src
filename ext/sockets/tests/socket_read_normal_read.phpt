--TEST--
socket_read(): PHP_NORMAL_READ on a non-blocking socket, at end of stream and with a partial line
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

function show(mixed $value): void
{
    echo json_encode($value), "\n";
}

function pair(): array
{
    socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $pair);
    return $pair;
}

echo "No data, non-blocking\n";
[$a, $b] = pair();
socket_set_nonblock($b);
show(socket_read($b, 100, PHP_NORMAL_READ));
show(socket_last_error($b) === SOCKET_EAGAIN);

echo "Partial line, non-blocking\n";
socket_write($a, 'ab');
show(socket_read($b, 100, PHP_NORMAL_READ));
show(socket_read($b, 100, PHP_NORMAL_READ));

echo "No data, receive timeout\n";
[$a, $b] = pair();
socket_set_option($b, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 0, 'usec' => 100000]);
show(socket_read($b, 100, PHP_NORMAL_READ));
show(socket_last_error($b) === SOCKET_EAGAIN);

echo "Lines and length limit\n";
[$a, $b] = pair();
socket_write($a, "one\ntwo\rthree");
show(socket_read($b, 100, PHP_NORMAL_READ));
show(socket_read($b, 100, PHP_NORMAL_READ));
show(socket_read($b, 2, PHP_NORMAL_READ));

echo "Closed peer\n";
socket_close($a);
show(socket_read($b, 100, PHP_NORMAL_READ));
show(socket_read($b, 100, PHP_NORMAL_READ));

echo "Closed peer, no data\n";
[$a, $b] = pair();
socket_close($a);
show(socket_read($b, 100, PHP_NORMAL_READ));

echo "Partial line, closed peer\n";
function partial(): void
{
    [$a, $b] = pair();
    socket_write($a, 'ab');
    socket_close($a);
    show(socket_read($b, 100, PHP_NORMAL_READ));
}
partial();
[$a, $b] = pair();
socket_write($a, "ab\n");
$line = socket_read($b, 100);
unset($line);
partial();

?>
--EXPECT--
No data, non-blocking
false
true
Partial line, non-blocking
"ab"
false
No data, receive timeout
false
true
Lines and length limit
"one\n"
"two\r"
"th"
Closed peer
"ree"
""
Closed peer, no data
""
Partial line, closed peer
"ab"
"ab"
