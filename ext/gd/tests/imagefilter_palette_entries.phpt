--TEST--
imagefilter(): a palette image does not get one new colour per pixel
--EXTENSIONS--
gd
--FILE--
<?php
$filters = [
    'negate' => [IMG_FILTER_NEGATE],
    'grayscale' => [IMG_FILTER_GRAYSCALE],
    'brightness' => [IMG_FILTER_BRIGHTNESS, 30],
    'contrast' => [IMG_FILTER_CONTRAST, 30],
    'colorize' => [IMG_FILTER_COLORIZE, 10, 20, 30],
];
foreach ($filters as $name => $args) {
    $q = imagecreate(50, 50);
    imagecolorallocate($q, 10, 20, 30);
    imagecolorallocate($q, 200, 100, 50);
    imagefill($q, 0, 0, 1);
    imagefilter($q, ...$args);
    echo $name, ': ', imagecolorstotal($q), ' ', var_export(imagecolorallocate($q, 1, 2, 3) !== false, true), "\n";
}
?>
--EXPECT--
negate: 3 true
grayscale: 3 true
brightness: 3 true
contrast: 3 true
colorize: 3 true
