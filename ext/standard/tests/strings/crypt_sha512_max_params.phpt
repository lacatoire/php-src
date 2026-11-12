--TEST--
crypt(): SHA-512 with a 9-digit rounds count and a 16-char salt must not fail
--SKIPIF--
<?php
if (!getenv('RUN_RESOURCE_HEAVY_TESTS')) die('skip resource-heavy test');
if (getenv('SKIP_SLOW_TESTS')) die('skip slow test');
?>
--FILE--
<?php
// The $6$ output buffer used to be sized to PHP_MAX_SALT_LEN (123), the
// maximum *salt* length, not the maximum *output* length. At the maximum
// (9-digit rounds + 16-char salt) the formatted hash needs 123 characters
// plus a terminating NUL (124), so it hit the buflen guard and returned
// "*0" instead of a valid hash.
var_dump(strlen(crypt("password", '$6$rounds=99999999$1234567890123456$')));
var_dump(strlen(crypt("password", '$6$rounds=100000000$1234567890123456$')));
?>
--EXPECT--
int(122)
int(123)
