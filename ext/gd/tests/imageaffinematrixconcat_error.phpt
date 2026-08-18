--TEST--
imageaffinematrixconcat(): argument errors are attributed to the right matrix
--EXTENSIONS--
gd
--FILE--
<?php
$valid = [1, 0, 0, 1, 0, 0];

foreach ([[[1, 2, 3], $valid], [$valid, [1, 2, 3]]] as [$m1, $m2]) {
    try {
        imageaffinematrixconcat($m1, $m2);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}

$invalid = [1, 0, 0, 1, 0, new stdClass()];

foreach ([[$invalid, $valid], [$valid, $invalid]] as [$m1, $m2]) {
    try {
        imageaffinematrixconcat($m1, $m2);
    } catch (TypeError $e) {
        echo $e->getMessage(), "\n";
    }
}
?>
--EXPECT--
imageaffinematrixconcat(): Argument #1 ($matrix1) must have 6 elements
imageaffinematrixconcat(): Argument #2 ($matrix2) must have 6 elements
imageaffinematrixconcat(): Argument #1 ($matrix1) contains invalid type for element 5
imageaffinematrixconcat(): Argument #2 ($matrix2) contains invalid type for element 5
