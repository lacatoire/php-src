--TEST--
array_replace(), array_unique() and array_intersect() modifying an array in place that a destructor took a reference to
--FILE--
<?php
class D {
    function __toString(): string {
        return 'D';
    }

    function __destruct() {
        foreach (debug_backtrace() as $frame) {
            if (isset($frame['args'][0]) && is_array($frame['args'][0])) {
                $GLOBALS['grabbed'] = $frame['args'][0];
            }
        }
    }
}

echo json_encode(array_replace([new D, 'keep'], [1, 2])), "\n";
echo json_encode(array_replace(['a' => new D, 'b' => 'keep', 'c' => 1], ['a' => 1], ['c' => 2])), "\n";
echo count(array_unique([new D, new D], SORT_REGULAR)), "\n";
echo json_encode(array_intersect(['x' => new D, 'y' => 'a', 'z' => 'zzz'], ['a', 'b'])), "\n";
echo json_encode(array_intersect(['x' => new D, 'z1' => 'zz1', 'z2' => 'zz2', 'z3' => 'zz3', 'y' => 'a'], ['a', 'b'])), "\n";
echo json_encode(array_uintersect([new D, 'a', 'zzz'], ['a', 'b'], strcmp(...))), "\n";
?>
--EXPECT--
[1,2]
{"a":1,"b":"keep","c":2}
1
{"y":"a"}
{"y":"a"}
{"1":"a"}
