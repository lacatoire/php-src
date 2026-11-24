--TEST--
spl_autoload_register() without argument when function spl_autoload() is disabled
--INI--
disable_functions=spl_autoload
--FILE--
<?php
try {
    spl_autoload_register();
} catch (Error $e) {
    echo get_class($e), ': ', $e->getMessage(), "\n";
}
var_dump(spl_autoload_functions());
var_dump(class_exists('Missing'));
?>
--EXPECT--
Error: Cannot register the default autoloader when function spl_autoload is disabled
array(0) {
}
bool(false)
