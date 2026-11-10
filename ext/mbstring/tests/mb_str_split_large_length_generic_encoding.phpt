--TEST--
mb_str_split() with a large length on encodings using the generic path
--EXTENSIONS--
mbstring
--INI--
memory_limit=64M
--FILE--
<?php
foreach (["UTF-16BE", "UTF-16", "GB18030", "ISO-2022-JP", "UTF-7"] as $enc) {
    var_dump(count(mb_str_split("abc", 200000000, $enc)));
}
?>
--EXPECT--
int(1)
int(1)
int(1)
int(1)
int(1)
