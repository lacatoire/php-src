--TEST--
imagescale(): the dimension derived from the aspect ratio is at least 1
--EXTENSIONS--
gd
--FILE--
<?php
function size($w, $h, $nw, $nh = -1) {
    $img = imagecreatetruecolor($w, $h);
    $s = imagescale($img, $nw, $nh);
    return imagesx($s) . 'x' . imagesy($s);
}

echo size(100, 1, 10), "\n";
echo size(100, 1, 99), "\n";
echo size(100, 50, 1), "\n";
echo size(1, 100, -1, 10), "\n";
echo size(7, 3, 4), "\n";
?>
--EXPECT--
10x1
99x1
1x1
1x10
4x1
