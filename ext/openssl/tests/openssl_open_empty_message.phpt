--TEST--
openssl_open() opens a valid sealed empty message
--EXTENSIONS--
openssl
--SKIPIF--
<?php
if (!@openssl_pkey_new(['private_key_bits' => 2048])) die("skip cannot create private key");
?>
--FILE--
<?php
$key = openssl_pkey_new(['private_key_bits' => 2048]);
$pub = openssl_pkey_get_details($key)['key'];

foreach (['', 'a'] as $msg) {
    var_dump(openssl_seal($msg, $sealed, $ekeys, [$pub], 'AES-128-CBC', $iv));
    var_dump(openssl_open($sealed, $plain, $ekeys[0], $key, 'AES-128-CBC', $iv));
    var_dump($plain);
    unset($plain);
}
?>
--EXPECT--
int(16)
bool(true)
string(0) ""
int(16)
bool(true)
string(1) "a"
