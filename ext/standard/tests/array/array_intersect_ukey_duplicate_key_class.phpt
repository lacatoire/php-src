--TEST--
array_intersect_ukey() / array_intersect_uassoc() / array_uintersect_uassoc(): don't drop entries when a non-injective key callback collides keys in the first array
--FILE--
<?php
// "A" and "a" both compare equal via strcasecmp, and both are legitimately
// present as distinct keys in the first array. Since a key of that class is
// present on the comparison side too, both entries must be kept, regardless
// of insertion order.
var_export(array_intersect_ukey(["A" => 1, "a" => 2], ["a" => 9], "strcasecmp"));
echo "\n";
var_export(array_intersect_ukey(["a" => 2, "A" => 1], ["a" => 9], "strcasecmp"));
echo "\n";

// Control: both classes present on the comparison side too (never triggered
// the bug, kept as a regression guard).
var_export(array_intersect_ukey(["A" => 1, "a" => 2], ["a" => 9, "A" => 8], "strcasecmp"));
echo "\n";

// Same shape for array_intersect_uassoc() (value compared internally) ...
var_export(array_intersect_uassoc(["A" => 1, "a" => 1], ["a" => 1], "strcasecmp"));
echo "\n";

// ... and array_uintersect_uassoc() (value compared with a user callback).
var_export(array_uintersect_uassoc(["A" => 1, "a" => 1], ["a" => 1], "strcmp", "strcasecmp"));
echo "\n";

// Control: a class present in the first array but absent from the
// comparison array must still be dropped.
var_export(array_intersect_ukey(["A" => 1, "a" => 2, "B" => 3], ["a" => 9], "strcasecmp"));
echo "\n";

// 3+ arguments: each comparison argument's cursor for this key-class is
// independent, so both duplicate-class entries of $array must be found in
// every comparison argument, even when each only holds a single member of
// the class (under a different case).
var_export(array_intersect_ukey(["A" => 1, "a" => 2], ["a" => 9], ["A" => 8], "strcasecmp"));
echo "\n";
var_export(array_intersect_ukey(["a" => 2, "A" => 1], ["A" => 8], ["a" => 9], "strcasecmp"));
echo "\n";

// Duplicate key-class on both sides, comparison array in reverse order.
var_export(array_intersect_ukey(["A" => 1, "a" => 2], ["A" => 8, "a" => 9], "strcasecmp"));
echo "\n";
?>
--EXPECT--
array (
  'A' => 1,
  'a' => 2,
)
array (
  'a' => 2,
  'A' => 1,
)
array (
  'A' => 1,
  'a' => 2,
)
array (
  'A' => 1,
  'a' => 1,
)
array (
  'A' => 1,
  'a' => 1,
)
array (
  'A' => 1,
  'a' => 2,
)
array (
  'A' => 1,
  'a' => 2,
)
array (
  'a' => 2,
  'A' => 1,
)
array (
  'A' => 1,
  'a' => 2,
)
