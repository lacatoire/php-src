--TEST--
imagejpeg() returns false when libjpeg fails
--EXTENSIONS--
gd
--SKIPIF--
<?php
if (!function_exists("imagejpeg")) die("skip JPEG Support is not enabled");
?>
--FILE--
<?php
$file = __DIR__ . '/imagejpeg_encoder_failure.jpg';

// libjpeg refuses images larger than 65500 pixels on a side
var_dump(imagejpeg(imagecreatetruecolor(65500, 1), $file));
var_dump(imagejpeg(imagecreatetruecolor(65501, 1), $file));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/imagejpeg_encoder_failure.jpg');
?>
--EXPECTF--
bool(true)

Warning: imagejpeg(): gd-jpeg: JPEG library reports unrecoverable error: Maximum supported image dimension is 65500 pixels in %s on line %d
bool(false)
