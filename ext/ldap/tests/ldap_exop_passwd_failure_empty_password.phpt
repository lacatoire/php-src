--TEST--
ldap_exop_passwd() - failure with an empty new password
--EXTENSIONS--
ldap
--SKIPIF--
<?php require_once('skipifbindfailure.inc'); ?>
--FILE--
<?php
require "connect.inc";

$link = ldap_connect_and_bind($uri, $user, $passwd, $protocol_version);

var_dump(ldap_exop_passwd($link, "cn=nosuchuser,$base"));
var_dump(ldap_exop_passwd($link, "cn=nosuchuser,$base", "", "new"));
?>
--EXPECTF--
Warning: ldap_exop_passwd(): Passwd modify extended operation failed: %s (%d) in %s on line %d
bool(false)

Warning: ldap_exop_passwd(): Passwd modify extended operation failed: %s (%d) in %s on line %d
bool(false)
