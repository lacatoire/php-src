--TEST--
stream_set_timeout() rejects values that would turn into an infinite wait and normalises microseconds
--FILE--
<?php
[$a, $b] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, 0);

foreach ([[1, 0], [0, 500000], [1, -3000], [3, 2500000], [-1, 0], [0, -1000], [0, -1500000], [2, -3000000], [-5, 0], [2147483, 0], [PHP_INT_MAX, 0], [2147482, 1000000]] as [$seconds, $microseconds]) {
    echo "$seconds, $microseconds: ";
    try {
        var_dump(stream_set_timeout($a, $seconds, $microseconds));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}

stream_set_timeout($a, 1, -900000);
var_dump(stream_get_meta_data($a)['timed_out']);
var_dump(fread($a, 10));
var_dump(stream_get_meta_data($a)['timed_out']);
?>
--EXPECT--
1, 0: bool(true)
0, 500000: bool(true)
1, -3000: bool(true)
3, 2500000: bool(true)
-1, 0: bool(true)
0, -1000: ValueError: stream_set_timeout(): Argument #3 ($microseconds) must give a timeout between 0 and 2147482 seconds
0, -1500000: ValueError: stream_set_timeout(): Argument #3 ($microseconds) must give a timeout between 0 and 2147482 seconds
2, -3000000: ValueError: stream_set_timeout(): Argument #3 ($microseconds) must give a timeout between 0 and 2147482 seconds
-5, 0: ValueError: stream_set_timeout(): Argument #2 ($seconds) must be -1 or between 0 and 2147482
2147483, 0: ValueError: stream_set_timeout(): Argument #2 ($seconds) must be -1 or between 0 and 2147482
9223372036854775807, 0: ValueError: stream_set_timeout(): Argument #2 ($seconds) must be -1 or between 0 and 2147482
2147482, 1000000: ValueError: stream_set_timeout(): Argument #3 ($microseconds) must give a timeout between 0 and 2147482 seconds
bool(false)
bool(false)
bool(true)
