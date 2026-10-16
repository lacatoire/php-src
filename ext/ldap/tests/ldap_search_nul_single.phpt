--TEST--
ldap_search(), ldap_list(), ldap_read(): a NUL byte in base or filter is rejected for a single connection
--EXTENSIONS--
ldap
--FILE--
<?php
$l = ldap_connect("ldap://127.0.0.1:1");
foreach (["ldap_search", "ldap_list", "ldap_read"] as $f) {
    foreach ([["dc=example,dc=com\0,cn=nosuch", "(cn=p1)"], ["dc=example,dc=com", "(cn=p0)\0(objectClass=nothing)"]] as [$base, $filter]) {
        try {
            $f($l, $base, $filter);
        } catch (ValueError $e) {
            echo $e->getMessage(), "\n";
        }
        try {
            $f([$l], $base, $filter);
        } catch (ValueError $e) {
            echo "parallel: ", $e->getMessage(), "\n";
        }
    }
}
?>
--EXPECT--
ldap_search(): Argument #2 ($base) must not contain null bytes
parallel: ldap_search(): Argument #2 ($base) must not contain null bytes
ldap_search(): Argument #3 ($filter) must not contain null bytes
parallel: ldap_search(): Argument #3 ($filter) must not contain null bytes
ldap_list(): Argument #2 ($base) must not contain null bytes
parallel: ldap_list(): Argument #2 ($base) must not contain null bytes
ldap_list(): Argument #3 ($filter) must not contain null bytes
parallel: ldap_list(): Argument #3 ($filter) must not contain null bytes
ldap_read(): Argument #2 ($base) must not contain null bytes
parallel: ldap_read(): Argument #2 ($base) must not contain null bytes
ldap_read(): Argument #3 ($filter) must not contain null bytes
parallel: ldap_read(): Argument #3 ($filter) must not contain null bytes
