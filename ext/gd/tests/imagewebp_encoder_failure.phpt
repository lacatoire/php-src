--TEST--
imagewebp() returns false when the encoder fails
--EXTENSIONS--
gd
--SKIPIF--
<?php
if (!function_exists("imagewebp")) die("skip WebP Support is not enabled");
?>
--FILE--
<?php
$file = __DIR__ . '/imagewebp_encoder_failure.webp';

$palette = imagecreate(4, 4);
imagecolorallocate($palette, 1, 2, 3);
var_dump(imagewebp($palette, $file));

var_dump(imagewebp(imagecreatetruecolor(16383, 1), $file));
// above the WebP size limit
var_dump(imagewebp(imagecreatetruecolor(16384, 1), $file));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/imagewebp_encoder_failure.webp');
?>
--EXPECTF--
Warning: imagewebp(): Palette image not supported by webp in %s on line %d
bool(false)
bool(true)

Warning: imagewebp(): gd-webp encoding failed in %s on line %d
bool(false)
