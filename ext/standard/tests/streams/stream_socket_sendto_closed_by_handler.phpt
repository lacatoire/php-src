--TEST--
stream_socket_sendto(): an error handler closing the stream during argument parsing
--FILE--
<?php

[$a, $b] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, 0);
set_error_handler(function () use (&$a) {
    fclose($a);
    return true;
});

try {
    var_dump(stream_socket_sendto($a, 'x', 0, null));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

?>
--EXPECT--
TypeError: stream_socket_sendto(): supplied resource is not a valid stream resource
