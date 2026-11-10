--TEST--
iconv_mime_decode(): unterminated encoded-word and trailing CR at the end of the input
--EXTENSIONS--
iconv
--FILE--
<?php
$m = ICONV_MIME_DECODE_CONTINUE_ON_ERROR;
foreach (["abc =?UTF-8?B?w6k", "abc =?UTF-8", "abc =", "abc =?UTF-8?B?w6k=?= tail"] as $x) {
    var_dump(iconv_mime_decode($x, $m, "UTF-8"));
}
var_dump(iconv_mime_decode("Subject: a\r", 0, "UTF-8"));
var_dump(iconv_mime_decode("Subject: a\r", $m, "UTF-8"));
var_dump(iconv_mime_decode("Subject: a\r\n", 0, "UTF-8"));
?>
--EXPECT--
string(17) "abc =?UTF-8?B?w6k"
string(11) "abc =?UTF-8"
string(5) "abc ="
string(11) "abc é tail"
string(10) "Subject: a"
string(10) "Subject: a"
string(10) "Subject: a"
