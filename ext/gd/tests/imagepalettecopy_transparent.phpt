--TEST--
imagepalettecopy(): the transparent colour follows the pixels
--EXTENSIONS--
gd
--FILE--
<?php
$src = imagecreate(1, 1);
imagecolorallocate($src, 0, 0, 250);
imagecolorallocate($src, 250, 0, 0);

$d = imagecreate(2, 1);
imagecolorallocate($d, 255, 0, 0);
$blue = imagecolorallocate($d, 0, 0, 255);
imagecolortransparent($d, $blue);
imagesetpixel($d, 0, 0, $blue);
imagepalettecopy($d, $src);

$t = imagecolortransparent($d);
var_dump($t === imagecolorat($d, 0, 0));
print_r(imagecolorsforindex($d, $t));

// A transparent colour that no pixel uses is translated too
$d = imagecreate(2, 1);
imagecolorallocate($d, 255, 0, 0);
$blue = imagecolorallocate($d, 0, 0, 255);
imagecolortransparent($d, $blue);
imagepalettecopy($d, $src);
print_r(imagecolorsforindex($d, imagecolortransparent($d)));
?>
--EXPECT--
bool(true)
Array
(
    [red] => 0
    [green] => 0
    [blue] => 250
    [alpha] => 0
)
Array
(
    [red] => 0
    [green] => 0
    [blue] => 250
    [alpha] => 0
)
