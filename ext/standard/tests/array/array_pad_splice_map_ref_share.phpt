--TEST--
array_pad() / array_splice() / array_map(null, ...): don't share refcount-1 references with the input
--FILE--
<?php
// The classic "break the reference" idiom: after this, $a's elements are
// plain values again, not references, even though the internal zvals were
// briefly wrapped in a refcount-1 zend_reference during the foreach.
function mk() { $a = [1, 2]; foreach ($a as &$v) {} unset($v); return $a; }

$a = mk();
$b = array_pad($a, 3, 0);
$b[0] = 99;
var_dump($a[0]);

$a = mk();
$t = [0, 0];
array_splice($t, 0, 1, $a);
$t[0] = 99;
var_dump($a[0]);

// array_splice() with a string-keyed replacement array (non-packed branch).
function mkstr() { $a = ['x' => 1, 'y' => 2]; foreach ($a as &$v) {} unset($v); return $a; }
$a = mkstr();
$t = [0, 0];
array_splice($t, 0, 1, $a);
$t['x'] = 99;
var_dump($a['x']);

$a = mk();
$m = array_map(null, $a, [7, 8]);
$m[0][0] = 99;
var_dump($a[0]);

// array_map(null, ...) with string-keyed (non-packed) input arrays.
$a = mkstr();
$m = array_map(null, $a, ['x' => 7, 'y' => 8]);
$m[0][0] = 99;
var_dump($a['x']);

$a = ['k' => 1, 0 => 2];
foreach ($a as &$v) {}
unset($v);
$b = array_pad($a, -3, 0);
$b['k'] = 99;
var_dump($a['k']);

// Control: an explicit reference (not the "broken" foreach kind, refcount
// stays above 1) must still be shared, this is unrelated to the fix.
$n = 1;
$a = ['k' => &$n];
$b = array_pad($a, 3, 0);
$b['k'] = 42;
var_dump($n);
?>
--EXPECT--
int(1)
int(1)
int(1)
int(1)
int(1)
int(1)
int(42)
