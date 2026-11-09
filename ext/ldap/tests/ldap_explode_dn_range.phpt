--TEST--
ldap_explode_dn(): with_attrib outside the int range is rejected
--EXTENSIONS--
ldap
--FILE--
<?php
foreach ([2**32, 2**32 + 1, PHP_INT_MIN] as $flag) {
    try {
        var_dump(ldap_explode_dn("cn=a,dc=c", $flag));
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}
var_dump(ldap_explode_dn("cn=a,dc=c", 0));
?>
--EXPECT--
ldap_explode_dn(): Argument #2 ($with_attrib) must be between -2147483648 and 2147483647
ldap_explode_dn(): Argument #2 ($with_attrib) must be between -2147483648 and 2147483647
ldap_explode_dn(): Argument #2 ($with_attrib) must be between -2147483648 and 2147483647
array(3) {
  ["count"]=>
  int(2)
  [0]=>
  string(4) "cn=a"
  [1]=>
  string(4) "dc=c"
}
