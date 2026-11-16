--TEST--
Memory leak on sodium_crypto_sign_ed25519_pk_to_curve25519() failure
--EXTENSIONS--
sodium
--SKIPIF--
<?php
if (version_compare(SODIUM_LIBRARY_VERSION, '1.0.14', '<')) print "skip libsodium before 1.0.14 does not reject small-order points";
?>
--FILE--
<?php

try {
    sodium_crypto_sign_ed25519_pk_to_curve25519(str_repeat("\x00", SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES));
} catch (SodiumException $e) {
    echo $e::class, ': ', $e->getMessage(), PHP_EOL;
}

?>
--EXPECT--
SodiumException: conversion failed
