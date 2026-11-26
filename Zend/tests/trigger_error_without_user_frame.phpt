--TEST--
trigger_error() without a user frame reports the file as Unknown
--INI--
ignore_repeated_errors=1
--FILE--
<?php
register_shutdown_function('trigger_error', 'dup');
register_shutdown_function('trigger_error', 'dup');
register_shutdown_function(function () {
    set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline) {
        var_dump($errfile, $errline);
        return true;
    });
});
register_shutdown_function('trigger_error', 'handled');
?>
--EXPECT--
Notice: dup in Unknown on line 0
string(7) "Unknown"
int(0)
