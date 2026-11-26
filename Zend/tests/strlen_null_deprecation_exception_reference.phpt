--TEST--
strlen(): reference operand is released when the null deprecation throws
--FILE--
<?php

set_error_handler(function ($errno, $errstr) {
    throw new ErrorException($errstr);
});

$arr = [null];
function &elem() {
    global $arr;
    return $arr[0];
}

try {
    strlen(elem());
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

$copy = $arr;
$copy[0] = 'changed';
var_dump($arr[0]);

?>
--EXPECT--
ErrorException: strlen(): Passing null to parameter #1 ($string) of type string is deprecated
NULL
