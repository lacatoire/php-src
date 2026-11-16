--TEST--
array_replace_recursive(): a destructor releasing the source array while it is iterated
--FILE--
<?php
class D {
    function __destruct() {
        $GLOBALS['x'] = null;
    }
}

$x = [];
foreach (['a', 'b', 'c', 'd', 'e', 'f', 'g'] as $i => $k) {
    $x[$k] = $i + 1;
}
$src = ['k' => &$x];
$r = array_replace_recursive(['k' => ['a' => new D]], $src);
echo json_encode($r), "\n";

// The immutable empty array is not refcounted
echo json_encode(array_replace_recursive(['a' => ['x' => 1]], [])), "\n";
?>
--EXPECT--
{"k":{"a":1,"b":2,"c":3,"d":4,"e":5,"f":6,"g":7}}
{"a":{"x":1}}
