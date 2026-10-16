--TEST--
imagefilter(): large brightness, contrast and colorize arguments do not wrap
--EXTENSIONS--
gd
--SKIPIF--
<?php
if (PHP_INT_SIZE != 8) die('skip 64-bit only');
?>
--FILE--
<?php
function px(int $filter, ...$args): string {
    $i = imagecreatetruecolor(2, 2);
    imagefill($i, 0, 0, 0x808080);
    $r = imagefilter($i, $filter, ...$args);
    return var_export($r, true) . ' ' . sprintf('%08x', imagecolorat($i, 0, 0));
}

foreach ([0, 255, 256, 4294967296, 4294967296 + 10, -4294967296] as $v) {
    echo "brightness $v: ", px(IMG_FILTER_BRIGHTNESS, $v), "\n";
}
foreach ([0, 255, 2147483647, 2147483648, PHP_INT_MAX, -2147483648, PHP_INT_MIN] as $v) {
    echo "colorize $v: ", px(IMG_FILTER_COLORIZE, $v, 0, 0), "\n";
}
echo "contrast: ", px(IMG_FILTER_CONTRAST, PHP_INT_MAX), "\n";
?>
--EXPECT--
brightness 0: true 00808080
brightness 255: true 00ffffff
brightness 256: false 00808080
brightness 4294967296: false 00808080
brightness 4294967306: false 00808080
brightness -4294967296: false 00808080
colorize 0: true 00808080
colorize 255: true 00ff8080
colorize 2147483647: true 00ff8080
colorize 2147483648: true 00ff8080
colorize 9223372036854775807: true 00ff8080
colorize -2147483648: true 00008080
colorize -9223372036854775808: true 00008080
contrast: true 00ffffff
