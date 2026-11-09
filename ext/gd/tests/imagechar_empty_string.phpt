--TEST--
imagechar(), imagecharup(): an empty string draws nothing
--EXTENSIONS--
gd
--FILE--
<?php
$file = __DIR__ . '/imagechar_empty_string.gdf';
// Two 2x2 glyphs, offset 0, glyph 0 fully set
file_put_contents($file, pack('V4', 2, 0, 2, 2) . "\x01\x01\x01\x01" . "\x00\x00\x00\x00");
$font = imageloadfont($file);
foreach (['imagechar', 'imagecharup', 'imagestring', 'imagestringup'] as $fn) {
    $im = imagecreatetruecolor(10, 10);
    var_dump($fn($im, $font, 3, 6, '', 0xffffff));
    $n = 0;
    for ($y = 0; $y < 10; $y++) {
        for ($x = 0; $x < 10; $x++) {
            if (imagecolorat($im, $x, $y) & 0xffffff) {
                $n++;
            }
        }
    }
    echo "$fn: $n pixels\n";
}
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/imagechar_empty_string.gdf');
?>
--EXPECT--
bool(true)
imagechar: 0 pixels
bool(true)
imagecharup: 0 pixels
bool(true)
imagestring: 0 pixels
bool(true)
imagestringup: 0 pixels
