--TEST--
proc_open(): requested descriptor numbers equal to fds of later pipes are preserved
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip not for Windows');
?>
--FILE--
<?php
// Reverse order over a wide range, so that the fds allocated for the pipes
// overlap the requested numbers whatever fds are already open.
$spec = [];
$cmd = [];
for ($i = 9; $i >= 3; $i--) {
    $spec[$i] = ['pipe', 'w'];
    $cmd[] = "echo fd$i >&$i";
}
$proc = proc_open(implode('; ', $cmd), $spec, $pipes);
ksort($pipes);
foreach ($pipes as $k => $h) {
    echo "[$k] ", json_encode(stream_get_contents($h)), "\n";
    fclose($h);
}
var_dump(proc_close($proc));
?>
--EXPECT--
[3] "fd3\n"
[4] "fd4\n"
[5] "fd5\n"
[6] "fd6\n"
[7] "fd7\n"
[8] "fd8\n"
[9] "fd9\n"
int(0)
