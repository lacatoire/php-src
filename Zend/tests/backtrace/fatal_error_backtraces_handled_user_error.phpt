--TEST--
error_get_last() has no fatal error backtrace for an E_USER_ERROR handled by a user error handler
--INI--
fatal_error_backtraces=On
--FILE--
<?php
@trigger_error('older warning', E_USER_WARNING);
set_error_handler(fn() => true);
trigger_error('handled', E_USER_ERROR);
$error = error_get_last();
var_dump($error['message'], array_key_exists('trace', $error));
?>
--EXPECT--
string(13) "older warning"
bool(false)
