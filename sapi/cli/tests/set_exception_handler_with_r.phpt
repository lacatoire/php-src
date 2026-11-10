--TEST--
set_exception_handler() handler is called for an uncaught exception with -r and -R
--SKIPIF--
<?php
include "skipif.inc";
?>
--FILE--
<?php

$php = getenv('TEST_PHP_EXECUTABLE_ESCAPED');
$code = 'set_exception_handler(function ($e) { echo "handled: ", $e->getMessage(), "\n"; }); throw new Exception("x");';

echo shell_exec("$php -n -r " . escapeshellarg($code) . " 2>&1");
echo shell_exec("echo line | $php -n -R " . escapeshellarg($code) . " 2>&1");

// Without a handler the exception is still fatal.
echo shell_exec("$php -n -r " . escapeshellarg('throw new Exception("y");') . " 2>&1");

?>
--EXPECTF--
handled: x
handled: x

Fatal error: Uncaught Exception: y in Command line code:1
Stack trace:
#0 {main}
  thrown in Command line code on line 1
