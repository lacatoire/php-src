--TEST--
metaphone() does not read its code table out of bounds under a single-byte locale
--SKIPIF--
<?php
if (setlocale(LC_CTYPE, "de_DE.ISO-8859-1", "de_DE.ISO8859-1", "en_US.ISO-8859-1", "fr_FR.ISO-8859-1") === false) {
    die("skip no ISO-8859-1 locale available");
}
?>
--FILE--
<?php
error_reporting(E_ALL & ~E_DEPRECATED);
setlocale(LC_CTYPE, "de_DE.ISO-8859-1", "de_DE.ISO8859-1", "en_US.ISO-8859-1", "fr_FR.ISO-8859-1");
var_dump(metaphone("W\xe9"));
var_dump(metaphone("C\xe9"));
var_dump(metaphone("Wa"));
?>
--EXPECT--
string(0) ""
string(1) "K"
string(1) "W"
