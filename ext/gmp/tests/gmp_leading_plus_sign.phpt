--TEST--
GMP: strings with a leading + sign are accepted like their - counterparts
--EXTENSIONS--
gmp
--FILE--
<?php
// Leading + must be accepted everywhere a numeric string is accepted.
var_dump(gmp_strval(gmp_init("+10")));        // string(2) "10"
var_dump(gmp_strval(gmp_init("+10", 10)));    // string(2) "10"
var_dump(gmp_strval(gmp_init("+0x10")));      // string(2) "16"
var_dump(gmp_strval(gmp_init("+0b1010")));    // string(2) "10"
var_dump(gmp_strval(gmp_init("+0o12")));      // string(2) "10"
var_dump(gmp_strval(gmp_add("+10", 0)));      // string(2) "10"
var_dump(gmp_strval(gmp_add(0, "+10")));      // string(2) "10"
var_dump(gmp_strval(gmp_mul("+10", 3)));      // string(2) "30"

// Leading whitespace + plus sign
var_dump(gmp_strval(gmp_init(" +10")));       // string(2) "10"

// Symmetry: leading - still works
var_dump(gmp_strval(gmp_init("-10")));        // string(3) "-10"

// Bare + (no digits) must still fail
try {
    gmp_init("+");
} catch (ValueError $e) {
    echo "ValueError: " . $e->getMessage() . "\n";
}
?>
--EXPECT--
string(2) "10"
string(2) "10"
string(2) "16"
string(2) "10"
string(2) "10"
string(2) "10"
string(2) "10"
string(2) "30"
string(2) "10"
string(3) "-10"
ValueError: gmp_init(): Argument #1 ($num) is not an integer string
