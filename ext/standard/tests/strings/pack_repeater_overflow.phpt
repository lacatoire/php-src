--TEST--
pack(): repeater count exceeding INT_MAX is rejected
--FILE--
<?php
foreach (["a4294967297", "a4294967296", "x4294967297", "c4294967297", "n4294967297", "a2147483648", "a99999999999"] as $format) {
    try {
        pack($format, "x", 1, 1);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}
?>
--EXPECT--
Type a: integer overflow in format string
Type a: integer overflow in format string
Type x: integer overflow in format string
Type c: integer overflow in format string
Type n: integer overflow in format string
Type a: integer overflow in format string
Type a: integer overflow in format string
