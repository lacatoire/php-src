--TEST--
imagefilltoborder() does not overflow the stack on a long corridor
--EXTENSIONS--
gd
--FILE--
<?php
// A border column every other x, with a gap alternating at the top and the bottom
$im = imagecreate(600, 600);
$bg = imagecolorallocate($im, 0, 0, 0);
$border = imagecolorallocate($im, 0, 0, 255);
$red = imagecolorallocate($im, 255, 0, 0);
for ($x = 1; $x < 600; $x += 2) {
    imageline($im, $x, 0, $x, 599, $border);
    imagesetpixel($im, $x, (($x >> 1) % 2) ? 0 : 599, $bg);
}
imagefilltoborder($im, 0, 0, $border, $red);

$filled = 0;
for ($y = 0; $y < 600; $y++) {
    for ($x = 0; $x < 600; $x++) {
        $filled += imagecolorat($im, $x, $y) === $red;
    }
}
var_dump($filled);
?>
--EXPECT--
int(180300)
