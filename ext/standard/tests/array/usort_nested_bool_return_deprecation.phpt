--TEST--
A nested sort does not reset the bool-return deprecation of the outer sort
--FILE--
<?php
$count = [];
set_error_handler(function ($errno, $message) use (&$count) {
    $function = strstr($message, '(', true);
    $count[$function] = ($count[$function] ?? 0) + 1;
    return true;
});

$array = [3, 1, 2, 5, 4];
uasort($array, function ($x, $y) {
    $inner = [];
    usort($inner, 'strcmp');
    return $x > $y;
});
var_dump($count);

$count = [];
$array = [3, 1, 2, 5, 4];
uasort($array, function ($x, $y) {
    $inner = [2, 1];
    usort($inner, fn($a, $b) => $a > $b);
    return $x > $y;
});
var_dump($count['uasort'] ?? 0);
?>
--EXPECT--
array(1) {
  ["uasort"]=>
  int(1)
}
int(1)
