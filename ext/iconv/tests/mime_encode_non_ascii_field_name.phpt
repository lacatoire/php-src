--TEST--
iconv_mime_encode(): a non-ASCII field name is an error
--EXTENSIONS--
iconv
--FILE--
<?php
$o = ["input-charset" => "UTF-8", "output-charset" => "UTF-8"];
var_dump(iconv_mime_encode("Sübject", "x", $o));
var_dump(iconv_mime_encode("Subject", "x", $o));
?>
--EXPECTF--
Notice: iconv_mime_encode(): Detected an illegal character in input string in %s on line %d
bool(false)
string(25) "Subject: =?UTF-8?B?eA==?="
