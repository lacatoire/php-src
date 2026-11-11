--TEST--
ldap_escape(): LDAP_ESCAPE_DN escapes the NUL byte
--EXTENSIONS--
ldap
--FILE--
<?php
var_dump(ldap_escape("a\0b", "", LDAP_ESCAPE_DN));
var_dump(ldap_escape("a\0b", "", LDAP_ESCAPE_FILTER));
var_dump(ldap_escape("a\0b", "", LDAP_ESCAPE_DN | LDAP_ESCAPE_FILTER));
var_dump(ldap_escape("a,b\0c", "", LDAP_ESCAPE_DN));
?>
--EXPECT--
string(5) "a\00b"
string(5) "a\00b"
string(5) "a\00b"
string(9) "a\2cb\00c"
