--TEST--
ldap_first_attribute() - restarting the iteration before its end
--EXTENSIONS--
ldap
--SKIPIF--
<?php require_once('skipifbindfailure.inc'); ?>
--FILE--
<?php
require "connect.inc";

$link = ldap_connect_and_bind($uri, $user, $passwd, $protocol_version);
insert_dummy_data($link, $base);

$result = ldap_search($link, "cn=userA,$base", "(cn=*)");
$entry = ldap_first_entry($link, $result);
for ($i = 0; $i < 3; $i++) {
    var_dump(ldap_first_attribute($link, $entry) !== false);
}
?>
--CLEAN--
<?php
include "connect.inc";

$link = ldap_connect_and_bind($uri, $user, $passwd, $protocol_version);
remove_dummy_data($link, $base);
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
