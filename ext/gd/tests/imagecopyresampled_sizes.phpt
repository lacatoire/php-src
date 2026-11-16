--TEST--
imagecopyresampled(): the sizes must be greater than 0, like imagecopyresized()
--EXTENSIONS--
gd
--FILE--
<?php
$s = imagecreatetruecolor(4, 4);
imagefill($s, 0, 0, 0x336699);

foreach ([[10, 10, 0, 3], [10, 10, 3, 0], [10, 10, -3, 3], [0, 10, 3, 3], [10, -1, 3, 3]] as [$dw, $dh, $sw, $sh]) {
    foreach (['imagecopyresized', 'imagecopyresampled'] as $f) {
        $d = imagecreatetruecolor(10, 10);
        imagefill($d, 0, 0, 0xffffff);
        try {
            $f($d, $s, 0, 0, 0, 0, $dw, $dh, $sw, $sh);
            echo "$f: ok\n";
        } catch (Throwable $e) {
            echo $e::class, ': ', $e->getMessage(), "\n";
        }
        printf("%06x\n", imagecolorat($d, 9, 9));
    }
}
$d = imagecreatetruecolor(10, 10);
var_dump(imagecopyresampled($d, $s, 0, 0, 0, 0, 10, 10, 4, 4));
?>
--EXPECT--
ValueError: imagecopyresized(): Argument #9 ($src_width) must be greater than 0
ffffff
ValueError: imagecopyresampled(): Argument #9 ($src_width) must be greater than 0
ffffff
ValueError: imagecopyresized(): Argument #10 ($src_height) must be greater than 0
ffffff
ValueError: imagecopyresampled(): Argument #10 ($src_height) must be greater than 0
ffffff
ValueError: imagecopyresized(): Argument #9 ($src_width) must be greater than 0
ffffff
ValueError: imagecopyresampled(): Argument #9 ($src_width) must be greater than 0
ffffff
ValueError: imagecopyresized(): Argument #7 ($dst_width) must be greater than 0
ffffff
ValueError: imagecopyresampled(): Argument #7 ($dst_width) must be greater than 0
ffffff
ValueError: imagecopyresized(): Argument #8 ($dst_height) must be greater than 0
ffffff
ValueError: imagecopyresampled(): Argument #8 ($dst_height) must be greater than 0
ffffff
bool(true)
