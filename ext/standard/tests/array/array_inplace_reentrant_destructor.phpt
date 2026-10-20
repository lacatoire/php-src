--TEST--
array_replace() / array_unique() / array_intersect() / array_uintersect(): a destructor reachable through debug_backtrace() during the in-place optimization must not corrupt or crash
--FILE--
<?php
// The in-place optimization for these functions assumes the first
// (refcount-1) argument cannot be observed by user code while the function
// is still writing into it. A destructor that runs during the operation can
// grab a reference to it through debug_backtrace(), which used to leave the
// function writing into a now-shared array: an assertion failure on debug
// builds, memory corruption (up to a crash) on release builds. The
// destructor below does not throw, to keep this test focused on that
// memory-safety issue rather than on exception-during-destruction semantics
// (a separate, general PHP behavior unrelated to this fix).
class D {
    function __toString(): string {
        return 'D#' . spl_object_id($this);
    }

    function __destruct() {
        // Grabs a reference to the array still being written into, the
        // same way an exception trace or debug_backtrace() call from
        // userland code would.
        foreach (debug_backtrace() as $frame) {
            if (isset($frame['args'][0]) && is_array($frame['args'][0])) {
                $GLOBALS['grabbed'] = $frame['args'][0];
            }
        }
    }
}

$GLOBALS['grabbed'] = null;
$r = array_replace([new D, 'keep'], [1, 2]);
var_dump($r);

$GLOBALS['grabbed'] = null;
$r = array_replace(['a' => new D, 'b' => 'keep', 'c' => 1], ['a' => 1], ['c' => 2]);
var_dump($r);

$GLOBALS['grabbed'] = null;
// Two instances of the same property-less class compare equal under
// SORT_REGULAR, so one is deleted as a duplicate: its destructor fires
// mid-operation.
$r = array_unique([new D, new D], SORT_REGULAR);
var_dump(count($r));

$GLOBALS['grabbed'] = null;
$r = array_intersect(['x' => new D, 'y' => 'a', 'z' => 'zzz'], ['a', 'b']);
var_dump($r);

$GLOBALS['grabbed'] = null;
// Several consecutive deletions after the reentrancy point, to exercise
// php_array_intersect_hash()'s bitset deletion loop beyond a single delete.
$r = array_intersect(['x' => new D, 'z1' => 'zz1', 'z2' => 'zz2', 'z3' => 'zz3', 'y' => 'a'], ['a', 'b']);
var_dump($r);

$GLOBALS['grabbed'] = null;
$r = array_uintersect([new D, 'a', 'zzz'], ['a', 'b'], fn($x, $y) => strcmp((string) $x, (string) $y));
var_dump($r);

// Normal (non-reentrant) behavior must be unaffected.
var_dump(array_replace(['a' => 1, 'b' => 2], ['b' => 3, 'c' => 4]));
var_dump(array_unique([3, 1, 2, 1, 3], SORT_REGULAR));
var_dump(array_intersect(['a', 'b', 'c'], ['b', 'c', 'd']));
var_dump(array_uintersect(['a', 'b', 'c'], ['b', 'c', 'd'], 'strcmp'));

echo "done, no crash\n";
?>
--EXPECTF--
array(2) {
  [0]=>
  int(1)
  [1]=>
  int(2)
}
array(3) {
  ["a"]=>
  int(1)
  ["b"]=>
  string(4) "keep"
  ["c"]=>
  int(2)
}
int(1)
array(1) {
  ["y"]=>
  string(1) "a"
}
array(1) {
  ["y"]=>
  string(1) "a"
}
array(1) {
  [1]=>
  string(1) "a"
}
array(3) {
  ["a"]=>
  int(1)
  ["b"]=>
  int(3)
  ["c"]=>
  int(4)
}
array(3) {
  [0]=>
  int(3)
  [1]=>
  int(1)
  [2]=>
  int(2)
}
array(2) {
  [1]=>
  string(1) "b"
  [2]=>
  string(1) "c"
}
array(2) {
  [1]=>
  string(1) "b"
  [2]=>
  string(1) "c"
}
done, no crash
