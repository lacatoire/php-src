--TEST--
array_merge_recursive(): reference-to-null destination value is preserved like a plain null
--FILE--
<?php
$n = null;
var_dump(array_merge_recursive(["a" => &$n], ["a" => "v"]));
var_dump(array_merge_recursive(["a" => null], ["a" => "v"]));

// A reference to a non-null scalar must still be converted like before
// (no null-preserving element inserted).
$s = "x";
var_dump(array_merge_recursive(["a" => &$s], ["a" => "v"]));

// A reference to an existing array: exercises the recursion-guard (thash)
// path with a referenced destination, unaffected by the null fix.
$arr = ["x"];
var_dump(array_merge_recursive(["a" => &$arr], ["a" => ["y"]]));
?>
--EXPECT--
array(1) {
  ["a"]=>
  array(2) {
    [0]=>
    NULL
    [1]=>
    string(1) "v"
  }
}
array(1) {
  ["a"]=>
  array(2) {
    [0]=>
    NULL
    [1]=>
    string(1) "v"
  }
}
array(1) {
  ["a"]=>
  array(2) {
    [0]=>
    string(1) "x"
    [1]=>
    string(1) "v"
  }
}
array(1) {
  ["a"]=>
  array(2) {
    [0]=>
    string(1) "x"
    [1]=>
    string(1) "y"
  }
}
