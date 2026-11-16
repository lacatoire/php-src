--TEST--
strripos() finds a one-byte needle whose byte is >= 0x80
--FILE--
<?php

var_dump(strripos("a\xF5", "\xF5"));
var_dump(strripos("\xF5a\xF5b", "\xF5"));
var_dump(strripos("abc", "\xF5"));

$mismatches = 0;
for ($byte = 128; $byte < 256; $byte++) {
    $needle = chr($byte);
    $haystack = "ab" . $needle . "cd";
    if (strripos($haystack, $needle) !== strrpos($haystack, $needle)) {
        $mismatches++;
    }
}
var_dump($mismatches);

?>
--EXPECT--
int(1)
int(2)
bool(false)
int(0)
