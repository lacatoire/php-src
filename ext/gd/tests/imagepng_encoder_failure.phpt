--TEST--
imagepng(): a libpng failure is reported
--EXTENSIONS--
gd
--FILE--
<?php
$file = __DIR__ . '/imagepng_encoder_failure.out.tmp';
$im = imagecreatetruecolor(8, 8);
var_dump(@imagepng($im, $file, -1, 7));
var_dump(imagepng($im, $file, -1, PNG_ALL_FILTERS));
var_dump(imagepng($im, $file));
unlink($file);
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/imagepng_encoder_failure.out.tmp');
?>
--EXPECT--
bool(false)
bool(true)
bool(true)
