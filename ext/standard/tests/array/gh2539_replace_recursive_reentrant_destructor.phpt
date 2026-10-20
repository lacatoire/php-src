--TEST--
GH-2539: array_replace_recursive() must not corrupt a nested in-place HashTable reachable from a destructor
--FILE--
<?php
// The in-place optimization writes directly into the first argument's
// HashTable at every recursion depth, on the assumption its refcount stays
// exclusive for the whole operation. A destructor invoked while
// overwriting a value can walk debug_backtrace(), momentarily holding a
// reference to the *nested* HashTable still being written into (the same
// way an exception trace would). The top-level in-place check alone does
// not protect these nested sub-arrays: on unpatched code this aborts with
// a zend_hash.c assertion failure (debug build) as soon as a later key in
// the same nested array is written.
class D {
    function __destruct() {
        foreach (debug_backtrace() as $frame) {
            if (isset($frame['args'][0]['a']) && is_array($frame['args'][0]['a'])) {
                // Never actually reached (array_replace_recursive() is not
                // itself a PHP-visible frame here), but evaluating this
                // nested isset() is what momentarily holds the extra
                // reference that reproduces the bug.
                $GLOBALS['grabbed'] = $frame['args'][0]['a'];
            }
        }
    }
}

$r = array_replace_recursive(
    ['a' => ['x' => new D, 'y' => 'keep', 'z' => 'zzz']],
    ['a' => ['x' => 1, 'y' => 2, 'z' => 3]]
);
var_dump($r);

// A destructor that throws must still propagate cleanly.
class E {
    function __destruct() {
        throw new Exception('e');
    }
}
try {
    array_replace_recursive(['a' => ['x' => new E]], ['a' => ['x' => 1]]);
    echo "no exception (unexpected)\n";
} catch (\Throwable $ex) {
    echo get_class($ex), ': ', $ex->getMessage(), "\n";
}

// Normal (non-reentrant) behavior must be unaffected.
var_dump(array_replace_recursive(['a' => ['x' => 1, 'y' => 2]], ['a' => ['x' => 10]]));
$orig = ['a' => ['x' => 1]];
$copy = array_replace_recursive($orig, ['a' => ['x' => 2]]);
var_dump($orig, $copy);

echo "done, no crash\n";
?>
--EXPECT--
array(1) {
  ["a"]=>
  array(3) {
    ["x"]=>
    int(1)
    ["y"]=>
    int(2)
    ["z"]=>
    int(3)
  }
}
Exception: e
array(1) {
  ["a"]=>
  array(2) {
    ["x"]=>
    int(10)
    ["y"]=>
    int(2)
  }
}
array(1) {
  ["a"]=>
  array(1) {
    ["x"]=>
    int(1)
  }
}
array(1) {
  ["a"]=>
  array(1) {
    ["x"]=>
    int(2)
  }
}
done, no crash
