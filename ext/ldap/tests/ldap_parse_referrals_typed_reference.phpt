--TEST--
ldap_parse_result()/ldap_parse_reference(): a typed reference that rejects an array throws without leaking
--CREDITS--
Patrick Allaert <patrickallaert@php.net>
# Belgian PHP Testfest 2009
--EXTENSIONS--
ldap
--SKIPIF--
<?php require_once('skipifbindfailure.inc'); ?>
--FILE--
<?php
require "connect.inc";

class H { public int $x = 0; }

$link = ldap_connect_and_bind($uri, $user, $passwd, $protocol_version);
insert_dummy_data($link, $base);
ldap_add($link, "cn=userref,$base", array(
    "objectClass" => array("extensibleObject", "referral"),
    "cn" => "userref",
    "ref" => "cn=userA,$base",
));
ldap_set_option($link, LDAP_OPT_DEREF, LDAP_DEREF_NEVER);

$h = new H;

$result = ldap_search($link, "cn=userref,$base", "(cn=user*)");
try {
    ldap_parse_result($link, $result, $errcode, $dn, $errmsg, $h->x);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

$result = ldap_search($link, $base, "(cn=*)");
$ref = ldap_first_reference($link, $result);
try {
    ldap_parse_reference($link, $ref, $h->x);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
?>
--CLEAN--
<?php
include "connect.inc";

$link = ldap_connect_and_bind($uri, $user, $passwd, $protocol_version);
ldap_set_option($link, LDAP_OPT_SERVER_CONTROLS, array(array("oid" => "2.16.840.1.113730.3.4.2")));
ldap_delete($link, "cn=userref,$base");
remove_dummy_data($link, $base);
?>
--EXPECT--
TypeError: Cannot assign array to reference held by property H::$x of type int
TypeError: Cannot assign array to reference held by property H::$x of type int
