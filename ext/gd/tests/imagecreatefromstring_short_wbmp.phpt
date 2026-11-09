--TEST--
imagecreatefromstring(): a WBMP shorter than 12 bytes is decoded
--EXTENSIONS--
gd
--FILE--
<?php
foreach ([[1, 1], [4, 4], [7, 7], [16, 2], [8, 8]] as [$w, $h]) {
    $im = imagecreatetruecolor($w, $h);
    imagefill($im, 0, 0, 0xffffff);
    imagesetpixel($im, 0, 0, 0);
    ob_start();
    imagewbmp($im);
    $data = ob_get_clean();
    $loaded = imagecreatefromstring($data);
    echo strlen($data), ' bytes: ', $loaded ? imagesx($loaded) . 'x' . imagesy($loaded) : 'false', "\n";
}
var_dump(@imagecreatefromstring("\x01\x02"));
var_dump(@imagecreatefromstring(''));
?>
--EXPECT--
5 bytes: 1x1
8 bytes: 4x4
11 bytes: 7x7
8 bytes: 16x2
12 bytes: 8x8
bool(false)
bool(false)
