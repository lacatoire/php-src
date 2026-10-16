--TEST--
imagefilledellipse(): the height is validated like the width
--EXTENSIONS--
gd
--FILE--
<?php
$im = imagecreatetruecolor(20, 20);
foreach ([[-1, 5], [5, -1], [5, 2**31], [5, PHP_INT_MAX], [0, 0], [5, 5]] as [$w, $h]) {
    try {
        var_dump(imagefilledellipse($im, 10, 10, $w, $h, 0xff0000));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
?>
--EXPECT--
ValueError: imagefilledellipse(): Argument #4 ($width) must be between 0 and 2147483647
ValueError: imagefilledellipse(): Argument #5 ($height) must be between 0 and 2147483647
ValueError: imagefilledellipse(): Argument #5 ($height) must be between 0 and 2147483647
ValueError: imagefilledellipse(): Argument #5 ($height) must be between 0 and 2147483647
bool(true)
bool(true)
