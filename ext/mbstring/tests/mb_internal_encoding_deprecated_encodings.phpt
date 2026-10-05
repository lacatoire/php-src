--TEST--
mb_internal_encoding() emits deprecations for pseudo-encodings
--EXTENSIONS--
mbstring
--FILE--
<?php
foreach (['BASE64', 'UUENCODE', 'Quoted-Printable', 'HTML-ENTITIES'] as $name) {
    var_dump(mb_internal_encoding($name));
}
try {
    mb_internal_encoding('unknown');
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}
?>
--EXPECTF--
Deprecated: mb_internal_encoding(): Handling Base64 via mbstring is deprecated; use base64_encode/base64_decode instead in %s on line %d
bool(true)

Deprecated: mb_internal_encoding(): Handling Uuencode via mbstring is deprecated; use convert_uuencode/convert_uudecode instead in %s on line %d
bool(true)

Deprecated: mb_internal_encoding(): Handling QPrint via mbstring is deprecated; use quoted_printable_encode/quoted_printable_decode instead in %s on line %d
bool(true)

Deprecated: mb_internal_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
bool(true)
mb_internal_encoding(): Argument #1 ($encoding) must be a valid encoding, "unknown" given
