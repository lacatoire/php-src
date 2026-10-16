--TEST--
defined() with a non-string literal applies strict_types
--FILE--
<?php
declare(strict_types=1);

foreach (['int' => fn() => defined(123), 'float' => fn() => defined(1.5), 'null' => fn() => defined(null), 'bool' => fn() => defined(true)] as $type => $f) {
    try {
        var_dump($f());
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
var_dump(defined('PHP_VERSION'), defined('NOT_DEFINED'));
?>
--EXPECT--
TypeError: defined(): Argument #1 ($constant_name) must be of type string, int given
TypeError: defined(): Argument #1 ($constant_name) must be of type string, float given
TypeError: defined(): Argument #1 ($constant_name) must be of type string, null given
TypeError: defined(): Argument #1 ($constant_name) must be of type string, true given
bool(true)
bool(false)
