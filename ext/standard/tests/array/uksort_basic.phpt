--TEST--
Test uksort(): basic functionality
--FILE--
<?php
/*
* Function is implemented in ext/standard/array.c
*/
function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}
$a = array(3 => 'x', 2 => 'z', 5 => 'a', 6 => 'b', 1 => 'y');
uksort($a, "cmp");
foreach($a as $key => $value) {
    echo "$key: $value\n";
}
?>
--EXPECT--
1: y
2: z
3: x
5: a
6: b
