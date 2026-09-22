--TEST--
array_replace_recursive(): a non-cyclic array shared between a source value and a deeper destination value does not trigger a false "Recursion detected"
--FILE--
<?php
// The same zend_array instance (copy-on-write shared) appears as a source
// value at one level and as the matching destination value one level
// deeper on the same key path. This is not a cycle: it must merge normally.
// range(1, 1) (a runtime-built array, not a literal) is required to
// reproduce reliably: an immutable literal is never protection-flagged.
$tree = ['node' => ['children' => ['children' => ['leaf' => range(1, 1)]]]];
var_dump(array_replace_recursive($tree, ['node' => $tree['node']['children']]));

// A genuine cycle must still be detected.
$a = ['k' => [1]];
$a['k2'] = &$a;
try {
    array_replace_recursive($a, $a);
    echo "no error (unexpected)\n";
} catch (\Throwable $e) {
    echo get_class($e), ': ', $e->getMessage(), "\n";
}

// array_merge_recursive() on the same non-cyclic shape must keep working
// (it was never affected, per the issue; kept here as a cross-check).
var_dump(array_merge_recursive($tree, ['node' => $tree['node']['children']]));

// A genuine cycle through two distinct zend_reference objects (not the same
// reference reused at both the source and destination slot, unlike the
// $a['k2'] = &$a case above): $x and $y reference each other via two
// separate &-references. This exercises the moved recursion check on a
// shape that does not immediately trip the pre-existing src_entry/dest_entry
// reference-identity clause.
$x = ['k' => [1]];
$y = [];
$x['link'] = &$y;
$y['link'] = &$x;
try {
    array_replace_recursive($x, $x);
    echo "no error (unexpected)\n";
} catch (\Throwable $e) {
    echo get_class($e), ': ', $e->getMessage(), "\n";
}
?>
--EXPECT--
array(1) {
  ["node"]=>
  array(1) {
    ["children"]=>
    array(2) {
      ["children"]=>
      array(1) {
        ["leaf"]=>
        array(1) {
          [0]=>
          int(1)
        }
      }
      ["leaf"]=>
      array(1) {
        [0]=>
        int(1)
      }
    }
  }
}
Error: Recursion detected
array(1) {
  ["node"]=>
  array(1) {
    ["children"]=>
    array(2) {
      ["children"]=>
      array(1) {
        ["leaf"]=>
        array(1) {
          [0]=>
          int(1)
        }
      }
      ["leaf"]=>
      array(1) {
        [0]=>
        int(1)
      }
    }
  }
}
Error: Recursion detected
