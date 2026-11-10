--TEST--
openssl_password_verify() and password_verify() return false for malformed argon2 hashes
--EXTENSIONS--
openssl
--SKIPIF--
<?php
if (!function_exists('openssl_password_verify')) {
    die("skip No openssl_password_verify");
}
if (!defined('PASSWORD_ARGON2_PROVIDER')) {
    die("skip No openssl argon2 support");
}
?>
--FILE--
<?php

$mk = fn($m, $hash = "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA")
    => "\$argon2id\$v=19\$m=$m,t=1,p=1\$c2FsdHNhbHRzYWx0\$$hash";

var_dump(password_verify("pw", $mk(8)));
var_dump(password_verify("pw", $mk(4)));
var_dump(password_verify("pw", $mk(1)));
var_dump(password_verify("pw", $mk(4294967295)));
var_dump(password_verify("pw", '$argon2id$v=19$m=64,t=1,p=1$c2FsdA$'));
var_dump(password_verify("pw", '$argon2id$v=19$m=64,t=1,p=1$$AAAA'));
var_dump(password_verify("pw", '$argon2id$v=19$m=64,t=0,p=1$c2FsdHNhbHRzYWx0$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA'));
var_dump(password_verify("pw", '$argon2id$v=19$m=64,t=1,p=0$c2FsdHNhbHRzYWx0$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA'));
var_dump(openssl_password_verify('argon2id', "pw", $mk(4)));
var_dump(openssl_password_verify('argon2i', "pw", str_replace('argon2id', 'argon2i', $mk(1))));

$hash = openssl_password_hash('argon2id', 'pw');
var_dump(openssl_password_verify('argon2id', 'pw', $hash));
var_dump(openssl_password_verify('argon2id', 'other', $hash));

?>
--EXPECT--
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(true)
bool(false)
