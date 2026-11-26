--TEST--
array_intersect_ukey() and friends do not modify a first argument retained by a user callback
--FILE--
<?php
function make(string $key): array { return [$key => 1]; }

$callbacks = [
    'array_intersect_ukey' => fn($cb) => array_intersect_ukey(make('a'), ['b' => 2], $cb),
    'array_intersect_uassoc' => fn($cb) => array_intersect_uassoc(make('a'), ['b' => 2], $cb),
    'array_uintersect' => fn($cb) => array_uintersect(make('a'), ['b' => 2], $cb),
    'array_uintersect_uassoc' => fn($cb) => array_uintersect_uassoc(make('a'), ['b' => 2], $cb, $cb),
];

foreach ($callbacks as $name => $call) {
    $captured = null;
    $result = $call(function ($x, $y) use (&$captured) {
        foreach (debug_backtrace() as $frame) {
            if (isset($frame['args'][0]) && is_array($frame['args'][0])) {
                $captured ??= $frame['args'][0];
                break;
            }
        }
        return $x <=> $y;
    });
    echo $name, ': ', json_encode($captured), ' ', json_encode($result), "\n";
}
?>
--EXPECT--
array_intersect_ukey: {"a":1} []
array_intersect_uassoc: {"a":1} []
array_uintersect: {"a":1} []
array_uintersect_uassoc: {"a":1} []
