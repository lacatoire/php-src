--TEST--
Bug #48645 (mb_convert_encoding() doesn't understand hexadecimal html-entities)
--EXTENSIONS--
mbstring
--FILE--
<?php
var_dump(bin2hex(mb_convert_encoding("&#x0;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x1;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x2;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x3;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x4;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x5;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x6;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x7;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x8;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x9;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#xA;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#xB;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#xC;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#xD;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#xE;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#xF;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#xa;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#xb;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#xc;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#xd;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#xe;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#xf;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x/;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x:;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x@;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x`;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#xG;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#xg;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X0;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X1;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X2;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X3;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X4;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X5;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X6;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X7;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X8;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X9;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#XA;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#XB;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#XC;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#XD;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#XE;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#XF;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#Xa;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#Xb;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#Xc;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#Xd;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#Xe;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#Xf;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X/;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X:;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X@;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X`;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#XG;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#Xg;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#0;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#1;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#2;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#3;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#4;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#5;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#6;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#7;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#8;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#9;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#/;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#:;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x10ffff;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#x110000;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X10ffff;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#X110000;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#1114111;", "UTF-8", "HTML-ENTITIES")));
var_dump(bin2hex(mb_convert_encoding("&#1114112;", "UTF-8", "HTML-ENTITIES")));
?>
--EXPECTF--
Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "00"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "01"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "02"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "03"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "04"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "05"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "06"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "07"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "08"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "09"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0a"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0c"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0d"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0e"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0f"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0a"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0c"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0d"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0e"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0f"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(10) "2623782f3b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(10) "2623783a3b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(10) "262378403b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(10) "262378603b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(10) "262378473b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(10) "262378673b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(8) "2623783b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "00"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "01"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "02"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "03"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "04"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "05"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "06"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "07"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "08"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "09"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0a"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0c"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0d"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0e"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0f"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0a"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0c"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0d"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0e"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "0f"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(10) "2623582f3b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(10) "2623583a3b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(10) "262358403b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(10) "262358603b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(10) "262358473b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(10) "262358673b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(8) "2623583b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "00"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "01"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "02"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "03"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "04"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "05"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "06"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "07"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "08"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(2) "09"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(8) "26232f3b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(8) "26233a3b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(6) "26233b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(8) "f48fbfbf"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(20) "2623783131303030303b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(8) "f48fbfbf"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(20) "2623583131303030303b"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(8) "f48fbfbf"

Deprecated: mb_convert_encoding(): Handling HTML entities via mbstring is deprecated; use htmlspecialchars, htmlentities, or mb_encode_numericentity/mb_decode_numericentity instead in %s on line %d
string(20) "2623313131343131323b"
