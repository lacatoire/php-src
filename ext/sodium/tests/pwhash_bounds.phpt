--TEST--
sodium_crypto_pwhash*(): arguments outside the bounds of libsodium are rejected with an argument error
--EXTENSIONS--
sodium
--SKIPIF--
<?php
if (!defined('SODIUM_CRYPTO_PWHASH_SALTBYTES')) print "skip libsodium without argon2";
if (PHP_INT_SIZE != 8) print "skip this test is for 64bit platform only";
?>
--FILE--
<?php

function show(callable $function): void
{
    try {
        var_dump(strlen($function()) > 0);
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}

$salt = str_repeat('s', SODIUM_CRYPTO_PWHASH_SALTBYTES);
$argon2i = SODIUM_CRYPTO_PWHASH_ALG_ARGON2I13;
$argon2id = SODIUM_CRYPTO_PWHASH_ALG_ARGON2ID13;

echo "sodium_crypto_pwhash()\n";
show(fn() => sodium_crypto_pwhash(32, 'password', $salt, 2, 8192, $argon2i));
show(fn() => sodium_crypto_pwhash(32, 'password', $salt, 3, 8192, $argon2i));
show(fn() => sodium_crypto_pwhash(8, 'password', $salt, 2, 8192, $argon2id));
show(fn() => sodium_crypto_pwhash(16, 'password', $salt, 2, 8192, $argon2id));
show(fn() => sodium_crypto_pwhash(32, 'password', $salt, PHP_INT_MAX, 8192, $argon2id));
show(fn() => sodium_crypto_pwhash(32, 'password', $salt, 2, PHP_INT_MAX, $argon2id));

echo "sodium_crypto_pwhash_str()\n";
show(fn() => sodium_crypto_pwhash_str('password', PHP_INT_MAX, 8192));
show(fn() => sodium_crypto_pwhash_str('password', 2, PHP_INT_MAX));

if (defined('SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_SALTBYTES')) {
    echo "sodium_crypto_pwhash_scryptsalsa208sha256()\n";
    $scrypt_salt = str_repeat('s', SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_SALTBYTES);
    foreach ([0, 8, 1 << 40] as $length) {
        show(fn() => sodium_crypto_pwhash_scryptsalsa208sha256(
            $length, 'password', $scrypt_salt,
            SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_MEMLIMIT_INTERACTIVE
        ));
    }
}

?>
--EXPECTF--
sodium_crypto_pwhash()
ValueError: sodium_crypto_pwhash(): Argument #4 ($opslimit) must be greater than or equal to 3
bool(true)
ValueError: sodium_crypto_pwhash(): Argument #1 ($length) must be greater than or equal to 16
bool(true)
ValueError: sodium_crypto_pwhash(): Argument #4 ($opslimit) must be less than or equal to %d
ValueError: sodium_crypto_pwhash(): Argument #5 ($memlimit) must be less than or equal to %d
sodium_crypto_pwhash_str()
ValueError: sodium_crypto_pwhash_str(): Argument #2 ($opslimit) must be less than or equal to %d
ValueError: sodium_crypto_pwhash_str(): Argument #3 ($memlimit) must be less than or equal to %d
sodium_crypto_pwhash_scryptsalsa208sha256()
ValueError: sodium_crypto_pwhash_scryptsalsa208sha256(): Argument #1 ($length) must be greater than 0
ValueError: sodium_crypto_pwhash_scryptsalsa208sha256(): Argument #1 ($length) must be greater than or equal to 16
ValueError: sodium_crypto_pwhash_scryptsalsa208sha256(): Argument #1 ($length) is too large
