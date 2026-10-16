--TEST--
gettext(): the message must not contain null bytes
--EXTENSIONS--
gettext
--FILE--
<?php
try {
    gettext("hello\0world");
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}
var_dump(gettext("hello"));
?>
--EXPECT--
gettext(): Argument #1 ($message) must not contain any null bytes
string(5) "hello"
