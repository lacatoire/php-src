--TEST--
implode(): the direct call accepts and rejects what the dynamic call does
--FILE--
<?php
$a = [1, 2];
$f = 'implode';

function t(string $label, callable $c) {
    try {
        $result = var_export($c(), true);
        echo $label, ': ', $result, "\n";
    } catch (Throwable $e) {
        echo $label, ': ', $e::class, ': ', $e->getMessage(), "\n";
    }
}

error_reporting(E_ALL & ~E_DEPRECATED);
t('direct array,null    ', fn() => implode($a, null));
t('dynamic array,null   ', fn() => $f($a, null));
t('direct object        ', fn() => implode(new stdClass));
t('dynamic object       ', fn() => $f(new stdClass));
t('direct array,string  ', fn() => implode([1], ","));
t('dynamic array,string ', fn() => $f([1], ","));
t('direct string        ', fn() => implode(","));
t('dynamic string       ', fn() => $f(","));
t('direct string,null   ', fn() => implode(",", null));
t('dynamic string,null  ', fn() => $f(",", null));
t('direct string,array  ', fn() => implode(",", $a));
t('direct array         ', fn() => implode($a));
?>
--EXPECT--
direct array,null    : '12'
dynamic array,null   : '12'
direct object        : TypeError: implode(): Argument #1 ($separator) must be of type array|string, stdClass given
dynamic object       : TypeError: implode(): Argument #1 ($separator) must be of type array|string, stdClass given
direct array,string  : TypeError: implode(): Argument #2 ($array) must be of type ?array, string given
dynamic array,string : TypeError: implode(): Argument #2 ($array) must be of type ?array, string given
direct string        : TypeError: implode(): If argument #1 ($separator) is of type string, argument #2 ($array) must be of type array, null given
dynamic string       : TypeError: implode(): If argument #1 ($separator) is of type string, argument #2 ($array) must be of type array, null given
direct string,null   : TypeError: implode(): If argument #1 ($separator) is of type string, argument #2 ($array) must be of type array, null given
dynamic string,null  : TypeError: implode(): If argument #1 ($separator) is of type string, argument #2 ($array) must be of type array, null given
direct string,array  : '1,2'
direct array         : '12'
