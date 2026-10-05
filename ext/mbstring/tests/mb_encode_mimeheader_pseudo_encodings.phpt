--TEST--
mb_encode_mimeheader() rejects non-charset pseudo-encodings as $charset
--EXTENSIONS--
mbstring
--FILE--
<?php
foreach (["UUENCODE", "BASE64", "HTML-ENTITIES", "7bit", "8bit", "Quoted-Printable"] as $charset) {
    try {
        var_dump(@mb_encode_mimeheader("é", $charset, "B"));
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}
var_dump(mb_encode_mimeheader("é", "UTF-8", "B"));
?>
--EXPECT--
mb_encode_mimeheader(): Argument #2 ($charset) "UUENCODE" cannot be used for MIME header encoding
mb_encode_mimeheader(): Argument #2 ($charset) "BASE64" cannot be used for MIME header encoding
mb_encode_mimeheader(): Argument #2 ($charset) "HTML-ENTITIES" cannot be used for MIME header encoding
mb_encode_mimeheader(): Argument #2 ($charset) "7bit" cannot be used for MIME header encoding
mb_encode_mimeheader(): Argument #2 ($charset) "8bit" cannot be used for MIME header encoding
mb_encode_mimeheader(): Argument #2 ($charset) "Quoted-Printable" cannot be used for MIME header encoding
string(16) "=?UTF-8?B?w6k=?="
