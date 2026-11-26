--TEST--
strtr() accepts an explicit null for $to
--FILE--
<?php
var_dump(strtr('abc', ['a' => 'x'], null));

$to = null;
var_dump(strtr('abc', ['a' => 'x'], $to));
var_dump(call_user_func('strtr', 'abc', ['a' => 'x'], null));

try {
    var_dump(strtr('abc', 'a', null));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

$from = 'a';
try {
    var_dump(strtr('abc', $from, $to));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

try {
    var_dump(call_user_func('strtr', 'abc', 'a', null));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

try {
    var_dump(strtr('abc', ['a' => 'x'], 'y'));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

var_dump(strtr('abc', 'a', 'x'));
?>
--EXPECT--
string(3) "xbc"
string(3) "xbc"
string(3) "xbc"
TypeError: strtr(): Argument #2 ($from) must be of type array, string given
TypeError: strtr(): Argument #2 ($from) must be of type array, string given
TypeError: strtr(): Argument #2 ($from) must be of type array, string given
TypeError: strtr(): Argument #2 ($from) must be of type string, array given
string(3) "xbc"
