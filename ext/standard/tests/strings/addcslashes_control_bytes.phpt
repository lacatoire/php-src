--TEST--
addcslashes() escapes real BEL/BS control bytes and bytes above 126 as octal
--FILE--
<?php

// The existing addcslashes tests build their input with "\a" and "\b",
// which PHP double-quoted strings do not recognise as escapes: they are
// a literal backslash followed by a letter, not the BEL (0x07) and
// BS (0x08) control bytes. So the '\a'/'\b' cases in
// ext/standard/string.c were never exercised by an actual control byte.
var_dump(addcslashes("\x07", "\0..\377"));
var_dump(addcslashes("\x08", "\0..\377"));

// No existing test pins the octal fallback for a byte above 126.
var_dump(addcslashes("\x7f", "\0..\377"));
var_dump(addcslashes("\xff", "\0..\377"));

?>
--EXPECT--
string(2) "\a"
string(2) "\b"
string(4) "\177"
string(4) "\377"
