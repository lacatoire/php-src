--TEST--
GIF: interlaced images of 2 to 4 rows are decoded and encoded correctly
--EXTENSIONS--
gd
--FILE--
<?php
// Minimal GIF writer: 4 colours, a Clear code before every pixel so that no table is built.
function make_gif(int $height, bool $interlaced): string {
    $order = [];
    if ($interlaced) {
        foreach ([[0, 8], [4, 8], [2, 4], [1, 2]] as [$start, $step]) {
            for ($y = $start; $y < $height; $y += $step) {
                $order[] = $y;
            }
        }
    } else {
        $order = range(0, $height - 1);
    }
    $bits = '';
    $emit = function (int $code) use (&$bits) {
        $bits = $bits . strrev(str_pad(decbin($code), 3, '0', STR_PAD_LEFT));
    };
    foreach ($order as $y) {
        $emit(4);
        $emit($y % 4);
    }
    $emit(4);
    $emit(5);
    $bytes = '';
    foreach (str_split($bits, 8) as $chunk) {
        $bytes .= chr(bindec(strrev(str_pad($chunk, 8, '0', STR_PAD_RIGHT))));
    }
    $palette = "\x00\x00\x00" . "\xff\x00\x00" . "\x00\xff\x00" . "\x00\x00\xff";
    return "GIF89a" . pack('vvCCC', 1, $height, 0xf1, 0, 0) . $palette
        . "," . pack('vvvvC', 0, 0, 1, $height, $interlaced ? 0x40 : 0)
        . chr(2) . chr(strlen($bytes)) . $bytes . "\x00;";
}

function rows($im): string {
    $r = '';
    for ($y = 0; $y < imagesy($im); $y++) {
        $r .= imagecolorat($im, 0, $y);
    }
    return $r;
}

for ($h = 1; $h <= 9; $h++) {
    $expected = '';
    for ($y = 0; $y < $h; $y++) {
        $expected .= $y % 4;
    }
    $plain = rows(imagecreatefromstring(make_gif($h, false)));
    $inter = rows(imagecreatefromstring(make_gif($h, true)));

    // Encoder: write the image interlaced, read it back
    $im = imagecreate(1, $h);
    for ($c = 0; $c < 4; $c++) {
        imagecolorallocate($im, $c * 80, 0, 0);
    }
    for ($y = 0; $y < $h; $y++) {
        imagesetpixel($im, 0, $y, $y % 4);
    }
    imageinterlace($im, true);
    ob_start();
    imagegif($im);
    $round = rows(imagecreatefromstring(ob_get_clean()));

    echo $h, ': ', $plain === $expected ? 'plain ok' : 'plain BAD', ', ',
        $inter === $expected ? 'decoder ok' : "decoder BAD ($inter)", ', ',
        $round === $expected ? 'encoder ok' : "encoder BAD ($round)", "\n";
}
?>
--EXPECT--
1: plain ok, decoder ok, encoder ok
2: plain ok, decoder ok, encoder ok
3: plain ok, decoder ok, encoder ok
4: plain ok, decoder ok, encoder ok
5: plain ok, decoder ok, encoder ok
6: plain ok, decoder ok, encoder ok
7: plain ok, decoder ok, encoder ok
8: plain ok, decoder ok, encoder ok
9: plain ok, decoder ok, encoder ok
