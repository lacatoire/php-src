--TEST--
imageaffine(), imageaffinematrixconcat(): a matrix needs the keys 0 to 5
--EXTENSIONS--
gd
--FILE--
<?php
$im = imagecreatetruecolor(20, 20);
$id = [1, 0, 0, 1, 0, 0];
$named = ['a' => 1, 'b' => 0, 'c' => 0, 'd' => 1, 'e' => 0, 'f' => 0];
$partial = [1, 0, 0, 1, 'x' => 0, 'y' => 0];

foreach ([
    fn() => imageaffine($im, $named),
    fn() => imageaffine($im, $partial),
    fn() => imageaffinematrixconcat($named, $id),
    fn() => imageaffinematrixconcat($id, $partial),
] as $f) {
    try {
        $f();
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
var_dump(imageaffinematrixconcat($id, $id));
?>
--EXPECT--
ValueError: imageaffine(): Argument #2 ($affine) must have the keys 0 to 5
ValueError: imageaffine(): Argument #2 ($affine) must have the keys 0 to 5
ValueError: imageaffinematrixconcat(): Argument #1 ($matrix1) must have the keys 0 to 5
ValueError: imageaffinematrixconcat(): Argument #2 ($matrix2) must have the keys 0 to 5
array(6) {
  [0]=>
  float(1)
  [1]=>
  float(0)
  [2]=>
  float(0)
  [3]=>
  float(1)
  [4]=>
  float(0)
  [5]=>
  float(0)
}
