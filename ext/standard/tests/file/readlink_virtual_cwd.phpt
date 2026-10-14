--TEST--
readlink() resolves relative paths against the virtual current directory
--FILE--
<?php
$base = __DIR__ . "/readlink_virtual_cwd_dir";
mkdir("$base/sub", 0777, true);
symlink("target", "$base/sub/link");

chdir("$base/sub");
var_dump(is_link("link"));
var_dump(readlink("link"));
?>
--CLEAN--
<?php
$base = __DIR__ . "/readlink_virtual_cwd_dir";
@unlink("$base/sub/link");
@rmdir("$base/sub");
@rmdir($base);
?>
--EXPECT--
bool(true)
string(6) "target"
