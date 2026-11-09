--TEST--
imagewbmp(), imagexbm(), imagebmp(): the clipping rectangle does not change the output
--EXTENSIONS--
gd
--FILE--
<?php
function capture(callable $f, $im): string {
    ob_start();
    $f($im, null);
    return ob_get_clean();
}

$im = imagecreatetruecolor(6, 3);
imagefill($im, 0, 0, 0xffffff);
imagesetpixel($im, 1, 1, 0);

foreach (['imagewbmp', 'imagexbm', 'imagebmp'] as $f) {
    $plain = capture($f, $im);
    imagesetclip($im, 1, 0, 4, 2);
    $clipped = capture($f, $im);
    var_dump($plain === $clipped, imagegetclip($im));
    imagesetclip($im, 0, 0, 5, 2);
}
?>
--EXPECT--
bool(true)
array(4) {
  [0]=>
  int(1)
  [1]=>
  int(0)
  [2]=>
  int(4)
  [3]=>
  int(2)
}
bool(true)
array(4) {
  [0]=>
  int(1)
  [1]=>
  int(0)
  [2]=>
  int(4)
  [3]=>
  int(2)
}
bool(true)
array(4) {
  [0]=>
  int(1)
  [1]=>
  int(0)
  [2]=>
  int(4)
  [3]=>
  int(2)
}
