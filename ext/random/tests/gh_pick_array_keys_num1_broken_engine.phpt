--TEST--
Random\Randomizer::pickArrayKeys(): num=1 throws BrokenRandomEngineError instead of looping forever on a broken engine
--EXTENSIONS--
random
--FILE--
<?php
final class Zero implements Random\Engine {
    public function generate(): string { return "\0\0\0\0\0\0\0\0"; }
}

$r = new Random\Randomizer(new Zero);

// At least half full so the sampling loop (not the linear scan) is used.
$a = [1, 2, 3, 4];
unset($a[0]);

try {
    var_dump($r->pickArrayKeys($a, 2));
} catch (Throwable $e) {
    echo get_class($e), ': ', $e->getMessage(), "\n";
}

try {
    var_dump($r->pickArrayKeys($a, 1));
} catch (Throwable $e) {
    echo get_class($e), ': ', $e->getMessage(), "\n";
}

// Same shape, packed array (contiguous 0-based integer keys, no string keys)
// with a hole, to hit the arPacked branch of the num=1 sampling loop.
// unset() alone does not un-pack an array, so this stays HT_IS_PACKED.
$b = [10, 20, 30, 40];
unset($b[0]);
try {
    var_dump($r->pickArrayKeys($b, 1));
} catch (Throwable $e) {
    echo get_class($e), ': ', $e->getMessage(), "\n";
}

// Genuinely non-packed (hash) array: non-contiguous integer keys from
// creation, so it never was HT_IS_PACKED, with a hole to hit the arData
// (non-packed) branch of the num=1 sampling loop.
// Delete the first-inserted entry specifically: the Zero engine always
// samples bucket slot 0, so the hole must be at that slot for the loop to
// ever retry (deleting a later key would leave slot 0 valid and the call
// would return immediately without exercising the retry logic at all).
$c = [0 => 'a', 2 => 'b', 3 => 'c', 4 => 'd'];
unset($c[0]);
try {
    var_dump($r->pickArrayKeys($c, 1));
} catch (Throwable $e) {
    echo get_class($e), ': ', $e->getMessage(), "\n";
}
?>
--EXPECT--
array(2) {
  [0]=>
  int(2)
  [1]=>
  int(3)
}
Random\BrokenRandomEngineError: Failed to generate an acceptable random number in 50 attempts
Random\BrokenRandomEngineError: Failed to generate an acceptable random number in 50 attempts
Random\BrokenRandomEngineError: Failed to generate an acceptable random number in 50 attempts
