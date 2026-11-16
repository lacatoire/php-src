--TEST--
openssl_digest() and openssl_pbkdf2() warn on extendable-output digests
--EXTENSIONS--
openssl
--SKIPIF--
<?php
if (!in_array("shake128", openssl_get_md_methods())) die("skip shake128 not available");
?>
--FILE--
<?php
var_dump(openssl_digest("abc", "shake128"));
var_dump(openssl_digest("abc", "shake256", true));
var_dump(openssl_pbkdf2("p", "s", 16, 10, "shake128"));
?>
--EXPECTF--
Warning: openssl_digest(): Extendable-output digest algorithms are not supported in %s on line %d
bool(false)

Warning: openssl_digest(): Extendable-output digest algorithms are not supported in %s on line %d
bool(false)

Warning: openssl_pbkdf2(): Extendable-output digest algorithms are not supported in %s on line %d
bool(false)
