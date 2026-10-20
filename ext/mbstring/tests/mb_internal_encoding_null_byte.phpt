--TEST--
mb_internal_encoding() rejects encoding names containing null bytes
--EXTENSIONS--
mbstring
--FILE--
<?php
try {
    var_dump(mb_internal_encoding("UTF-8\0junk"));
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}
var_dump(mb_internal_encoding());
?>
--EXPECT--
mb_internal_encoding(): Argument #1 ($encoding) must not contain any null bytes
string(5) "UTF-8"
