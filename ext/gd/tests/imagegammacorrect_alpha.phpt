--TEST--
imagegammacorrect(): semi-transparent pixels keep their alpha
--EXTENSIONS--
gd
--FILE--
<?php
foreach ([32, 64, 100, 0, 127] as $alpha) {
    $im = imagecreatetruecolor(1, 1);
    imagealphablending($im, false);
    imagesetpixel($im, 0, 0, ($alpha << 24) | 0x808080);
    imagealphablending($im, true);
    imagegammacorrect($im, 1.0, 2.2);
    printf("%08x\n", imagecolorat($im, 0, 0));
}

// The clipping rectangle does not limit the correction
$im = imagecreatetruecolor(2, 1);
imagefill($im, 0, 0, 0x808080);
imagesetclip($im, 0, 0, 0, 0);
imagegammacorrect($im, 1.0, 2.2);
imagesetclip($im, 0, 0, 1, 0);
printf("%06x %06x\n", imagecolorat($im, 0, 0), imagecolorat($im, 1, 0));
?>
--EXPECT--
20bababa
40bababa
64bababa
00bababa
7fbababa
bababa bababa
