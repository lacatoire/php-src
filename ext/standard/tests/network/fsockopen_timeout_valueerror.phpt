--TEST--
fsockopen()/pfsockopen(): out-of-range $timeout ValueError names argument #5 and uses float format
--FILE--
<?php
// The ValueError is thrown before any connection attempt, so no server needed.
foreach ([-2.0, NAN, INF, 1e300] as $timeout) {
    try {
        fsockopen('127.0.0.1', 12345, $errno, $errstr, $timeout);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}

try {
    pfsockopen('127.0.0.1', 12345, $errno, $errstr, -2.0);
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}
?>
--EXPECTF--
fsockopen(): Argument #5 ($timeout) must be -1 or between 0 and %f
fsockopen(): Argument #5 ($timeout) must be -1 or between 0 and %f
fsockopen(): Argument #5 ($timeout) must be -1 or between 0 and %f
fsockopen(): Argument #5 ($timeout) must be -1 or between 0 and %f
pfsockopen(): Argument #5 ($timeout) must be -1 or between 0 and %f
