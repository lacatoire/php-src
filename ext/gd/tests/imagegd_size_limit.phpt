--TEST--
imagegd(), imagegd2(): an image larger than 65535 is refused
--EXTENSIONS--
gd
--FILE--
<?php
$file = __DIR__ . '/imagegd_size_limit.out.tmp';
foreach (['imagegd', 'imagegd2'] as $f) {
    foreach ([[65536, 1], [1, 65536]] as [$w, $h]) {
        $im = imagecreate($w, $h);
        imagecolorallocate($im, 0, 0, 0);
        var_dump($f($im, $file));
        var_dump(file_exists($file));
    }
    $im = imagecreate(65535, 1);
    imagecolorallocate($im, 0, 0, 0);
    var_dump($f($im, $file));
    var_dump(file_exists($file));
    unlink($file);
}
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/imagegd_size_limit.out.tmp');
?>
--EXPECTF--
Warning: imagegd(): Image is too large for the GD format, the width and the height must not exceed 65535 in %s on line %d
bool(false)
bool(false)

Warning: imagegd(): Image is too large for the GD format, the width and the height must not exceed 65535 in %s on line %d
bool(false)
bool(false)
bool(true)
bool(true)

Warning: imagegd2(): Image is too large for the GD format, the width and the height must not exceed 65535 in %s on line %d
bool(false)
bool(false)

Warning: imagegd2(): Image is too large for the GD format, the width and the height must not exceed 65535 in %s on line %d
bool(false)
bool(false)
bool(true)
bool(true)
