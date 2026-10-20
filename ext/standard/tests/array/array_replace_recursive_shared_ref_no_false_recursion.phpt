--TEST--
array_replace_recursive(): a shared non-cyclic reference does not trigger a false "Recursion detected"
--FILE--
<?php
function t($label, $fn) {
    try {
        $result = json_encode($fn());
    } catch (\Throwable $e) {
        $result = get_class($e) . ': ' . $e->getMessage();
    }
    echo "$label: $result\n";
}

// The false positive depended on the shared reference's refcount parity,
// which is unrelated to whether the data actually forms a cycle.
t('0 extra holder', function () {
    $x = ['p' => 1];
    $a = ['k' => &$x];
    return array_replace_recursive($a, $a);
});
t('1 extra holder', function () {
    $x = ['p' => 1];
    $a = ['k' => &$x];
    $t = &$x;
    return array_replace_recursive($a, $a);
});

// Two distinct arrays sharing a reference (e.g. a config array and an
// override built from it), rather than the same array passed twice.
t('two distinct arrays', function () {
    $x = ['p' => 1];
    $config = ['k' => &$x];
    $override = ['k' => &$x, 'extra' => 2];
    return array_replace_recursive($config, $override);
});

// foreach (... as &$v) {} without unset($v) also leaves a live reference.
t('foreach without unset', function () {
    $a = ['k' => ['p' => 1]];
    foreach ($a as &$v) {
    }
    return array_replace_recursive($a, $a);
});

// A genuine cycle must still be detected.
t('genuine cycle', function () {
    $a = ['k' => [1]];
    $a['k2'] = &$a;
    return array_replace_recursive($a, $a);
});
?>
--EXPECT--
0 extra holder: {"k":{"p":1}}
1 extra holder: {"k":{"p":1}}
two distinct arrays: {"k":{"p":1},"extra":2}
foreach without unset: {"k":{"p":1}}
genuine cycle: Error: Recursion detected
