--TEST--
ldap_get_option(): a typed reference that rejects an array does not leak the controls
--EXTENSIONS--
ldap
--FILE--
<?php
class H { public int $x = 0; }
$l = ldap_connect("ldap://127.0.0.1:3899");
ldap_set_option($l, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($l, LDAP_OPT_SERVER_CONTROLS, [["oid" => LDAP_CONTROL_PAGEDRESULTS, "value" => ["size" => 10, "cookie" => ""]]]);
$h = new H;
for ($i = 0; $i < 3; $i++) {
    try {
        ldap_get_option($l, LDAP_OPT_SERVER_CONTROLS, $h->x);
    } catch (TypeError $e) {
        echo $e->getMessage(), "\n";
    }
}
?>
--EXPECT--
Cannot assign array to reference held by property H::$x of type int
Cannot assign array to reference held by property H::$x of type int
Cannot assign array to reference held by property H::$x of type int
