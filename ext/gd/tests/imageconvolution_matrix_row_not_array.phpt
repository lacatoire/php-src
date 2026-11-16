--TEST--
imageconvolution(): a matrix row that is not an array is rejected
--EXTENSIONS--
gd
--FILE--
<?php
$im = imagecreatetruecolor(5, 5);
imagefill($im, 0, 0, 0x336699);

foreach ([
    [1, 2, 3],
    ['a' => [1, 1, 1], 'b' => [1, 1, 1], 'c' => [1, 1, 1]],
    [[1, 1, 1], [1, 1, 1], 5],
] as $matrix) {
    try {
        var_dump(imageconvolution($im, $matrix, 9, 0));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
var_dump(imageconvolution($im, [[0, 0, 0], [0, 1, 0], [0, 0, 0]], 1, 0));
printf("%06x\n", imagecolorat($im, 2, 2));
?>
--EXPECT--
ValueError: imageconvolution(): Argument #2 ($matrix) must be a 3x3 array, matrix[0] must be an array
ValueError: imageconvolution(): Argument #2 ($matrix) must be a 3x3 array, matrix[0] must be an array
ValueError: imageconvolution(): Argument #2 ($matrix) must be a 3x3 array, matrix[2] must be an array
bool(true)
336699
