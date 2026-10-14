--TEST--
array_walk_recursive(): does not leak the current string key when aborting on "Recursion detected"
--FILE--
<?php
// A non-interned (refcounted) string key is needed to observe the leak;
// integer and interned keys never leak, so this key is built at runtime.
$key = str_repeat('K', 40) . mt_rand();
$y = ['b' => 1];
$x = [];
$x[$key] = &$y;
$y['back'] = &$x; // reference cycle
try {
    array_walk_recursive($x, fn(&$v, $k) => null);
    echo "no exception (unexpected)\n";
} catch (\Throwable $e) {
    echo get_class($e), ': ', $e->getMessage(), "\n";
}
?>
--EXPECT--
Error: Recursion detected
