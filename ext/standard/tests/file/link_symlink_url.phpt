--TEST--
link(), symlink(): a URL in either argument is rejected with the URL warning
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip no link() on Windows');
?>
--FILE--
<?php
$dir = __DIR__ . '/link_symlink_url_dir';
mkdir($dir);
$file = "$dir/f.txt";
file_put_contents($file, 'x');

foreach (['http://example.com/y', 'ftp://example.com/y', 'php://memory', 'data://text/plain,a'] as $url) {
    var_dump(link($url, "$dir/h"));
    var_dump(link($file, $url));
    var_dump(symlink($file, $url));
    var_dump(symlink($url, "$dir/s"));
}
var_dump(file_exists("$dir/h"), file_exists("$dir/s"));
var_dump(link($file, "$dir/h"), symlink($file, "$dir/s"));
?>
--CLEAN--
<?php
$dir = __DIR__ . '/link_symlink_url_dir';
@unlink("$dir/h");
@unlink("$dir/s");
@unlink("$dir/f.txt");
@rmdir($dir);
?>
--EXPECTF--
Warning: link(): Unable to link to a URL in %s on line %d
bool(false)

Warning: link(): Unable to link to a URL in %s on line %d
bool(false)

Warning: symlink(): Unable to symlink to a URL in %s on line %d
bool(false)

Warning: symlink(): Unable to symlink to a URL in %s on line %d
bool(false)

Warning: link(): Unable to link to a URL in %s on line %d
bool(false)

Warning: link(): Unable to link to a URL in %s on line %d
bool(false)

Warning: symlink(): Unable to symlink to a URL in %s on line %d
bool(false)

Warning: symlink(): Unable to symlink to a URL in %s on line %d
bool(false)

Warning: link(): Unable to link to a URL in %s on line %d
bool(false)

Warning: link(): Unable to link to a URL in %s on line %d
bool(false)

Warning: symlink(): Unable to symlink to a URL in %s on line %d
bool(false)

Warning: symlink(): Unable to symlink to a URL in %s on line %d
bool(false)

Warning: link(): Unable to link to a URL in %s on line %d
bool(false)

Warning: link(): Unable to link to a URL in %s on line %d
bool(false)

Warning: symlink(): Unable to symlink to a URL in %s on line %d
bool(false)

Warning: symlink(): Unable to symlink to a URL in %s on line %d
bool(false)
bool(false)
bool(false)
bool(true)
bool(true)
