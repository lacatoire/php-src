--TEST--
stream_select() does not wait after throwing for an element that is not a stream
--FILE--
<?php
[$a, $b] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, 0);
$write = $except = null;

$read = [$a, 5];
$start = microtime(true);
try {
    stream_select($read, $write, $except, 2);
} catch (Throwable $e) {
    printf("%s after %d second(s)\n", $e::class, round(microtime(true) - $start));
}

$read = [5];
try {
    stream_select($read, $write, $except, 0);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

/* The exception is thrown once, without a previous one */
$read = [$a, 5];
$write = [6];
try {
    stream_select($read, $write, $except, 0);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
    var_dump($e->getPrevious());
}

?>
--EXPECT--
TypeError after 0 second(s)
TypeError: stream_select(): supplied argument is not a valid stream resource
TypeError: stream_select(): supplied argument is not a valid stream resource
NULL
