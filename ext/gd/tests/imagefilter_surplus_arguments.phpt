--TEST--
imagefilter(): the filters taking no argument reject a surplus one
--EXTENSIONS--
gd
--FILE--
<?php
$filters = [
    'IMG_FILTER_NEGATE' => IMG_FILTER_NEGATE,
    'IMG_FILTER_GRAYSCALE' => IMG_FILTER_GRAYSCALE,
    'IMG_FILTER_EDGEDETECT' => IMG_FILTER_EDGEDETECT,
    'IMG_FILTER_EMBOSS' => IMG_FILTER_EMBOSS,
    'IMG_FILTER_GAUSSIAN_BLUR' => IMG_FILTER_GAUSSIAN_BLUR,
    'IMG_FILTER_SELECTIVE_BLUR' => IMG_FILTER_SELECTIVE_BLUR,
    'IMG_FILTER_MEAN_REMOVAL' => IMG_FILTER_MEAN_REMOVAL,
];

foreach ($filters as $name => $filter) {
    $image = imagecreatetruecolor(8, 8);

    /* the documented call keeps working */
    var_dump(imagefilter($image, $filter));

    try {
        imagefilter($image, $filter, 5);
    } catch (ArgumentCountError $e) {
        printf("%-26s %s\n", $name, $e->getMessage());
    }
}
?>
--EXPECT--
bool(true)
IMG_FILTER_NEGATE          imagefilter() expects exactly 2 arguments, 3 given
bool(true)
IMG_FILTER_GRAYSCALE       imagefilter() expects exactly 2 arguments, 3 given
bool(true)
IMG_FILTER_EDGEDETECT      imagefilter() expects exactly 2 arguments, 3 given
bool(true)
IMG_FILTER_EMBOSS          imagefilter() expects exactly 2 arguments, 3 given
bool(true)
IMG_FILTER_GAUSSIAN_BLUR   imagefilter() expects exactly 2 arguments, 3 given
bool(true)
IMG_FILTER_SELECTIVE_BLUR  imagefilter() expects exactly 2 arguments, 3 given
bool(true)
IMG_FILTER_MEAN_REMOVAL    imagefilter() expects exactly 2 arguments, 3 given
