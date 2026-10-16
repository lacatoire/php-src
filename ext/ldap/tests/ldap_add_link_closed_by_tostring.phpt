--TEST--
ldap_add(), ldap_modify(): a __toString() value that closes the connection
--EXTENSIONS--
ldap
--FILE--
<?php
class S {
    function __toString(): string {
        global $l;
        ldap_close($l);
        return "v";
    }
}
foreach (['ldap_add', 'ldap_add_ext', 'ldap_modify', 'ldap_mod_add', 'ldap_mod_replace', 'ldap_mod_del'] as $f) {
    $l = ldap_connect("ldap://127.0.0.1:3333");
    try {
        var_dump($f($l, "cn=a,dc=x", ["a" => new S]));
    } catch (Error $e) {
        echo $f, ': ', $e::class, ': ', $e->getMessage(), "\n";
    }
}
echo "survived\n";
?>
--EXPECT--
ldap_add: Error: LDAP connection has already been closed
ldap_add_ext: Error: LDAP connection has already been closed
ldap_modify: Error: LDAP connection has already been closed
ldap_mod_add: Error: LDAP connection has already been closed
ldap_mod_replace: Error: LDAP connection has already been closed
ldap_mod_del: Error: LDAP connection has already been closed
survived
