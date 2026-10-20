--TEST--
phpdbg_exec() rejects non-regular files and paths containing null bytes
--PHPDBG--
r
q
--EXPECTF--
[Successful compilation of %s]
prompt> 
Warning: Failed to set execution context (/dev/null), not a regular file or symlink in %s on line %d
bool(false)
phpdbg_exec(): Argument #1 ($context) must not contain any null bytes
[Script ended normally]
prompt>
--FILE--
<?php

var_dump(phpdbg_exec('/dev/null'));

try {
    phpdbg_exec("/dev/null\0x");
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}
