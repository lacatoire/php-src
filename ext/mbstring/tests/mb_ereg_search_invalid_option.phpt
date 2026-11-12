--TEST--
mb_ereg_search*(): invalid option leaves the search state untouched
--EXTENSIONS--
mbstring
--SKIPIF--
<?php
error_reporting(E_ALL & ~E_DEPRECATED);
?>
--FILE--
<?php
error_reporting(E_ALL & ~E_DEPRECATED);
mb_ereg_search_init("aaa", "a");
var_dump(mb_ereg_search_getpos());
try {
    mb_ereg_search("a", "q");
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}
var_dump(mb_ereg_search_getpos());
try {
    mb_ereg_search_pos("a", "q");
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}
var_dump(mb_ereg_search_getpos());
try {
    mb_ereg_search_regs("a", "q");
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}
var_dump(mb_ereg_search_getpos());
var_dump(mb_ereg_search());

mb_ereg_search_init("xyz", "x");
try {
    mb_ereg_search_init("abc", "b", "q");
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}
var_dump(mb_ereg_search_getpos());
var_dump(mb_ereg_search());
?>
--EXPECT--
int(0)
Option "q" is not supported
int(0)
Option "q" is not supported
int(0)
Option "q" is not supported
int(0)
bool(true)
Option "q" is not supported
int(0)
bool(true)
