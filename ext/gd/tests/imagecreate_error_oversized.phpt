--TEST--
imagecreate()/imagecreatetruecolor(): oversized dimensions produce a correct error message
--EXTENSIONS--
gd
--FILE--
<?php
foreach ([PHP_INT_MAX, 2147483647, 4294967296] as $v) {
    try { imagecreate($v, 1); } catch (ValueError $e) { echo $e->getMessage(), "\n"; }
    try { imagecreate(1, $v); } catch (ValueError $e) { echo $e->getMessage(), "\n"; }
    try { imagecreatetruecolor($v, 1); } catch (ValueError $e) { echo $e->getMessage(), "\n"; }
    try { imagecreatetruecolor(1, $v); } catch (ValueError $e) { echo $e->getMessage(), "\n"; }
}
?>
--EXPECTF--
imagecreate(): Argument #1 ($width) must be greater than 0 and less than %d
imagecreate(): Argument #2 ($height) must be greater than 0 and less than %d
imagecreatetruecolor(): Argument #1 ($width) must be greater than 0 and less than %d
imagecreatetruecolor(): Argument #2 ($height) must be greater than 0 and less than %d
imagecreate(): Argument #1 ($width) must be greater than 0 and less than %d
imagecreate(): Argument #2 ($height) must be greater than 0 and less than %d
imagecreatetruecolor(): Argument #1 ($width) must be greater than 0 and less than %d
imagecreatetruecolor(): Argument #2 ($height) must be greater than 0 and less than %d
imagecreate(): Argument #1 ($width) must be greater than 0 and less than %d
imagecreate(): Argument #2 ($height) must be greater than 0 and less than %d
imagecreatetruecolor(): Argument #1 ($width) must be greater than 0 and less than %d
imagecreatetruecolor(): Argument #2 ($height) must be greater than 0 and less than %d
