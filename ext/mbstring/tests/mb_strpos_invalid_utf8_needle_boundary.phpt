--TEST--
mb_strpos()/mb_strrpos()/mb_strstr() must not match an invalid UTF-8 needle inside a multibyte character
--EXTENSIONS--
mbstring
--FILE--
<?php
var_dump(mb_strpos("あい", "\x81\x82"));
var_dump(mb_strstr("あい", "\x81\x82"));
var_dump(mb_strrpos("あい", "\x81"));
var_dump(mb_strrpos("あい", "\x81", -1));
var_dump(mb_strrpos("あい", "\x81", 1));
var_dump(mb_substr_count("あい", "\x81\x82"));
// Still found at a character boundary after skipping the misaligned match
var_dump(mb_strpos("あ\x81\x82い", "\x81\x82"));
var_dump(mb_strrpos("あ\x81\x82い", "\x81\x82"));
var_dump(mb_strpos("あい", "い"));
?>
--EXPECT--
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
int(0)
int(1)
int(1)
int(1)
