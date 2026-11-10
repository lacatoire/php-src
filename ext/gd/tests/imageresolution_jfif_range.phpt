--TEST--
imageresolution(): a resolution above 65535 is refused
--EXTENSIONS--
gd
--FILE--
<?php
$im = imagecreate(10, 10);
foreach ([65535, 65536, 70000, 4294967295] as $res) {
    try {
        var_dump(imageresolution($im, $res));
        var_dump(imageresolution($im));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
try {
    imageresolution($im, 72, 70000);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
?>
--EXPECT--
bool(true)
array(2) {
  [0]=>
  int(65535)
  [1]=>
  int(65535)
}
ValueError: imageresolution(): Argument #2 ($resolution_x) must be between 0 and 65535
ValueError: imageresolution(): Argument #2 ($resolution_x) must be between 0 and 65535
ValueError: imageresolution(): Argument #2 ($resolution_x) must be between 0 and 65535
ValueError: imageresolution(): Argument #3 ($resolution_y) must be between 0 and 65535
