--TEST--
imagesetinterpolation(): out of range methods are rejected without truncation
--EXTENSIONS--
gd
--FILE--
<?php
$image = imagecreatetruecolor(4, 4);

imagesetinterpolation($image, IMG_BSPLINE);
$before = imagegetinterpolation($image);

foreach ([PHP_INT_MIN, PHP_INT_MAX, -2, 100] as $method) {
    var_dump(imagesetinterpolation($image, $method));
}

/* a rejected method must leave the image untouched */
var_dump(imagegetinterpolation($image) === $before);

/* the legacy -1 still means the default, and named methods still apply */
var_dump(imagesetinterpolation($image, -1));
var_dump(imagegetinterpolation($image) === IMG_BILINEAR_FIXED);
var_dump(imagesetinterpolation($image, IMG_NEAREST_NEIGHBOUR));
var_dump(imagegetinterpolation($image) === IMG_NEAREST_NEIGHBOUR);
?>
--EXPECT--
bool(false)
bool(false)
bool(false)
bool(false)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
