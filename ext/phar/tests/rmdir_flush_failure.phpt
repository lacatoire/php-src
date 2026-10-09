--TEST--
Phar: rmdir() reports the directory name when the flush fails
--EXTENSIONS--
phar
--SKIPIF--
<?php
if (function_exists('posix_geteuid') && posix_geteuid() === 0) {
    die('skip root can write to read-only files');
}
?>
--INI--
phar.readonly=0
--FILE--
<?php

$file = __DIR__ . '/rmdir_flush_failure.phar';
$phar = new Phar($file);
$phar->addEmptyDir('e');
unset($phar);
chmod($file, 0444);

var_dump(rmdir("phar://$file/e"));

?>
--CLEAN--
<?php
$file = __DIR__ . '/rmdir_flush_failure.phar';
@chmod($file, 0644);
@unlink($file);
?>
--EXPECTF--
Warning: rmdir(): Failed to open stream: Permission denied in %s on line %d

Warning: rmdir(): phar error: cannot remove directory "e" in phar "%srmdir_flush_failure.phar", unable to open new phar "%srmdir_flush_failure.phar" for writing in %s on line %d
bool(false)
