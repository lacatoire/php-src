--TEST--
min(), max(): the two-argument fast path returns what the regular call returns
--FILE--
<?php
function show(mixed $v): string {
    return var_export($v, true);
}

$pairs = [
    [1.0, NAN], [NAN, 1.0], [1, NAN], [NAN, 1],
    [0, false], [1, "1"], [1, 1.0], [0, -0.0], [0, null], ["1", 1], [true, 1], [false, 0],
];
foreach ($pairs as [$a, $b]) {
    foreach (['min', 'max'] as $f) {
        $direct = $f === 'min' ? min($a, $b) : max($a, $b);
        $regular = call_user_func($f, $a, $b);
        echo $f, '(', show($a), ', ', show($b), '): ', show($direct), ' ', show($regular), ' ',
            show($direct) === show($regular) ? 'same' : 'DIFFERENT', "\n";
    }
}
?>
--EXPECT--
min(1.0, NAN): 1.0 1.0 same
max(1.0, NAN): 1.0 1.0 same
min(NAN, 1.0): NAN NAN same
max(NAN, 1.0): NAN NAN same
min(1, NAN): 1 1 same
max(1, NAN): 1 1 same
min(NAN, 1): NAN NAN same
max(NAN, 1): NAN NAN same
min(0, false): 0 0 same
max(0, false): 0 0 same
min(1, '1'): 1 1 same
max(1, '1'): 1 1 same
min(1, 1.0): 1 1 same
max(1, 1.0): 1 1 same
min(0, -0.0): 0 0 same
max(0, -0.0): 0 0 same
min(0, NULL): 0 0 same
max(0, NULL): 0 0 same
min('1', 1): '1' '1' same
max('1', 1): '1' '1' same
min(true, 1): true true same
max(true, 1): true true same
min(false, 0): false false same
max(false, 0): false false same
