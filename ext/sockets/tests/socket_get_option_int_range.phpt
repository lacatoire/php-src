--TEST--
socket_get_option(): $level and $option outside the int range are rejected
--EXTENSIONS--
sockets
--SKIPIF--
<?php if (PHP_INT_SIZE != 8) die("skip this test is for 64bit platform only"); ?>
--FILE--
<?php

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

foreach ([
    [2 ** 32 + SOL_SOCKET, SO_LINGER],
    [2 ** 32 + SOL_TCP, TCP_NODELAY],
    [PHP_INT_MIN, SO_LINGER],
    [SOL_SOCKET, 2 ** 32 + SO_LINGER],
    [SOL_SOCKET, PHP_INT_MAX],
] as [$level, $option]) {
    try {
        var_dump(socket_get_option($socket, $level, $option));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}

var_dump(socket_get_option($socket, SOL_SOCKET, SO_TYPE) === SOCK_STREAM);

?>
--EXPECT--
ValueError: socket_get_option(): Argument #2 ($level) must be between -2147483648 and 2147483647
ValueError: socket_get_option(): Argument #2 ($level) must be between -2147483648 and 2147483647
ValueError: socket_get_option(): Argument #2 ($level) must be between -2147483648 and 2147483647
ValueError: socket_get_option(): Argument #3 ($option) must be between -2147483648 and 2147483647
ValueError: socket_get_option(): Argument #3 ($option) must be between -2147483648 and 2147483647
bool(true)
