--TEST--
ldap_set_option(): a __toString() that closes the connection
--EXTENSIONS--
ldap
--FILE--
<?php
$l = ldap_connect("ldap://127.0.0.1:3899");
class S {
    function __toString(): string {
        global $l;
        ldap_close($l);
        return "v";
    }
}
try {
    var_dump(ldap_set_option($l, LDAP_OPT_ERROR_STRING, new S));
} catch (Error $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
echo "survived\n";
?>
--EXPECT--
Error: LDAP connection has already been closed
survived
