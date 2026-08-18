--TEST--
imagefill() / imagefilltoborder(): coordinates outside the int range are rejected, not truncated
--EXTENSIONS--
gd
--SKIPIF--
<?php
if (PHP_INT_SIZE < 8) die('skip 64-bit only: no zend_long can exceed an int here');
?>
--FILE--
<?php
function scene(): array
{
    $image = imagecreatetruecolor(9, 9);
    $white = imagecolorallocate($image, 255, 255, 255);
    $red = imagecolorallocate($image, 255, 0, 0);
    $green = imagecolorallocate($image, 0, 255, 0);
    imagefill($image, 0, 0, $white);
    imagerectangle($image, 2, 2, 6, 6, $red);

    return [$image, $red, $green];
}

function filled(GdImage $image, int $color): int
{
    $count = 0;
    for ($y = 0; $y < 9; $y++) {
        for ($x = 0; $x < 9; $x++) {
            if (imagecolorat($image, $x, $y) === $color) {
                $count++;
            }
        }
    }

    return $count;
}

$truncating = 2 ** 32 + 4;

[$image, $red, $green] = scene();

foreach ([[$truncating, 4], [4, $truncating], [PHP_INT_MIN, 0], [0, PHP_INT_MAX]] as [$x, $y]) {
    try {
        imagefilltoborder($image, $x, $y, $red, $green);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
    try {
        imagefill($image, $x, $y, $green);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}

/* a rejected fill must not have touched anything */
var_dump(filled($image, $green));

/* in range coordinates keep their old behaviour: libgd clamps to the edge, so
 * the fill starts outside the frame and covers the 56 exterior pixels */
[$image, $red, $green] = scene();
var_dump(imagefilltoborder($image, 100, 100, $red, $green));
var_dump(filled($image, $green));

/* and starting inside the frame still fills only the 3x3 interior */
[$image, $red, $green] = scene();
var_dump(imagefilltoborder($image, 4, 4, $red, $green));
var_dump(filled($image, $green));
?>
--EXPECT--
imagefilltoborder(): Argument #2 ($x) must be between -2147483648 and 2147483647
imagefill(): Argument #2 ($x) must be between -2147483648 and 2147483647
imagefilltoborder(): Argument #3 ($y) must be between -2147483648 and 2147483647
imagefill(): Argument #3 ($y) must be between -2147483648 and 2147483647
imagefilltoborder(): Argument #2 ($x) must be between -2147483648 and 2147483647
imagefill(): Argument #2 ($x) must be between -2147483648 and 2147483647
imagefilltoborder(): Argument #3 ($y) must be between -2147483648 and 2147483647
imagefill(): Argument #3 ($y) must be between -2147483648 and 2147483647
int(0)
bool(true)
int(56)
bool(true)
int(9)
