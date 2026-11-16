--TEST--
GH-2531: array_intersect_uassoc()/array_uintersect_uassoc() must not drop an entry present under a duplicated key-class
--FILE--
<?php
// The key comparator is not required to be injective (e.g. strcasecmp()).
// When both arrays contain two keys the callback treats as equal, each
// mapping to a different value, the value tie-break used to check only the
// first key-class-equal bucket it found and gave up, dropping an entry that
// was in fact present, identically, in the other array.
var_export(array_intersect_uassoc(["A" => 1, "a" => 2], ["a" => 2, "A" => 1], "strcasecmp"));
echo "\n";
var_export(array_uintersect_uassoc(["A" => 1, "a" => 2], ["a" => 2, "A" => 1], fn($x, $y) => $x <=> $y, "strcasecmp"));
echo "\n";

// A genuine value mismatch (no duplicated key-class) must still be dropped.
var_export(array_intersect_uassoc(["a" => 1], ["a" => 2], "strcasecmp"));
echo "\n";

// Three entries sharing the same key-class, all distinct values, all present.
var_export(array_intersect_uassoc(["A" => 1, "a" => 2, "aA" => 3], ["A" => 1, "a" => 2, "aA" => 3], "strcasecmp"));
echo "\n";
?>
--EXPECT--
array (
  'A' => 1,
  'a' => 2,
)
array (
  'A' => 1,
  'a' => 2,
)
array (
)
array (
  'A' => 1,
  'a' => 2,
  'aA' => 3,
)
