--TEST--
imagefilltoborder(): no deep recursion on a long corridor, same region as a plain flood fill
--EXTENSIONS--
gd
--FILE--
<?php
// Vertical serpentine: a border column every other x, one gap alternating top and bottom
$w = $h = 600;
$im = imagecreate($w, $h);
$bg = imagecolorallocate($im, 0, 0, 0);
$bd = imagecolorallocate($im, 0, 0, 255);
$red = imagecolorallocate($im, 255, 0, 0);
for ($x = 1; $x < $w; $x += 2) {
    imageline($im, $x, 0, $x, $h - 1, $bd);
    imagesetpixel($im, $x, (($x >> 1) % 2) ? 0 : $h - 1, $bg);
}
imagefilltoborder($im, 0, 0, $bd, $red);
$n = 0;
for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        if (imagecolorat($im, $x, $y) == $red) {
            $n++;
        }
    }
}
var_dump($n);

// Compare with a plain 4-connected flood fill on random images
mt_srand(42);
$mismatch = 0;
for ($t = 0; $t < 40; $t++) {
    $size = 12;
    $im = imagecreate($size, $size);
    $bg = imagecolorallocate($im, 0, 0, 0);
    $bd = imagecolorallocate($im, 0, 0, 255);
    $red = imagecolorallocate($im, 255, 0, 0);
    $cells = [];
    for ($y = 0; $y < $size; $y++) {
        for ($x = 0; $x < $size; $x++) {
            $cells[$y][$x] = mt_rand(0, 99) < 35 ? 1 : 0;
            imagesetpixel($im, $x, $y, $cells[$y][$x] ? $bd : $bg);
        }
    }
    $sx = mt_rand(0, $size - 1);
    $sy = mt_rand(0, $size - 1);
    $expected = [];
    if (!$cells[$sy][$sx]) {
        $stack = [[$sx, $sy]];
        while ($stack) {
            [$x, $y] = array_pop($stack);
            if ($x < 0 || $y < 0 || $x >= $size || $y >= $size || $cells[$y][$x] || isset($expected["$x,$y"])) {
                continue;
            }
            $expected["$x,$y"] = true;
            array_push($stack, [$x + 1, $y], [$x - 1, $y], [$x, $y + 1], [$x, $y - 1]);
        }
    }
    imagefilltoborder($im, $sx, $sy, $bd, $red);
    for ($y = 0; $y < $size; $y++) {
        for ($x = 0; $x < $size; $x++) {
            if ((imagecolorat($im, $x, $y) == $red) !== isset($expected["$x,$y"])) {
                $mismatch++;
            }
        }
    }
}
var_dump($mismatch);
?>
--EXPECT--
int(180300)
int(0)
