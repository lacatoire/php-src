--TEST--
phpdbg_break_file() rejects a line number lower than 1
--PHPDBG--
r
q
--EXPECTF--
[Successful compilation of %s]
prompt> phpdbg_break_file(): Argument #2 ($line) must be greater than 0
phpdbg_break_file(): Argument #2 ($line) must be greater than 0
[Script ended normally]
prompt> 
--FILE--
<?php

foreach ([-1, 0] as $line) {
    try {
        phpdbg_break_file(__FILE__, $line);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}
