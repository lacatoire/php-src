--TEST--
posix_getpwnam() and posix_getgrnam() reject names containing a NUL byte
--EXTENSIONS--
posix
--FILE--
<?php
$pw = posix_getpwuid(posix_geteuid());
$gr = posix_getgrgid(posix_getegid());
var_dump(posix_getpwnam($pw['name'])['name'] === $pw['name']);
var_dump(posix_getgrnam($gr['name'])['name'] === $gr['name']);
var_dump(posix_getpwnam($pw['name'] . "\0x"));
var_dump(posix_getgrnam($gr['name'] . "\0x"));
?>
--EXPECT--
bool(true)
bool(true)
bool(false)
bool(false)
