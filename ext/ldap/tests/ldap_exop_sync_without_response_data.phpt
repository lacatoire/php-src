--TEST--
ldap_exop_sync() - $response_data and $response_oid can be omitted
--EXTENSIONS--
ldap
--SKIPIF--
<?php require_once('skipifbindfailure.inc'); ?>
--FILE--
<?php
require "connect.inc";

$link = ldap_connect_and_bind($uri, $user, $passwd, $protocol_version);

// Who Am I?
var_dump(ldap_exop_sync($link, LDAP_EXOP_WHO_AM_I));
?>
--EXPECT--
bool(true)
