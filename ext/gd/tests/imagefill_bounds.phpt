--TEST--
imagefill(): the start point and the scans stay inside the image
--EXTENSIONS--
gd
--FILE--
<?php
function count_red($im) {
    $n = 0;
    for ($y = 0; $y < imagesy($im); $y++) {
        for ($x = 0; $x < imagesx($im); $x++) {
            if (imagecolorat($im, $x, $y) == 0xff0000) {
                $n++;
            }
        }
    }
    return $n;
}

// A start point outside the image fills nothing
$im = imagecreatetruecolor(10, 10);
imagefill($im, 10, 5, 0xff0000);
var_dump(count_red($im));
imagefill($im, 5, 10, 0xff0000);
var_dump(count_red($im));

// An enclosed black cell is not reached
$im = imagecreatetruecolor(4, 3);
imagefilledrectangle($im, 2, 1, 3, 2, 0x0000ff);
imagesetpixel($im, 3, 2, 0);
imagefill($im, 1, 1, 0xff0000);
for ($y = 0; $y < 3; $y++) {
    for ($x = 0; $x < 4; $x++) {
        echo match (imagecolorat($im, $x, $y)) { 0xff0000 => 'R', 0x0000ff => 'B', default => '.' };
    }
    echo "\n";
}
?>
--EXPECT--
int(0)
int(0)
RRRR
RRBB
RRB.
