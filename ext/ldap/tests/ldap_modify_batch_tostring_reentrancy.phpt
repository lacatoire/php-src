--TEST--
ldap_modify_batch(): __toString() that frees the values array or closes the connection
--EXTENSIONS--
ldap
--FILE--
<?php
$l = ldap_connect("ldap://127.0.0.1:3333");

class Ev {
    function __toString(): string {
        global $vals;
        $vals = null;
        return "first";
    }
}
$vals = [new Ev, "second", "third"];
$mods = [["attrib" => "description", "modtype" => LDAP_MODIFY_BATCH_ADD, "values" => &$vals]];
try {
    var_dump(@ldap_modify_batch($l, "cn=x,dc=example,dc=com", $mods));
} catch (Error $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

class S {
    function __toString(): string {
        global $l;
        ldap_close($l);
        return "v";
    }
}
$l = ldap_connect("ldap://127.0.0.1:3333");
try {
    ldap_modify_batch($l, "cn=x,dc=example,dc=com", [["attrib" => "description", "modtype" => LDAP_MODIFY_BATCH_ADD, "values" => [new S]]]);
} catch (Error $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
echo "survived\n";
?>
--EXPECT--
bool(false)
Error: LDAP connection has already been closed
survived
