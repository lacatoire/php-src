--TEST--
mt_srand() applies the MT_RAND_PHP mode after the deprecation handler ran
--FILE--
<?php
$mode = 1;

mt_srand(5, $mode);
$expected = [mt_rand(), mt_rand()];

set_error_handler(function ($errno, $errstr) {
    mt_rand();
    return true;
});
mt_srand(5, $mode);
var_dump([mt_rand(), mt_rand()] === $expected);

set_error_handler(function ($errno, $errstr) {
    throw new ErrorException($errstr);
});
mt_srand(7);
$before = mt_rand();
mt_srand(7);
try {
    mt_srand(5, $mode);
} catch (ErrorException $e) {
    echo $e->getMessage(), "\n";
}
var_dump(mt_rand() === $before);
?>
--EXPECTF--
Deprecated: The MT_RAND_PHP variant of Mt19937 is deprecated in %s on line %d
bool(true)
The MT_RAND_PHP variant of Mt19937 is deprecated
bool(true)
