--TEST--
phpcredits(): flags outside the 32-bit range are rejected instead of wrapping around
--FILE--
<?php
foreach ([1 << 32, 1 + (1 << 32), PHP_INT_MAX, PHP_INT_MIN, 0xFFFFFFFF + 1] as $flag) {
    try {
        phpcredits($flag);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}

ob_start();
var_dump(phpcredits(1));
var_dump(phpcredits(-1));
ob_end_clean();
echo "done\n";
?>
--EXPECT--
phpcredits(): Argument #1 ($flags) must be between -2147483648 and 4294967295
phpcredits(): Argument #1 ($flags) must be between -2147483648 and 4294967295
phpcredits(): Argument #1 ($flags) must be between -2147483648 and 4294967295
phpcredits(): Argument #1 ($flags) must be between -2147483648 and 4294967295
phpcredits(): Argument #1 ($flags) must be between -2147483648 and 4294967295
done
