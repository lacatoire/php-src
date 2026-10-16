--TEST--
chown(), chgrp(), lchown(), lchgrp(): an integer id outside the valid range is rejected
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip no chown() on Windows');
?>
--FILE--
<?php
$file = __DIR__ . '/chown_chgrp_id_range.txt';
touch($file);
$uid = getmyuid();
$gid = getmygid();

foreach (['chown', 'lchown', 'chgrp', 'lchgrp'] as $f) {
    foreach ([-1, -2, 2**32 + 1, 2**32 - 1, PHP_INT_MAX, PHP_INT_MIN] as $id) {
        try {
            var_dump($f($file, $id));
        } catch (Throwable $e) {
            echo $f, ' ', $id, ': ', $e::class, ': ', $e->getMessage(), "\n";
        }
    }
}
var_dump(chown($file, $uid), chgrp($file, $gid));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/chown_chgrp_id_range.txt');
?>
--EXPECT--
chown -1: ValueError: chown(): Argument #2 ($user) must be between 0 and 4294967294
chown -2: ValueError: chown(): Argument #2 ($user) must be between 0 and 4294967294
chown 4294967297: ValueError: chown(): Argument #2 ($user) must be between 0 and 4294967294
chown 4294967295: ValueError: chown(): Argument #2 ($user) must be between 0 and 4294967294
chown 9223372036854775807: ValueError: chown(): Argument #2 ($user) must be between 0 and 4294967294
chown -9223372036854775808: ValueError: chown(): Argument #2 ($user) must be between 0 and 4294967294
lchown -1: ValueError: lchown(): Argument #2 ($user) must be between 0 and 4294967294
lchown -2: ValueError: lchown(): Argument #2 ($user) must be between 0 and 4294967294
lchown 4294967297: ValueError: lchown(): Argument #2 ($user) must be between 0 and 4294967294
lchown 4294967295: ValueError: lchown(): Argument #2 ($user) must be between 0 and 4294967294
lchown 9223372036854775807: ValueError: lchown(): Argument #2 ($user) must be between 0 and 4294967294
lchown -9223372036854775808: ValueError: lchown(): Argument #2 ($user) must be between 0 and 4294967294
chgrp -1: ValueError: chgrp(): Argument #2 ($group) must be between 0 and 4294967294
chgrp -2: ValueError: chgrp(): Argument #2 ($group) must be between 0 and 4294967294
chgrp 4294967297: ValueError: chgrp(): Argument #2 ($group) must be between 0 and 4294967294
chgrp 4294967295: ValueError: chgrp(): Argument #2 ($group) must be between 0 and 4294967294
chgrp 9223372036854775807: ValueError: chgrp(): Argument #2 ($group) must be between 0 and 4294967294
chgrp -9223372036854775808: ValueError: chgrp(): Argument #2 ($group) must be between 0 and 4294967294
lchgrp -1: ValueError: lchgrp(): Argument #2 ($group) must be between 0 and 4294967294
lchgrp -2: ValueError: lchgrp(): Argument #2 ($group) must be between 0 and 4294967294
lchgrp 4294967297: ValueError: lchgrp(): Argument #2 ($group) must be between 0 and 4294967294
lchgrp 4294967295: ValueError: lchgrp(): Argument #2 ($group) must be between 0 and 4294967294
lchgrp 9223372036854775807: ValueError: lchgrp(): Argument #2 ($group) must be between 0 and 4294967294
lchgrp -9223372036854775808: ValueError: lchgrp(): Argument #2 ($group) must be between 0 and 4294967294
bool(true)
bool(true)
