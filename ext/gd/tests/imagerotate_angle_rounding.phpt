--TEST--
imagerotate(): the angle is rounded to two decimals, so near right angles use the exact rotation
--EXTENSIONS--
gd
--FILE--
<?php
$im = imagecreatetruecolor(5, 3);
foreach ([89.999, 90.0, 90.001, 89.99, 90.01, 179.999, 270.001, -0.001, 0.004, -90.001] as $angle) {
    $r = imagerotate($im, $angle, 0);
    echo $angle, ': ', imagesx($r), 'x', imagesy($r), "\n";
}
?>
--EXPECT--
89.999: 3x5
90: 3x5
90.001: 3x5
89.99: 4x6
90.01: 4x6
179.999: 5x3
270.001: 3x5
-0.001: 5x3
0.004: 5x3
-90.001: 3x5
