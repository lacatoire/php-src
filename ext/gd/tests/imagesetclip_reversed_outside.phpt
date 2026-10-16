--TEST--
imagesetclip(): reversed corners are swapped, a rectangle outside the image clips everything
--EXTENSIONS--
gd
--FILE--
<?php
function lit(array $clip): string {
    $w = 10;
    $h = 8;
    $im = imagecreatetruecolor($w, $h);
    imagesetclip($im, ...$clip);
    $r = imagegetclip($im);
    imagefilledrectangle($im, 0, 0, $w - 1, $h - 1, 0xffffff);
    imagesetclip($im, 0, 0, $w - 1, $h - 1);
    $n = 0;
    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            if (imagecolorat($im, $x, $y)) {
                $n++;
            }
        }
    }
    return json_encode($clip) . " => $n pixels, clip " . json_encode($r);
}

foreach ([[20, 20, 30, 30], [-9, -9, -1, -1], [-9, 2, -1, 4], [3, 20, 5, 30], [8, 6, 2, 1], [2, 1, 8, 6], [-5, -5, 20, 20], [0, 0, 9, 7]] as $clip) {
    echo lit($clip), "\n";
}
?>
--EXPECT--
[20,20,30,30] => 0 pixels, clip [0,0,-1,-1]
[-9,-9,-1,-1] => 0 pixels, clip [0,0,-1,-1]
[-9,2,-1,4] => 0 pixels, clip [0,0,-1,-1]
[3,20,5,30] => 0 pixels, clip [0,0,-1,-1]
[8,6,2,1] => 42 pixels, clip [2,1,8,6]
[2,1,8,6] => 42 pixels, clip [2,1,8,6]
[-5,-5,20,20] => 80 pixels, clip [0,0,9,7]
[0,0,9,7] => 80 pixels, clip [0,0,9,7]
