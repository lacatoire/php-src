--TEST--
gmp_pow() throws for an exponent that makes the result too large
--EXTENSIONS--
gmp
--FILE--
<?php
foreach ([[2, 1 << 45], [2, PHP_INT_MAX], [3, 1 << 62], [-5, 1 << 60]] as [$base, $exp]) {
    try {
        gmp_pow($base, $exp);
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
var_dump(gmp_pow(1, PHP_INT_MAX) == 1, gmp_pow(0, PHP_INT_MAX) == 0, gmp_pow(-1, PHP_INT_MAX) == -1);
var_dump(gmp_pow(2, 10) == 1024);
?>
--EXPECT--
ValueError: gmp_pow(): Argument #2 ($exponent) is too large for this base
ValueError: gmp_pow(): Argument #2 ($exponent) is too large for this base
ValueError: gmp_pow(): Argument #2 ($exponent) is too large for this base
ValueError: gmp_pow(): Argument #2 ($exponent) is too large for this base
bool(true)
bool(true)
bool(true)
bool(true)
