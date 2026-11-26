--TEST--
Sorting a single-element array resets the internal pointer
--FILE--
<?php
foreach (['ksort', 'krsort', 'asort', 'arsort', 'natsort', 'natcasesort'] as $function) {
    $array = ['k' => 'v'];
    next($array);
    $function($array);
    echo $function, ': ', var_export(current($array), true), "\n";
}

$array = ['x' => 1, 'k' => 'v'];
unset($array['x']);
next($array);
ksort($array);
echo 'ksort after unset: ', var_export(current($array), true), "\n";
?>
--EXPECT--
ksort: 'v'
krsort: 'v'
asort: 'v'
arsort: 'v'
natsort: 'v'
natcasesort: 'v'
ksort after unset: 'v'
