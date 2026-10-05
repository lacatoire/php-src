--TEST--
mb_trim(), mb_ltrim() and mb_rtrim(): invalid bytes are handled the same whether or not anything is stripped
--EXTENSIONS--
mbstring
--FILE--
<?php
foreach (["\xffab", " \xffab", "a\xff "] as $s) {
    echo bin2hex($s), " trim=", bin2hex(mb_trim($s)), " ltrim=", bin2hex(mb_ltrim($s)), " rtrim=", bin2hex(mb_rtrim($s)), "\n";
}
?>
--EXPECT--
ff6162 trim=3f6162 ltrim=3f6162 rtrim=3f6162
20ff6162 trim=3f6162 ltrim=3f6162 rtrim=203f6162
61ff20 trim=613f ltrim=613f20 rtrim=613f
