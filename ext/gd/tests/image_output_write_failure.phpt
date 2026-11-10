--TEST--
imagegif(), imagepng(), imagebmp(), imagewbmp(), imagegd(): a write failure is reported
--EXTENSIONS--
gd
--SKIPIF--
<?php
if (!file_exists('/dev/full')) die('skip needs /dev/full');
?>
--FILE--
<?php
$im = imagecreatetruecolor(20, 20);
foreach (['imagegif', 'imagepng', 'imagebmp', 'imagewbmp'] as $f) {
    $h = fopen('/dev/full', 'w');
    echo $f, ': ';
    var_dump(@$f($im, $h));
}
echo "imagegd: ";
var_dump(@imagegd($im, '/dev/full'));
echo "imagegd2: ";
var_dump(@imagegd2($im, '/dev/full'));

// Writing to the output still works
ob_start();
$r = imagepng($im);
ob_end_clean();
var_dump($r);
?>
--EXPECT--
imagegif: bool(false)
imagepng: bool(false)
imagebmp: bool(false)
imagewbmp: bool(false)
imagegd: bool(false)
imagegd2: bool(false)
bool(true)
