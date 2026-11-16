--TEST--
intval(): the base must be 0 or between 2 and 36 for a string
--FILE--
<?php
foreach ([1, 37, -2, 2**32 + 10, PHP_INT_MAX, PHP_INT_MIN] as $base) {
    try {
        var_dump(intval("10", $base));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
// The base is ignored for other types
var_dump(intval(10.5, 37));
var_dump(intval("10", 0), intval("10", 2), intval("10", 36));
?>
--EXPECT--
ValueError: intval(): Argument #2 ($base) must be 0 or between 2 and 36 (inclusive)
ValueError: intval(): Argument #2 ($base) must be 0 or between 2 and 36 (inclusive)
ValueError: intval(): Argument #2 ($base) must be 0 or between 2 and 36 (inclusive)
ValueError: intval(): Argument #2 ($base) must be 0 or between 2 and 36 (inclusive)
ValueError: intval(): Argument #2 ($base) must be 0 or between 2 and 36 (inclusive)
ValueError: intval(): Argument #2 ($base) must be 0 or between 2 and 36 (inclusive)
int(10)
int(10)
int(2)
int(36)
