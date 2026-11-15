--TEST--
stream_socket_pair() rejects $domain, $type and $protocol outside of the int range
--SKIPIF--
<?php
if (PHP_INT_SIZE != 8) die('skip 64-bit only');
?>
--FILE--
<?php
$arguments = [
    [STREAM_PF_UNIX + 2 ** 32, STREAM_SOCK_STREAM, 0],
    [STREAM_PF_UNIX, STREAM_SOCK_STREAM + 2 ** 32, 0],
    [STREAM_PF_UNIX, STREAM_SOCK_STREAM, PHP_INT_MIN],
];
foreach ($arguments as [$domain, $type, $protocol]) {
    try {
        var_dump(is_array(stream_socket_pair($domain, $type, $protocol)));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}

var_dump(is_array(stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, 0)));

?>
--EXPECT--
ValueError: stream_socket_pair(): Argument #1 ($domain) must be between -2147483648 and 2147483647
ValueError: stream_socket_pair(): Argument #2 ($type) must be between -2147483648 and 2147483647
ValueError: stream_socket_pair(): Argument #3 ($protocol) must be between -2147483648 and 2147483647
bool(true)
