--TEST--
imagesetthickness(): a negative or oversized thickness is rejected
--EXTENSIONS--
gd
--FILE--
<?php
$im = imagecreatetruecolor(40, 40);
foreach ([-1, -5, 2**31, 2**32 + 3, PHP_INT_MAX, PHP_INT_MIN, 0, 1, 3] as $t) {
    try {
        var_dump(imagesetthickness($im, $t));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
?>
--EXPECT--
ValueError: imagesetthickness(): Argument #2 ($thickness) must be between 0 and 2147483647
ValueError: imagesetthickness(): Argument #2 ($thickness) must be between 0 and 2147483647
ValueError: imagesetthickness(): Argument #2 ($thickness) must be between 0 and 2147483647
ValueError: imagesetthickness(): Argument #2 ($thickness) must be between 0 and 2147483647
ValueError: imagesetthickness(): Argument #2 ($thickness) must be between 0 and 2147483647
ValueError: imagesetthickness(): Argument #2 ($thickness) must be between 0 and 2147483647
bool(true)
bool(true)
bool(true)
