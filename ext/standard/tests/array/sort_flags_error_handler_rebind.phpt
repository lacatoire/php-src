--TEST--
sort() and array_splice() when an error handler rebinds the by-reference variable during argument parsing
--FILE--
<?php

set_error_handler(function () use (&$array) {
    $array = 42;
    return true;
});

foreach (['sort', 'rsort', 'asort', 'arsort', 'ksort', 'krsort'] as $function) {
    $array = [2, 1];
    try {
        $function($array, 1.5);
    } catch (TypeError $e) {
        echo $e->getMessage(), "\n";
    }
    var_dump($array);
}

$array = [3, 1, 2];
try {
    array_splice($array, 1.5);
} catch (TypeError $e) {
    echo $e->getMessage(), "\n";
}
var_dump($array);

$shared = [3, 2, 1];
set_error_handler(function () use (&$array, $shared) {
    $array = $shared;
    return true;
});
$array = [2, 1];
sort($array, 1.5);
var_dump($array, $shared);
?>
--EXPECT--
sort(): Argument #1 ($array) must be of type array, int given
int(42)
rsort(): Argument #1 ($array) must be of type array, int given
int(42)
asort(): Argument #1 ($array) must be of type array, int given
int(42)
arsort(): Argument #1 ($array) must be of type array, int given
int(42)
ksort(): Argument #1 ($array) must be of type array, int given
int(42)
krsort(): Argument #1 ($array) must be of type array, int given
int(42)
array_splice(): Argument #1 ($array) must be of type array, int given
int(42)
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
}
array(3) {
  [0]=>
  int(3)
  [1]=>
  int(2)
  [2]=>
  int(1)
}
