--TEST--
Bug #69054 (Null dereference in readline_(read|write)_history() without parameters)
--EXTENSIONS--
readline
--INI--
open_basedir="{TMP}"
--ENV--
HOME=/nonexistent-home
--FILE--
<?php readline_read_history(); ?>
==DONE==
--EXPECTF--
Warning: readline_read_history(): open_basedir restriction in effect. File(/nonexistent-home/.history) is not within the allowed path(s): (%s) in %s on line %d
==DONE==
