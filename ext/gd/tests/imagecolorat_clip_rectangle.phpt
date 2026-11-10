--TEST--
imagecolorat(): the clipping rectangle does not limit reading
--EXTENSIONS--
gd
--FILE--
<?php
foreach (['imagecreatetruecolor', 'imagecreate'] as $create) {
    $im = $create(10, 10);
    $c = imagecolorallocate($im, 0x33, 0x66, 0x99);
    imagefill($im, 0, 0, $c);
    $expected = imagecolorat($im, 0, 0);
    imagesetclip($im, 2, 2, 5, 5);
    var_dump(imagecolorat($im, 0, 0) === $expected, imagecolorat($im, 3, 3) === $expected, imagecolorat($im, 9, 9) === $expected);
    var_dump(imagecolorat($im, 10, 0));
    var_dump(imagecolorat($im, 0, -1));
}
?>
--EXPECTF--
bool(true)
bool(true)
bool(true)

Notice: imagecolorat(): 10,0 is out of bounds in %s on line %d
bool(false)

Notice: imagecolorat(): 0,-1 is out of bounds in %s on line %d
bool(false)
bool(true)
bool(true)
bool(true)

Notice: imagecolorat(): 10,0 is out of bounds in %s on line %d
bool(false)

Notice: imagecolorat(): 0,-1 is out of bounds in %s on line %d
bool(false)
