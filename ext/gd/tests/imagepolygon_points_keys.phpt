--TEST--
imagepolygon(), imageopenpolygon(), imagefilledpolygon(): the points array needs the keys 0 to 2n-1
--EXTENSIONS--
gd
--FILE--
<?php
$im = imagecreatetruecolor(30, 30);
$named = [];
for ($i = 0; $i < 6; $i++) {
    $named['k' . $i] = $i * 3;
}
$holes = [0 => 1, 1 => 1, 2 => 20, 4 => 20, 5 => 10, 7 => 10];

foreach (['imagepolygon', 'imageopenpolygon', 'imagefilledpolygon'] as $f) {
    foreach ([$named, $holes] as $points) {
        try {
            $f($im, $points, 0xff0000);
        } catch (Throwable $e) {
            echo $e::class, ': ', $e->getMessage(), "\n";
        }
    }
    var_dump($f($im, [1, 1, 20, 1, 10, 20], 0xff0000));
}
?>
--EXPECT--
ValueError: imagepolygon(): Argument #2 ($points) must have the integer keys 0 to 5
ValueError: imagepolygon(): Argument #2 ($points) must have the integer keys 0 to 5
bool(true)
ValueError: imageopenpolygon(): Argument #2 ($points) must have the integer keys 0 to 5
ValueError: imageopenpolygon(): Argument #2 ($points) must have the integer keys 0 to 5
bool(true)
ValueError: imagefilledpolygon(): Argument #2 ($points) must have the integer keys 0 to 5
ValueError: imagefilledpolygon(): Argument #2 ($points) must have the integer keys 0 to 5
bool(true)
