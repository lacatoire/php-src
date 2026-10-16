--TEST--
imagepalettetotruecolor(): imagecolorstotal() is 0 once the image is truecolor
--EXTENSIONS--
gd
--FILE--
<?php
$p = imagecreate(3, 3);
imagecolorallocate($p, 1, 2, 3);
imagecolorallocate($p, 4, 5, 6);
imagecolorallocate($p, 7, 8, 9);
var_dump(imageistruecolor($p), imagecolorstotal($p));
var_dump(imagepalettetotruecolor($p));
var_dump(imageistruecolor($p), imagecolorstotal($p));
var_dump(imagecolorstotal(imagecreatetruecolor(2, 2)));
?>
--EXPECT--
bool(false)
int(3)
bool(true)
bool(true)
int(0)
int(0)
