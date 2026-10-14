--TEST--
range(): decreasing ranges exceeding the maximum array size report start and end in user order
--SKIPIF--
<?php
if (PHP_INT_SIZE != 8) die("skip this test is for 64bit platform only");
?>
--FILE--
<?php

$cases = [
    [5, -(1 << 40)],
    [-(1 << 40), 5],
    [5.0, -1e12],
    [-1e12, 5.0],
    [0, -(1 << 41), 2],
];
foreach ($cases as $args) {
    try {
        range(...$args);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}

?>
--EXPECT--
The supplied range exceeds the maximum array size by 1098437885958 elements: start=5, end=-1099511627776, step=1. Calculated size: 1099511627781. Maximum size: 1073741824.
The supplied range exceeds the maximum array size by 1098437885958 elements: start=-1099511627776, end=5, step=1. Calculated size: 1099511627781. Maximum size: 1073741824.
The supplied range exceeds the maximum array size by 998926258182.0 elements: start=5.0, end=-1000000000000.0, step=1.0. Max size: 1073741824
The supplied range exceeds the maximum array size by 998926258182.0 elements: start=-1000000000000.0, end=5.0, step=1.0. Max size: 1073741824
The supplied range exceeds the maximum array size by 1098437885953 elements: start=0, end=-2199023255552, step=2. Calculated size: 1099511627776. Maximum size: 1073741824.
