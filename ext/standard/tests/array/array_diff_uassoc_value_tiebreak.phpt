--TEST--
array_diff_uassoc() / array_udiff_uassoc(): value tie-break scans all key-equal buckets, order-independent
--FILE--
<?php
$a = ["A" => "x"];

// "A" => "x" is present identically in the comparison array under both
// orderings, so the result must be [] both ways. The key callback
// (strcasecmp) is not injective: "a" and "A" compare equal, so the value
// tie-break must not stop at the first key-equal bucket found.
var_export(array_diff_uassoc($a, ["a" => "y", "A" => "x"], "strcasecmp"));
echo "\n";
var_export(array_diff_uassoc($a, ["A" => "x", "a" => "y"], "strcasecmp"));
echo "\n";

var_export(array_udiff_uassoc($a, ["a" => "y", "A" => "x"], "strcmp", "strcasecmp"));
echo "\n";
var_export(array_udiff_uassoc($a, ["A" => "x", "a" => "y"], "strcmp", "strcasecmp"));
echo "\n";

// Control: no key-equal bucket has a matching value, the entry must be kept.
var_export(array_diff_uassoc($a, ["a" => "y", "A" => "z"], "strcasecmp"));
echo "\n";

// Group of 3 key-equal buckets (callback compares only the first letter),
// with the matching value in the last one: exercises several consecutive
// ptr++ advances within the same key-class before the match is found.
function first_letter_cmp($x, $y) {
    return strtolower($x[0]) <=> strtolower($y[0]);
}
var_export(array_diff_uassoc(["Ax" => "x"], ["aY" => "y", "AZ" => "z", "Ax" => "x"], "first_letter_cmp"));
echo "\n";
?>
--EXPECT--
array (
)
array (
)
array (
)
array (
)
array (
  'A' => 'x',
)
array (
)
