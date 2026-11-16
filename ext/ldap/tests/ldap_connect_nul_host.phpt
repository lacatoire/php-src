--TEST--
ldap_connect(): a host containing a NUL byte is rejected
--EXTENSIONS--
ldap
--FILE--
<?php
foreach (["127.0.0.1\0evil.example", "ldap://127.0.0.1:1\0evil.example"] as $host) {
    try {
        var_dump(ldap_connect($host));
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}
var_dump(ldap_connect("ldap://127.0.0.1:1") instanceof LDAP\Connection);
?>
--EXPECT--
ldap_connect(): Argument #1 ($uri) must not contain any null bytes
ldap_connect(): Argument #1 ($uri) must not contain any null bytes
bool(true)
