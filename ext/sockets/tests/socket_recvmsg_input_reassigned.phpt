--TEST--
socket_recvmsg(): $message reassigned during the conversion of its own elements
--EXTENSIONS--
sockets
--FILE--
<?php

class Size
{
    public function __toString(): string
    {
        global $message;
        $message = 5;
        return '64';
    }
}

$rx = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_bind($rx, '127.0.0.1', 0);
socket_getsockname($rx, $address, $port);
$tx = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_sendto($tx, 'hello', 5, 0, '127.0.0.1', $port);

$message = ['name' => [], 'buffer_size' => new Size(), 'controllen' => socket_cmsg_space(SOL_SOCKET, SCM_RIGHTS, 1)];
var_dump(socket_recvmsg($rx, $message, 0));
var_dump($message['iov']);

try {
    socket_recvmsg($rx, $unset, 0);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

?>
--EXPECT--
int(5)
array(1) {
  [0]=>
  string(5) "hello"
}
TypeError: socket_recvmsg(): Argument #2 ($message) must be of type array, null given
