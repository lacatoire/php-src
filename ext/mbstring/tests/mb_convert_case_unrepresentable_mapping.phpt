--TEST--
Case conversion keeps characters whose mapping is not representable in the encoding
--EXTENSIONS--
mbstring
--FILE--
<?php
foreach ([["\xb5", "ISO-8859-1"], ["\xff", "ISO-8859-1"], ["\xb5\xff", "Windows-1252"]] as [$s, $e]) {
    echo bin2hex($s), " $e upper=", bin2hex(mb_strtoupper($s, $e)), " lower=", bin2hex(mb_strtolower($s, $e)), "\n";
}
echo bin2hex(mb_convert_case("\xb5abc", MB_CASE_UPPER, "ISO-8859-1")), "\n";
echo bin2hex(mb_convert_case("\xb5abc", MB_CASE_UPPER_SIMPLE, "ISO-8859-1")), "\n";
echo bin2hex(mb_convert_case("\xb5abc", MB_CASE_TITLE, "ISO-8859-1")), "\n";
echo bin2hex(mb_convert_case("\xb5ABC", MB_CASE_FOLD, "ISO-8859-1")), "\n";
echo bin2hex(mb_ucfirst("\xb5abc", "ISO-8859-1")), "\n";
mb_substitute_character("none");
echo bin2hex(mb_strtoupper("\xb5a", "ISO-8859-1")), "\n";
// Representable mappings are still applied
echo bin2hex(mb_strtoupper("\xe9", "ISO-8859-1")), "\n";
echo mb_strtoupper("µÿ", "UTF-8"), "\n";
?>
--EXPECT--
b5 ISO-8859-1 upper=b5 lower=b5
ff ISO-8859-1 upper=ff lower=ff
b5ff Windows-1252 upper=b59f lower=b5ff
b5414243
b5414243
b5616263
b5616263
b5616263
b541
c9
ΜŸ
