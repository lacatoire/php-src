--TEST--
imagefilter(): pixelate in average mode costs the image size, not the block size
--EXTENSIONS--
gd
--FILE--
<?php
$i = imagecreatetruecolor(1, 1);
imagefill($i, 0, 0, 0x336699);
$start = microtime(true);
foreach ([100, 46341, 2147483647] as $b) {
    var_dump(imagefilter($i, IMG_FILTER_PIXELATE, $b, true));
}
var_dump(microtime(true) - $start < 1);
printf("%06x\n", imagecolorat($i, 0, 0));

// The average of a block that sticks out of the image only uses the pixels inside it
$i = imagecreatetruecolor(3, 1);
imagesetpixel($i, 0, 0, 0x000000);
imagesetpixel($i, 1, 0, 0x404040);
imagesetpixel($i, 2, 0, 0xc0c0c0);
imagefilter($i, IMG_FILTER_PIXELATE, 2, true);
printf("%06x %06x %06x\n", imagecolorat($i, 0, 0), imagecolorat($i, 1, 0), imagecolorat($i, 2, 0));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
336699
202020 202020 c0c0c0
