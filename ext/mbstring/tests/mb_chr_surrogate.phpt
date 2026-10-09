--TEST--
mb_chr() rejects surrogate code points in every encoding
--EXTENSIONS--
mbstring
--FILE--
<?php

foreach (['UTF-8', 'UTF-16', 'UTF-16BE', 'UTF-16LE', 'UTF-32', 'UTF-32BE', 'UTF-32LE', 'UCS-2', 'UCS-4'] as $encoding) {
    echo $encoding, ': ';
    var_dump(mb_chr(0xD800, $encoding), mb_chr(0xDFFF, $encoding));
}

// The code points around the surrogate range are still valid.
var_dump(bin2hex(mb_chr(0xD7FF, 'UTF-16BE')), bin2hex(mb_chr(0xE000, 'UTF-16BE')));

?>
--EXPECT--
UTF-8: bool(false)
bool(false)
UTF-16: bool(false)
bool(false)
UTF-16BE: bool(false)
bool(false)
UTF-16LE: bool(false)
bool(false)
UTF-32: bool(false)
bool(false)
UTF-32BE: bool(false)
bool(false)
UTF-32LE: bool(false)
bool(false)
UCS-2: bool(false)
bool(false)
UCS-4: bool(false)
bool(false)
string(4) "d7ff"
string(4) "e000"
