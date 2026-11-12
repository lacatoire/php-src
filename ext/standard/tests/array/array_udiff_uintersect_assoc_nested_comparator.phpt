--TEST--
array_udiff_assoc() / array_uintersect_assoc(): save and restore the user comparator global, don't clobber an outer sort's callback
--FILE--
<?php
// array_udiff_assoc()/array_uintersect_assoc() used to write
// BG(user_compare_fci) straight from parameter parsing with no save/restore,
// unlike usort()/array_udiff()/array_uintersect() and friends. A call
// nested inside another user-comparator sort left that outer sort pointing
// at the inner, already-freed callback.
$a = [3, 1, 2, 5, 4];
usort($a, function ($x, $y) {
    static $n = 0;
    if ($n++ === 0) {
        $d = new stdClass();
        array_udiff_assoc([1], [2], function ($p, $q) use ($d) { return $p <=> $q; });
    }
    return $x <=> $y;
});
var_dump($a);

$b = [3, 1, 2, 5, 4];
usort($b, function ($x, $y) {
    static $n = 0;
    if ($n++ === 0) {
        $d = new stdClass();
        array_uintersect_assoc([1 => 1], [1 => 1], function ($p, $q) use ($d) { return $p <=> $q; });
    }
    return $x <=> $y;
});
var_dump($b);

// Control: the functions themselves still work correctly outside of any
// nesting.
var_dump(array_udiff_assoc(['a' => 1, 'b' => 2], ['a' => 1], fn($x, $y) => $x <=> $y));
var_dump(array_uintersect_assoc(['a' => 1, 'b' => 2], ['a' => 1, 'b' => 3], fn($x, $y) => $x <=> $y));
?>
--EXPECT--
array(5) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
  [3]=>
  int(4)
  [4]=>
  int(5)
}
array(5) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
  [3]=>
  int(4)
  [4]=>
  int(5)
}
array(1) {
  ["b"]=>
  int(2)
}
array(1) {
  ["a"]=>
  int(1)
}
