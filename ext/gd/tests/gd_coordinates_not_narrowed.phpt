--TEST--
imagesetpixel(), imageline(), imagearc(): coordinates and angles outside the int range do not wrap around
--EXTENSIONS--
gd
--SKIPIF--
<?php
if (PHP_INT_SIZE != 8) die('skip 64-bit only');
?>
--FILE--
<?php
function count_lit($im) {
    $n = 0;
    for ($y = 0; $y < 10; $y++) {
        for ($x = 0; $x < 10; $x++) {
            if (imagecolorat($im, $x, $y) != 0xffffff) {
                $n++;
            }
        }
    }
    return $n;
}
function fresh() {
    $im = imagecreatetruecolor(10, 10);
    imagefill($im, 0, 0, 0xffffff);
    return $im;
}

$im = fresh();
imagesetpixel($im, 4294967296 + 5, 5, 0xff0000);
echo count_lit($im), "\n";
imagesetpixel($im, 5, -4294967296 + 5, 0xff0000);
echo count_lit($im), "\n";
imagesetpixel($im, 5, 5, 0xff0000);
echo count_lit($im), "\n";

$im = fresh();
imageline($im, 4294967296 + 1, 0, 4294967296 + 8, 0, 0x00ff00);
echo count_lit($im), "\n";

// A start angle of 2**32 + 90 is 2**32 % 360 + 90
$a = fresh();
$b = fresh();
imagearc($a, 5, 5, 8, 8, 4294967296 + 90, 270, 0xff0000);
imagearc($b, 5, 5, 8, 8, (4294967296 % 360) + 90, 270, 0xff0000);
var_dump(count_lit($a) === count_lit($b));
?>
--EXPECT--
0
0
1
0
bool(true)
