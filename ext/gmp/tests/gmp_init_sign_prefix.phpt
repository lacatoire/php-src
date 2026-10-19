--TEST--
gmp_init(): the sign goes before the radix prefix
--EXTENSIONS--
gmp
--FILE--
<?php
foreach (["-0x10", "-0b11", "-0o10", "0o-10", "0x-1", "0b-101", "0x+1", "-0x", "-5", "-0", "0x10", "-0X1f", "-0B11", "-0O17"] as $s) {
    try {
        $result = gmp_strval(gmp_init($s));
    } catch (Throwable $e) {
        $result = $e::class . ': ' . $e->getMessage();
    }
    echo str_pad($s, 8), " -> ", $result, "\n";
}
var_dump(gmp_strval(gmp_init("-0x10", 16)));
?>
--EXPECT--
-0x10    -> -16
-0b11    -> -3
-0o10    -> -8
0o-10    -> ValueError: gmp_init(): Argument #1 ($num) is not an integer string
0x-1     -> ValueError: gmp_init(): Argument #1 ($num) is not an integer string
0b-101   -> ValueError: gmp_init(): Argument #1 ($num) is not an integer string
0x+1     -> ValueError: gmp_init(): Argument #1 ($num) is not an integer string
-0x      -> ValueError: gmp_init(): Argument #1 ($num) is not an integer string
-5       -> -5
-0       -> 0
0x10     -> 16
-0X1f    -> -31
-0B11    -> -3
-0O17    -> -15
string(3) "-16"
