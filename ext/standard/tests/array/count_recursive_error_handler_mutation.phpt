--TEST--
count() with COUNT_RECURSIVE: an error handler freeing the array being counted
--FILE--
<?php
$outer = [];
$inner = [&$outer, 10, 20, 30];
$outer[] = &$inner;
$keep = [];
set_error_handler(function ($no, $str) use (&$inner, &$keep) {
    echo $str, "\n";
    $inner = null;
    for ($i = 0; $i < 64; $i++) {
        $keep[] = [[1, 2, 3], [4, 5, 6], [7, 8, 9], [1, 2, 3]];
    }
    return true;
});
var_dump(count($outer, COUNT_RECURSIVE));
?>
--EXPECTF--
count(): Recursion detected
int(%d)
