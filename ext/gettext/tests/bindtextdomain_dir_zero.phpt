--TEST--
bindtextdomain() treats a directory named "0" as a path, not as the current directory
--EXTENSIONS--
gettext
--FILE--
<?php
$base = __DIR__ . '/bindtextdomain_dir_zero';
mkdir($base);
mkdir("$base/0");
chdir($base);
var_dump(bindtextdomain("app", "0") === realpath("$base/0"));
var_dump(bindtextdomain("app", "") === realpath($base));
?>
--CLEAN--
<?php
$base = __DIR__ . '/bindtextdomain_dir_zero';
@rmdir("$base/0");
@rmdir($base);
?>
--EXPECT--
bool(true)
bool(true)
