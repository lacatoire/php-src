--TEST--
ldap_list() - parallel search restores the connection options
--EXTENSIONS--
ldap
--SKIPIF--
<?php require_once('skipifbindfailure.inc'); ?>
--FILE--
<?php
require "connect.inc";

$link = ldap_connect_and_bind($uri, $user, $passwd, $protocol_version);
insert_dummy_data($link, $base);

function show($link) {
    ldap_get_option($link, LDAP_OPT_SIZELIMIT, $size);
    ldap_get_option($link, LDAP_OPT_TIMELIMIT, $time);
    ldap_get_option($link, LDAP_OPT_DEREF, $deref);
    echo "size=$size time=$time deref=$deref\n";
}

show($link);
ldap_list([$link], [$base], ["(objectClass=*)"], [], 0, 2, 7, LDAP_DEREF_ALWAYS);
show($link);
?>
--CLEAN--
<?php
include "connect.inc";

$link = ldap_connect_and_bind($uri, $user, $passwd, $protocol_version);
remove_dummy_data($link, $base);
?>
--EXPECT--
size=0 time=0 deref=0
size=0 time=0 deref=0
