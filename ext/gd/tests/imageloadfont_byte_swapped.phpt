--TEST--
imageloadfont(): a font written with the other byte order is loaded, offset included
--EXTENSIONS--
gd
--FILE--
<?php
$dir = __DIR__ . '/imageloadfont_byte_swapped_dir';
mkdir($dir);
$body = str_repeat("\x01", 95 * 2 * 2);
file_put_contents("$dir/le.gdf", pack('V4', 95, 32, 2, 2) . $body);
file_put_contents("$dir/be.gdf", pack('N4', 95, 32, 2, 2) . $body);
file_put_contents("$dir/bad.gdf", pack('N4', 95, 32, 2, 2) . "short");
file_put_contents("$dir/huge.gdf", pack('N4', 0x7fffffff, 32, 0x7fff, 0x7fff) . $body);

foreach (['le', 'be', 'bad', 'huge'] as $name) {
    $font = @imageloadfont("$dir/$name.gdf");
    if (!$font) {
        echo "$name: false\n";
        continue;
    }
    $im = imagecreatetruecolor(10, 10);
    imagechar($im, $font, 2, 2, 'A', 0xffffff);
    $n = 0;
    for ($y = 0; $y < 10; $y++) {
        for ($x = 0; $x < 10; $x++) {
            if (imagecolorat($im, $x, $y)) {
                $n++;
            }
        }
    }
    echo "$name: $n pixels\n";
}
?>
--CLEAN--
<?php
$dir = __DIR__ . '/imageloadfont_byte_swapped_dir';
foreach (['le', 'be', 'bad', 'huge'] as $name) {
    @unlink("$dir/$name.gdf");
}
@rmdir($dir);
?>
--EXPECT--
le: 4 pixels
be: 4 pixels
bad: false
huge: false
