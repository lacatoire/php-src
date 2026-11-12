--TEST--
openssl_encrypt()/openssl_decrypt() round trip with GCM-SIV cipher algorithms
--EXTENSIONS--
openssl
--SKIPIF--
<?php
if (!in_array('aes-128-gcm-siv', openssl_get_cipher_methods()))
	die("skip: aes-128-gcm-siv not available");
?>
--FILE--
<?php
foreach (['aes-128-gcm-siv', 'aes-256-gcm-siv'] as $method) {
    $key = str_repeat('k', openssl_cipher_key_length($method));
    $iv = str_repeat('i', openssl_cipher_iv_length($method));
    $ct = openssl_encrypt('hello world', $method, $key, OPENSSL_RAW_DATA, $iv, $tag, 'aad', 16);
    var_dump(strlen($ct), strlen($tag));
    var_dump(openssl_decrypt($ct, $method, $key, OPENSSL_RAW_DATA, $iv, $tag, 'aad'));
    // Wrong AAD must fail authentication
    var_dump(openssl_decrypt($ct, $method, $key, OPENSSL_RAW_DATA, $iv, $tag, 'bad'));
}
?>
--EXPECT--
int(11)
int(16)
string(11) "hello world"
bool(false)
int(11)
int(16)
string(11) "hello world"
bool(false)
