--TEST--
The $state parameters of the generichash and secretstream functions are sensitive
--EXTENSIONS--
sodium
--FILE--
<?php

$key = str_repeat('K', 32);
$state = sodium_crypto_generichash_init($key);
set_error_handler(function () use ($key) {
    foreach (debug_backtrace() as $frame) {
        if (($frame['function'] ?? '') === 'sodium_crypto_generichash_update') {
            var_dump($frame['args'][0] instanceof SensitiveParameterValue);
        }
    }
    return true;
});
sodium_crypto_generichash_update($state, null);
restore_error_handler();

foreach ([
    'sodium_crypto_generichash_update',
    'sodium_crypto_generichash_final',
    'sodium_crypto_secretstream_xchacha20poly1305_push',
    'sodium_crypto_secretstream_xchacha20poly1305_pull',
    'sodium_crypto_secretstream_xchacha20poly1305_rekey',
] as $function) {
    $attributes = (new ReflectionFunction($function))->getParameters()[0]->getAttributes(SensitiveParameter::class);
    echo $function, ': ', count($attributes), "\n";
}

?>
--EXPECT--
bool(true)
sodium_crypto_generichash_update: 1
sodium_crypto_generichash_final: 1
sodium_crypto_secretstream_xchacha20poly1305_push: 1
sodium_crypto_secretstream_xchacha20poly1305_pull: 1
sodium_crypto_secretstream_xchacha20poly1305_rekey: 1
