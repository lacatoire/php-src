--TEST--
session_start() stops applying options when an error handler throws
--EXTENSIONS--
session
--SKIPIF--
<?php include 'skipif.inc'; ?>
--INI--
session.use_cookies=0
session.cache_limiter=
--FILE--
<?php

ob_start();

set_error_handler(function ($errno, $message) {
    throw new Exception($message);
});

try {
    session_start(['save_handler' => 'user', 'name' => 'AFTERX']);
    echo "No exception\n";
} catch (Exception $e) {
    echo get_class($e), ': ', $e->getMessage(), "\n";
}

var_dump(session_status() === PHP_SESSION_NONE);
var_dump(session_name());

?>
--EXPECT--
Exception: session_start(): Session save handler "user" cannot be set by ini_set()
bool(true)
string(9) "PHPSESSID"
