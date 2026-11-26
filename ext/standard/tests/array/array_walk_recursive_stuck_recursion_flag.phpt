--TEST--
array_walk_recursive(): does not leave an acyclic array permanently flagged as recursive
--FILE--
<?php
// Before descending into a sub-array, its hashtable is protected and only
// unprotected if the reference still points at the same table afterwards.
// A callback can keep the sub-array alive through an unrelated
// copy-on-write reference and then detach the walked slot, leaving the
// table to survive with its recursion flag stuck, so json_encode(),
// var_export() and var_dump() would treat legitimate data as a cycle.
$sub = [10, 20];
$other = [99];
$a = ['k' => &$sub];
array_walk_recursive($a, function (&$v, $k) use (&$sub, &$other) {
    static $n = 0;
    if ($n++ === 0) {
        $GLOBALS['copy'] = $sub;   // COW-share the sub-array (keeps its HT alive)
        $sub = $other;             // detach the walked slot
    }
});
$c = $GLOBALS['copy'];
var_dump(json_encode($c));
var_dump($c);

// A genuine cycle must still be detected (this exercises the same
// unprotect/protect code path, unconditionally now).
$cyclic = [];
$cyclic['self'] = &$cyclic;
try {
    array_walk_recursive($cyclic, function (&$v) {});
    echo "no error (unexpected)\n";
} catch (\Throwable $e) {
    echo get_class($e), ': ', $e->getMessage(), "\n";
}

// Detaching without keeping any other reference to the sub-array (it drops
// to refcount 0 and is freed through the normal path) must not crash.
$sub2 = [1, 2, 3];
$other2 = [9];
$a2 = ['k' => &$sub2];
array_walk_recursive($a2, function (&$v, $k) use (&$sub2, &$other2) {
    static $n = 0;
    if ($n++ === 0) {
        $sub2 = $other2;
    }
});
echo "done, no crash\n";
?>
--EXPECT--
string(7) "[10,20]"
array(2) {
  [0]=>
  int(10)
  [1]=>
  int(20)
}
Error: Recursion detected
done, no crash
