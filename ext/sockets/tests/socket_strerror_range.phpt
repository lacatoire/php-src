--TEST--
socket_strerror(): range of the error code and host lookup errors
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (PHP_INT_SIZE != 8) die("skip this test is for 64bit platform only");
if (!stristr(PHP_OS, "Linux")) {
    die('skip - test validates linux error strings only.');
}
if (socket_strerror(-10001) !== 'Unknown host') {
    die('skip - the messages of the resolver differ with this libc.');
}
?>
--FILE--
<?php
foreach ([-2147483649, 2147483648, PHP_INT_MIN, PHP_INT_MAX] as $code) {
    try {
        var_dump(socket_strerror($code));
    } catch (ValueError $e) {
        echo $e->getMessage(), PHP_EOL;
    }
}

// Both ends of the int range are accepted, and no code is read as a host lookup error
var_dump(socket_strerror(-2147483648));
var_dump(socket_strerror(2147483647));

// Host lookup errors are the codes below -10000
foreach ([-10000, -10001, -10002, -10003, -10004] as $code) {
    var_dump(socket_strerror($code));
}
?>
--EXPECT--
socket_strerror(): Argument #1 ($error_code) must be between -2147483648 and 2147483647
socket_strerror(): Argument #1 ($error_code) must be between -2147483648 and 2147483647
socket_strerror(): Argument #1 ($error_code) must be between -2147483648 and 2147483647
socket_strerror(): Argument #1 ($error_code) must be between -2147483648 and 2147483647
string(22) "Unknown resolver error"
string(24) "Unknown error 2147483647"
string(20) "Unknown error -10000"
string(12) "Unknown host"
string(24) "Host name lookup failure"
string(20) "Unknown server error"
string(31) "No address associated with name"
