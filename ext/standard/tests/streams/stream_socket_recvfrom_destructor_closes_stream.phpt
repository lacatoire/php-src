--TEST--
stream_socket_recvfrom(): a destructor of the old $address value closing the stream
--FILE--
<?php

class Closer {
    public $stream;
    public function __destruct() {
        fclose($this->stream);
    }
}

[$a, $b] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
fwrite($b, 'data');
$address = new Closer;
$address->stream = $a;

try {
    var_dump(stream_socket_recvfrom($a, 10, 0, $address));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

?>
--EXPECT--
TypeError: stream_socket_recvfrom(): supplied resource is not a valid stream resource
