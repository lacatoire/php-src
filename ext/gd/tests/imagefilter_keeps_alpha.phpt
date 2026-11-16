--TEST--
imagefilter(): semi-transparent pixels keep their alpha
--EXTENSIONS--
gd
--FILE--
<?php
$filters = [
    'negate' => [IMG_FILTER_NEGATE],
    'grayscale' => [IMG_FILTER_GRAYSCALE],
    'brightness' => [IMG_FILTER_BRIGHTNESS, 50],
    'contrast' => [IMG_FILTER_CONTRAST, 30],
    'colorize' => [IMG_FILTER_COLORIZE, 10, 20, 30],
    'edgedetect' => [IMG_FILTER_EDGEDETECT],
    'gaussian' => [IMG_FILTER_GAUSSIAN_BLUR],
    'smooth' => [IMG_FILTER_SMOOTH, 5],
    'emboss' => [IMG_FILTER_EMBOSS],
    'mean' => [IMG_FILTER_MEAN_REMOVAL],
    'selective' => [IMG_FILTER_SELECTIVE_BLUR],
];
foreach ($filters as $name => $args) {
    $im = imagecreatetruecolor(1, 1);
    imagealphablending($im, false);
    imagesetpixel($im, 0, 0, 0x40808080);
    imagealphablending($im, true);
    imagefilter($im, ...$args);
    printf("%-10s alpha %02x\n", $name, (imagecolorat($im, 0, 0) >> 24) & 0x7f);
}
// The blending mode is restored
$im = imagecreatetruecolor(1, 1);
imagefilter($im, IMG_FILTER_NEGATE);
imagesetpixel($im, 0, 0, 0x7f808080);
printf("%08x\n", imagecolorat($im, 0, 0));
?>
--EXPECT--
negate     alpha 40
grayscale  alpha 40
brightness alpha 40
contrast   alpha 40
colorize   alpha 40
edgedetect alpha 40
gaussian   alpha 40
smooth     alpha 40
emboss     alpha 40
mean       alpha 40
selective  alpha 40
00ffffff
