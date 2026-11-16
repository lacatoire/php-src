--TEST--
ldap_err2str(): a code outside the int range is rejected
--EXTENSIONS--
ldap
--FILE--
<?php
foreach ([PHP_INT_MIN, PHP_INT_MAX, 2**32, -(2**31) - 1] as $code) {
    try {
        var_dump(ldap_err2str($code));
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}
var_dump(ldap_err2str(2));
?>
--EXPECT--
ldap_err2str(): Argument #1 ($errno) must be between -2147483648 and 2147483647
ldap_err2str(): Argument #1 ($errno) must be between -2147483648 and 2147483647
ldap_err2str(): Argument #1 ($errno) must be between -2147483648 and 2147483647
ldap_err2str(): Argument #1 ($errno) must be between -2147483648 and 2147483647
string(14) "Protocol error"
