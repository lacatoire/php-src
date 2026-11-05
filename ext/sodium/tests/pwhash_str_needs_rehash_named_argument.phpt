--TEST--
sodium_crypto_pwhash_str_needs_rehash(): the first parameter is named $hash
--EXTENSIONS--
sodium
--SKIPIF--
<?php
if (!defined('SODIUM_CRYPTO_PWHASH_SALTBYTES')) print "skip libsodium without argon2";
?>
--FILE--
<?php

$hash = sodium_crypto_pwhash_str('secret', SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE, SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE);

var_dump(sodium_crypto_pwhash_str_needs_rehash(
    hash: $hash,
    opslimit: SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
    memlimit: SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE,
));
var_dump(sodium_crypto_pwhash_str_needs_rehash(
    $hash,
    SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE,
    SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE,
));
echo (new ReflectionFunction('sodium_crypto_pwhash_str_needs_rehash'))->getParameters()[0]->getName(), "\n";

?>
--EXPECT--
bool(false)
bool(true)
hash
