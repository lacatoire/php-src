--TEST--
sodium_base642bin(), sodium_hex2bin() and sodium_bin2base64(): NUL bytes in the input and the variant identifier
--EXTENSIONS--
sodium
--SKIPIF--
<?php if (PHP_INT_SIZE != 8) die("skip this test is for 64bit platform only"); ?>
--FILE--
<?php

function show(callable $function): void
{
    try {
        var_dump(bin2hex($function()));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}

echo "NUL byte in the input\n";
foreach (['', ' ', " \r\n"] as $ignore) {
    show(fn() => sodium_base642bin("AQ\0ID", SODIUM_BASE64_VARIANT_ORIGINAL, $ignore));
    show(fn() => sodium_hex2bin("00\0ff", $ignore));
}
show(fn() => sodium_base642bin("AQ\0ID", SODIUM_BASE64_VARIANT_ORIGINAL));
show(fn() => sodium_hex2bin("00\0ff"));

echo "Characters to ignore still work\n";
show(fn() => sodium_base642bin("AQ ID", SODIUM_BASE64_VARIANT_ORIGINAL, ' '));
show(fn() => sodium_hex2bin("00 ff", ' '));

echo "Variant identifier\n";
foreach ([4294967297, 4294967296, 4294967299, 8589934593, -4294967295] as $variant) {
    show(fn() => sodium_base642bin('AQID', $variant));
    show(fn() => sodium_bin2base64("\x01\x02\x03", $variant));
}
show(fn() => sodium_base642bin('AQID', SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING));
show(fn() => sodium_bin2base64("\x01\x02\x03", SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING));

?>
--EXPECT--
NUL byte in the input
SodiumException: sodium_base642bin(): Argument #1 ($string) must be a valid base64 string
SodiumException: sodium_hex2bin(): Argument #1 ($string) must be a valid hexadecimal string
SodiumException: sodium_base642bin(): Argument #1 ($string) must be a valid base64 string
SodiumException: sodium_hex2bin(): Argument #1 ($string) must be a valid hexadecimal string
SodiumException: sodium_base642bin(): Argument #1 ($string) must be a valid base64 string
SodiumException: sodium_hex2bin(): Argument #1 ($string) must be a valid hexadecimal string
SodiumException: sodium_base642bin(): Argument #1 ($string) must be a valid base64 string
SodiumException: sodium_hex2bin(): Argument #1 ($string) must be a valid hexadecimal string
Characters to ignore still work
string(6) "010203"
string(4) "00ff"
Variant identifier
SodiumException: sodium_base642bin(): Argument #2 ($id) must be a valid base64 variant identifier
SodiumException: sodium_bin2base64(): Argument #2 ($id) must be a valid base64 variant identifier
SodiumException: sodium_base642bin(): Argument #2 ($id) must be a valid base64 variant identifier
SodiumException: sodium_bin2base64(): Argument #2 ($id) must be a valid base64 variant identifier
SodiumException: sodium_base642bin(): Argument #2 ($id) must be a valid base64 variant identifier
SodiumException: sodium_bin2base64(): Argument #2 ($id) must be a valid base64 variant identifier
SodiumException: sodium_base642bin(): Argument #2 ($id) must be a valid base64 variant identifier
SodiumException: sodium_bin2base64(): Argument #2 ($id) must be a valid base64 variant identifier
SodiumException: sodium_base642bin(): Argument #2 ($id) must be a valid base64 variant identifier
SodiumException: sodium_bin2base64(): Argument #2 ($id) must be a valid base64 variant identifier
string(6) "010203"
string(8) "41514944"
