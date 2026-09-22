--TEST--
array_multisort(): next free integer key matches the number of integer keys, not the array size
--FILE--
<?php
// No integer key survives the sort: next append must land on 0, like a fresh
// array literal, not on the total element count.
$a = ["b" => 2, "a" => 1];
array_multisort($a);
$a[] = "x";
var_dump(array_key_last($a));

// One integer key survives: next append must land on 1 (0 already in use
// after renumbering), not on the total element count.
$b = [5 => 2, "a" => 1];
array_multisort($b);
$b[] = "x";
var_dump(array_key_last($b));

// Multiple arrays sorted together must each get their own correct next key.
$c1 = ["b" => 2, "a" => 1];
$c2 = [5 => "y", "z" => "x"];
array_multisort($c1, $c2);
$c1[] = "next1";
$c2[] = "next2";
var_dump(array_key_last($c1));
var_dump(array_key_last($c2));

// Control: purely integer-keyed array (packed after sort), unaffected by
// this fix (nNextFreeElement = array_size is already correct there).
$d = [2 => "b", 0 => "a"];
array_multisort($d);
$d[] = "x";
var_dump(array_key_last($d));

// Distinguishes ZEND_LONG_MIN from 0: an explicit negative integer key
// inserted right after the sort (before any append) must behave the same
// as on a fresh array, which only ZEND_LONG_MIN guarantees.
$e = ["b" => 2, "a" => 1];
array_multisort($e);
$e[-3] = "y";
$e[] = "z";
var_dump(array_key_last($e));
?>
--EXPECT--
int(0)
int(1)
int(0)
int(1)
int(2)
int(-2)
