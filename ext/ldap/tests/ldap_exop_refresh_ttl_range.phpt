--TEST--
ldap_exop_refresh(): a ttl outside the 32-bit range is rejected
--EXTENSIONS--
ldap
--SKIPIF--
<?php
if (!function_exists("ldap_exop_refresh")) die("skip ldap_exop_refresh() is not available");
?>
--FILE--
<?php
$l = ldap_connect("ldap://127.0.0.1:1");
foreach ([2**32 + 3600, -(2**31) - 1, PHP_INT_MAX] as $ttl) {
    try {
        ldap_exop_refresh($l, "cn=d,dc=example,dc=com", $ttl);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}
?>
--EXPECT--
ldap_exop_refresh(): Argument #3 ($ttl) must be between -2147483648 and 2147483647
ldap_exop_refresh(): Argument #3 ($ttl) must be between -2147483648 and 2147483647
ldap_exop_refresh(): Argument #3 ($ttl) must be between -2147483648 and 2147483647
