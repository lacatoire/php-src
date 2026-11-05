--TEST--
socket_sendmsg(): exceptions during the conversion, missing control keys and values held by references
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

function receive($socket): array
{
    $received = [];
    while (socket_recv($socket, $buffer, 64, MSG_DONTWAIT)) {
        $received[] = $buffer;
    }
    return $received;
}

class Boom
{
    public function __toString(): string
    {
        throw new Exception('boom');
    }
}

echo "Exception during the conversion\n";
socket_create_pair(AF_UNIX, SOCK_DGRAM, 0, $pair);
set_error_handler(function ($no, $msg) { throw new ErrorException($msg); });
foreach ([['A', [1], 'C'], ['A', new Boom, 'C']] as $iov) {
    try {
        var_dump(socket_sendmsg($pair[0], ['iov' => $iov], 0));
    } catch (Throwable $e) {
        echo get_class($e), ': ', $e->getMessage(), "\n";
    }
}
restore_error_handler();
var_dump(receive($pair[1]));

echo "Missing keys of a control message\n";
foreach ([['data' => 5], ['level' => SOL_SOCKET], ['level' => IPPROTO_IPV6, 'type' => IPV6_HOPLIMIT]] as $control) {
    var_dump(socket_sendmsg($pair[0], ['iov' => ['x'], 'control' => [$control]], 0));
}
var_dump(receive($pair[1]));

echo "Values held by references\n";
$message = ['iov' => ['a', 'b']];
foreach ($message['iov'] as &$buffer) {
}
unset($buffer);
var_dump(socket_sendmsg($pair[0], $message, 0));

$level = SOL_SOCKET;
$type = SCM_RIGHTS;
$data = [$pair[1]];
var_dump(socket_sendmsg($pair[0], ['iov' => ['c'], 'control' => [['level' => &$level, 'type' => &$type, 'data' => &$data]]], 0));

class Overwrite
{
    public function __toString(): string
    {
        global $message;
        $message['iov'] = null;
        return 'e';
    }
}
$message = ['iov' => ['d', new Overwrite(), 'f']];
foreach ($message as &$value) {
}
unset($value);
var_dump(socket_sendmsg($pair[0], $message, 0));
var_dump(receive($pair[1]));

?>
--EXPECTF--
Exception during the conversion
ErrorException: Array to string conversion
Exception: boom
array(0) {
}
Missing keys of a control message

Warning: socket_sendmsg(): error converting user data (path: msghdr > control > element #0): The key 'level' is required in %s on line %d
bool(false)

Warning: socket_sendmsg(): error converting user data (path: msghdr > control > element #0): The key 'type' is required in %s on line %d
bool(false)

Warning: socket_sendmsg(): error converting user data (path: msghdr > control > element #0): The key 'data' is required in %s on line %d
bool(false)
array(0) {
}
Values held by references
int(2)
int(1)
int(3)
array(3) {
  [0]=>
  string(2) "ab"
  [1]=>
  string(1) "c"
  [2]=>
  string(3) "def"
}
