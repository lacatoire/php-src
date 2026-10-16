--TEST--
gzopen(): no file descriptor leaked when zlib rejects the mode
--EXTENSIONS--
zlib
--SKIPIF--
<?php
if (!is_dir('/proc/self/fd')) die('skip needs /proc/self/fd');
?>
--FILE--
<?php
function fds() { return count(glob('/proc/self/fd/*')); }

$dir = __DIR__ . '/gzopen_rejected_mode_fd_leak_dir';
mkdir($dir);
file_put_contents("$dir/f.gz", gzencode('x'));

$before = fds();
for ($i = 0; $i < 20; $i++) {
    $h = @gzopen("$dir/f.gz", 'c');
}
var_dump($h);
echo "mode c leaked ", fds() - $before, "\n";

$before = fds();
for ($i = 0; $i < 20; $i++) {
    @unlink("$dir/x.gz");
    $h = @gzopen("$dir/x.gz", 'x');
}
echo "mode x leaked ", fds() - $before, "\n";

$before = fds();
for ($i = 0; $i < 20; $i++) {
    $h = gzopen("$dir/f.gz", 'r');
    gzclose($h);
}
echo "mode r leaked ", fds() - $before, "\n";
?>
--CLEAN--
<?php
$dir = __DIR__ . '/gzopen_rejected_mode_fd_leak_dir';
@unlink("$dir/f.gz");
@unlink("$dir/x.gz");
@rmdir($dir);
?>
--EXPECT--
bool(false)
mode c leaked 0
mode x leaked 0
mode r leaked 0
