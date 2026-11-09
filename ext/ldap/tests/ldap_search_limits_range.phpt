--TEST--
ldap_search(), ldap_list(), ldap_read(): limits outside the int range are rejected
--EXTENSIONS--
ldap
--FILE--
<?php
$l = ldap_connect("ldap://127.0.0.1:1");
foreach (["ldap_search", "ldap_list", "ldap_read"] as $f) {
    foreach ([[5, 2**32], [6, 2**32 + 1], [7, 2**32], [8, PHP_INT_MAX]] as [$pos, $value]) {
        $args = [$l, "dc=example,dc=com", "(objectClass=*)", [], 0, 0, 0, 0];
        $args[$pos - 1] = $value;
        try {
            $f(...$args);
        } catch (ValueError $e) {
            echo $e->getMessage(), "\n";
        }
    }
}
?>
--EXPECT--
ldap_search(): Argument #5 ($attributes_only) must be between -2147483648 and 2147483647
ldap_search(): Argument #6 ($sizelimit) must be between -2147483648 and 2147483647
ldap_search(): Argument #7 ($timelimit) must be between -2147483648 and 2147483647
ldap_search(): Argument #8 ($deref) must be between -2147483648 and 2147483647
ldap_list(): Argument #5 ($attributes_only) must be between -2147483648 and 2147483647
ldap_list(): Argument #6 ($sizelimit) must be between -2147483648 and 2147483647
ldap_list(): Argument #7 ($timelimit) must be between -2147483648 and 2147483647
ldap_list(): Argument #8 ($deref) must be between -2147483648 and 2147483647
ldap_read(): Argument #5 ($attributes_only) must be between -2147483648 and 2147483647
ldap_read(): Argument #6 ($sizelimit) must be between -2147483648 and 2147483647
ldap_read(): Argument #7 ($timelimit) must be between -2147483648 and 2147483647
ldap_read(): Argument #8 ($deref) must be between -2147483648 and 2147483647
