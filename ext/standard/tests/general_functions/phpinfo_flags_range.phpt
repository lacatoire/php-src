--TEST--
phpinfo(): flags outside the 32-bit range are rejected
--SKIPIF--
<?php
if (PHP_INT_SIZE < 8) die("skip 64-bit only");
?>
--FILE--
<?php

foreach ([1 << 32, 2 + (1 << 32), PHP_INT_MAX, PHP_INT_MIN] as $flags) {
    try {
        phpinfo($flags);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}

ob_start();
var_dump(phpinfo(-1));
$all = ob_get_clean();
var_dump(str_contains($all, 'PHP Credits'));

?>
--EXPECT--
phpinfo(): Argument #1 ($flags) must be between -2147483648 and 4294967295
phpinfo(): Argument #1 ($flags) must be between -2147483648 and 4294967295
phpinfo(): Argument #1 ($flags) must be between -2147483648 and 4294967295
phpinfo(): Argument #1 ($flags) must be between -2147483648 and 4294967295
bool(true)
