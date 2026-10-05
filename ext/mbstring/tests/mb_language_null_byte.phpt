--TEST--
mb_language() rejects a language name containing a NUL byte
--EXTENSIONS--
mbstring
--FILE--
<?php
$before = mb_language();
try {
    mb_language("Japanese\0junk");
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}
var_dump(mb_language() === $before);
var_dump(ini_get("mbstring.language") === $before);
?>
--EXPECT--
mb_language(): Argument #1 ($language) must not contain any null bytes
bool(true)
bool(true)
