--TEST--
mb_convert_encoding() deprecates the pseudo-encodings given as from_encoding
--EXTENSIONS--
mbstring
--FILE--
<?php

foreach (['HTML-ENTITIES', 'BASE64', 'UUENCODE', 'Quoted-Printable'] as $encoding) {
    echo "$encoding as string\n";
    mb_convert_encoding("abc", "UTF-8", $encoding);
    echo "$encoding as array\n";
    mb_convert_encoding("abc", "UTF-8", [$encoding]);
}

?>
--EXPECTF--
HTML-ENTITIES as string

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
HTML-ENTITIES as array

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
BASE64 as string

Deprecated: mb_convert_encoding(): Handling Base64 via mbstring is deprecated; use base64_encode/base64_decode instead in %s on line %d
BASE64 as array

Deprecated: mb_convert_encoding(): Handling Base64 via mbstring is deprecated; use base64_encode/base64_decode instead in %s on line %d
UUENCODE as string

Deprecated: mb_convert_encoding(): Handling Uuencode via mbstring is deprecated; use convert_uuencode/convert_uudecode instead in %s on line %d
UUENCODE as array

Deprecated: mb_convert_encoding(): Handling Uuencode via mbstring is deprecated; use convert_uuencode/convert_uudecode instead in %s on line %d
Quoted-Printable as string

Deprecated: mb_convert_encoding(): Handling QPrint via mbstring is deprecated; use quoted_printable_encode/quoted_printable_decode instead in %s on line %d
Quoted-Printable as array

Deprecated: mb_convert_encoding(): Handling QPrint via mbstring is deprecated; use quoted_printable_encode/quoted_printable_decode instead in %s on line %d
