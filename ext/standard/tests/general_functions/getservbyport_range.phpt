--TEST--
getservbyport() rejects a $port outside 0-65535 instead of silently wrapping through a 16-bit cast
--SKIPIF--
<?php
if (in_array(PHP_OS_FAMILY, ['BSD', 'Darwin', 'Solaris', 'Linux'])) {
    if (!file_exists("/etc/services")) die("skip reason: missing /etc/services");
}
$serv = getservbyport(21, "tcp");
if ($serv !== "ftp") die("skip reason: port 21/tcp is not registered as ftp on this machine");
if (getenv('SKIP_MSAN')) die('skip msan missing interceptor for getservbyport()');
?>
--FILE--
<?php
// Before the fix, (unsigned short) truncation made 21 and 21 - 65536 collide.
try {
    var_dump(getservbyport(21 - 65536, "tcp"));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
try {
    var_dump(getservbyport(65536, "tcp"));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
var_dump(is_string(getservbyport(0, "tcp")) || getservbyport(0, "tcp") === false);
var_dump(is_string(getservbyport(65535, "tcp")) || getservbyport(65535, "tcp") === false);
?>
--EXPECTF--
ValueError: getservbyport(): Argument #1 ($port) must be between 0 and 65535
ValueError: getservbyport(): Argument #1 ($port) must be between 0 and 65535
bool(true)
bool(true)
