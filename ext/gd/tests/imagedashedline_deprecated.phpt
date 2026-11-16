--TEST--
imagedashedline() triggers deprecation notice
--EXTENSIONS--
gd
--SKIPIF--
<?php
if (!function_exists('imagedashedline')) die('skip imagedashedline not available');
?>
--FILE--
<?php
$im = imagecreate(100, 100);
imagedashedline($im, 0, 0, 99, 99, 0);
imagedestroy($im);
echo "done\n";
?>
--EXPECTF--
Deprecated: Function imagedashedline() is deprecated since 8.6, use imagesetstyle() together with imageline() instead in %s on line %d
done
