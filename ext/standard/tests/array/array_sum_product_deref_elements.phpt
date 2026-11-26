--TEST--
array_sum() / array_product(): a referenced element behaves the same as a by-value one
--FILE--
<?php
// Elements become references without any explicit & in the call, e.g. after
// foreach ($a as &$v) {} unset($v);. The result must not depend on it.
//
// NOTE: the scenario that originally motivated this fix, an object with an
// arithmetic operator handler (BcMath\Number, GMP) that *succeeds* the
// operation and was returned as-is instead of int|float, is not exercised
// here: bcmath/gmp are not available in this environment. The stdClass case
// below only exercises the failure path of the same IS_OBJECT check
// (unsupported type, cast_object() absent), not the success path that
// actually produced the wrong return type. The fix dereferences entry
// identically before every type check regardless of which branch it takes,
// so the same code path is covered, but this specific success case relies
// on that reasoning rather than an empirical run; verify with bcmath/gmp
// available (e.g. in CI) if in doubt.

// Non-numeric string: counted as 0, with a warning, exactly like by value.
$s = "abc";
var_dump(array_product([$s, 5]) === array_product([&$s, 5]));
var_dump(array_sum([$s, 5]) === array_sum([&$s, 5]));

// Resource: counted as its handle ID, exactly like by value.
$r = fopen('php://memory', 'r');
$id = (int) $r;
var_dump(array_sum([&$r, 5]) === $id + 5);
fclose($r);

// Object without operator overloading: unsupported, exactly like by value,
// never returned as-is.
$o = new stdClass();
var_dump(array_sum([$o, 5]) === array_sum([&$o, 5]));
$result = array_sum([&$o, 5]);
var_dump(is_int($result) || is_float($result));

// The foreach-then-unset idiom specifically (not an explicit &).
function mk() { $a = [2, 3]; foreach ($a as &$v) {} unset($v); return $a; }
var_dump(array_product(mk()) === array_product([2, 3]));
var_dump(array_sum(mk()) === array_sum([2, 3]));
?>
--EXPECTF--
Warning: array_product(): Multiplication is not supported on type string in %s on line %d

Warning: array_product(): Multiplication is not supported on type string in %s on line %d
bool(true)

Warning: array_sum(): Addition is not supported on type string in %s on line %d

Warning: array_sum(): Addition is not supported on type string in %s on line %d
bool(true)

Warning: array_sum(): Addition is not supported on type resource in %s on line %d
bool(true)

Warning: array_sum(): Addition is not supported on type stdClass in %s on line %d

Warning: array_sum(): Addition is not supported on type stdClass in %s on line %d
bool(true)

Warning: array_sum(): Addition is not supported on type stdClass in %s on line %d
bool(true)
bool(true)
bool(true)
