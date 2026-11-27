--TEST--
wordwrap() detects an existing break containing a NUL byte in a binary safe way
--FILE--
<?php
var_dump(bin2hex(wordwrap("aaaa\0Yb cc", 6, "\0X")));
var_dump(bin2hex(wordwrap("aaaa\1Yb cc", 6, "\1X")));
var_dump(bin2hex(wordwrap("aa\0Xbbbb cc", 6, "\0X")));
?>
--EXPECT--
string(22) "6161616100596200586363"
string(22) "6161616101596201586363"
string(24) "616100586262626200586363"
