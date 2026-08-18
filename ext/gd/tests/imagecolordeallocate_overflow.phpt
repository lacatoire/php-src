--TEST--
imagecolordeallocate(): out-of-range $color that aliases into valid range must raise ValueError, and error message must name the correct upper bound
--EXTENSIONS--
gd
--SKIPIF--
<?php
if (PHP_INT_SIZE < 8) die("skip 64-bit only");
?>
--FILE--
<?php
$im = imagecreate(10, 10);
imagecolorallocate($im, 255, 255, 255);  // index 0
imagecolorallocate($im, 255, 0, 0);      // index 1
// imagecolorstotal == 2, valid indices 0 and 1

$bias = 2 ** 32;

// Values that alias onto valid indices must be rejected
try {
    imagecolordeallocate($im, 1 + $bias);
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}

try {
    imagecolordeallocate($im, 0 + $bias);
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}

// The error message must name the correct maximum valid index (count - 1)
try {
    imagecolordeallocate($im, 99);
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";   // "must be between 0 and 1", not "0 and 2"
}

// Valid index 1 still works
var_dump(imagecolordeallocate($im, 1));   // bool(true)

imagedestroy($im);
?>
--EXPECT--
imagecolordeallocate(): Argument #2 ($color) must be between 0 and 1
imagecolordeallocate(): Argument #2 ($color) must be between 0 and 1
imagecolordeallocate(): Argument #2 ($color) must be between 0 and 1
bool(true)
