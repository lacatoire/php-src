--TEST--
The optimizer does not fold str_replace() and str_ireplace() calls with a huge result
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.optimization_level=-1
memory_limit=128M
--EXTENSIONS--
opcache
--FILE--
<?php
function replace_ci() {
    return str_ireplace("a", str_repeat("a", 20000), str_repeat("a", 20000));
}
function replace_array() {
    return str_replace(["a", "b"], str_repeat("a", 300), str_repeat("a", 300));
}
function replace_small() {
    return str_replace("a", "bb", "banana");
}
echo "loaded\n";
echo replace_small(), "\n";
?>
--EXPECT--
loaded
bbbnbbnbb
