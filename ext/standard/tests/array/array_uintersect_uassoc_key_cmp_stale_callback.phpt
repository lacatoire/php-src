--TEST--
array_uintersect_uassoc(): key comparison of arrays beyond the second uses the key callback, not the leftover data callback
--FILE--
<?php
// It needs at least three arrays and an intermediate array that matches the
// current element on both key and value; two arrays never reach the stale
// state (BG(user_compare_fci) briefly holds the data callback after a
// value-equal comparison, and must be restored to the key callback before
// the next array's key search).
$a = [5 => 'm'];
$b = [5 => 'm'];
$c = [1 => 'zz', 5 => 'm'];
$key_cb  = fn($x, $y) => $x <=> $y;   // ascending
$data_cb = fn($x, $y) => $y <=> $x;   // descending (a valid total order)
var_export(array_uintersect_uassoc($a, $b, $c, $data_cb, $key_cb));
echo "\n";

// Same shape, entry genuinely absent from the third array: must still be
// dropped (control, not affected by the bug, kept as a regression guard).
$c2 = [1 => 'zz', 9 => 'other'];
var_export(array_uintersect_uassoc($a, $b, $c2, $data_cb, $key_cb));
echo "\n";

// Four arrays: the stale callback would otherwise persist across more than
// one extra array.
$d = [5 => 'm'];
var_export(array_uintersect_uassoc($a, $b, $c, $d, $data_cb, $key_cb));
echo "\n";
?>
--EXPECT--
array (
  5 => 'm',
)
array (
)
array (
  5 => 'm',
)
