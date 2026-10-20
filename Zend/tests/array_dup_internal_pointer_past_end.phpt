--TEST--
Separating an array keeps an internal pointer that is past either end
--FILE--
<?php

// Pointer before the first element
$a = ['a' => 1, 'b' => 2, 'c' => 3];
prev($a);
var_dump(key($a), next($a));

$a = ['a' => 1, 'b' => 2, 'c' => 3];
prev($a);
$b = $a;
var_dump(key($a), next($a), key($a));

// Pointer past the last element
$a = ['a' => 1, 'b' => 2, 'c' => 3];
end($a);
next($a);
$b = $a;
$b[] = 9;
var_dump(key($a), key($b));

// Packed array
$a = [1, 2, 3];
prev($a);
$b = $a;
var_dump(key($a), next($a));

$a = [1, 2, 3];
end($a);
next($a);
$b = $a;
$b[] = 9;
var_dump(key($a), key($b));

// Array with holes
$a = [1, 2, 3, 4];
unset($a[1]);
end($a);
next($a);
$b = $a;
$b[] = 9;
var_dump(key($a), key($b));

?>
--EXPECT--
NULL
bool(false)
NULL
bool(false)
NULL
NULL
int(0)
NULL
bool(false)
NULL
int(3)
NULL
int(4)
