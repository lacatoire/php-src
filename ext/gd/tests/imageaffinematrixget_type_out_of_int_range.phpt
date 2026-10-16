--TEST--
imageaffinematrixget(): a type outside the int range is invalid
--EXTENSIONS--
gd
--FILE--
<?php
foreach ([4294967296 + IMG_AFFINE_TRANSLATE, 4294967296 + IMG_AFFINE_SHEAR_HORIZONTAL, 4294967296 + 9, -4294967296] as $type) {
    try {
        var_dump(imageaffinematrixget($type, ['x' => 2, 'y' => 3]));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
var_dump(imageaffinematrixget(IMG_AFFINE_TRANSLATE, ['x' => 2, 'y' => 3]));
?>
--EXPECT--
ValueError: imageaffinematrixget(): Argument #1 ($type) must be a valid element type
ValueError: imageaffinematrixget(): Argument #1 ($type) must be a valid element type
ValueError: imageaffinematrixget(): Argument #1 ($type) must be a valid element type
ValueError: imageaffinematrixget(): Argument #1 ($type) must be a valid element type
array(6) {
  [0]=>
  float(1)
  [1]=>
  float(0)
  [2]=>
  float(0)
  [3]=>
  float(1)
  [4]=>
  float(2)
  [5]=>
  float(3)
}
