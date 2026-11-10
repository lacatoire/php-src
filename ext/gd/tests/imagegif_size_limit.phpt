--TEST--
imagegif(): an image larger than 65535 is refused
--EXTENSIONS--
gd
--FILE--
<?php
$file = __DIR__ . '/imagegif_size_limit.out.tmp';
foreach ([[65536, 1], [1, 65536]] as [$w, $h]) {
    $im = imagecreate($w, $h);
    imagecolorallocate($im, 0, 0, 0);
    var_dump(imagegif($im, $file));
    var_dump(file_exists($file));
}
$im = imagecreate(65535, 1);
imagecolorallocate($im, 0, 0, 0);
var_dump(imagegif($im, $file));
var_dump(file_exists($file));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/imagegif_size_limit.out.tmp');
?>
--EXPECTF--
Warning: imagegif(): Image is too large for the GIF format, the width and the height must not exceed 65535 in %s on line %d
bool(false)
bool(false)

Warning: imagegif(): Image is too large for the GIF format, the width and the height must not exceed 65535 in %s on line %d
bool(false)
bool(false)
bool(true)
bool(true)
