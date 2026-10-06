--TEST--
session_write_close(): an exception thrown while encoding does not replace the stored data
--INI--
session.use_cookies=0
session.use_strict_mode=0
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
ob_start();
$dir = __DIR__ . '/session_write_close_encode_exception_dir';
mkdir($dir);
session_save_path($dir);

class T {
    public function __serialize(): array { throw new RuntimeException('boom'); }
}

session_id('keepx');
session_start();
$_SESSION['a'] = 1;
session_write_close();
echo file_get_contents("$dir/sess_keepx"), "\n";

session_start();
$_SESSION['a'] = 2;
$_SESSION['b'] = new T;
$_SESSION['c'] = 3;
try {
    session_write_close();
} catch (Throwable $e) {
    echo get_class($e), ': ', $e->getMessage(), "\n";
}
echo file_get_contents("$dir/sess_keepx"), "\n";

session_id('keepx');
var_dump(session_start());
var_dump($_SESSION);
session_write_close();
?>
--CLEAN--
<?php
$dir = __DIR__ . '/session_write_close_encode_exception_dir';
foreach (glob("$dir/sess_*") as $file) {
    @unlink($file);
}
@rmdir($dir);
?>
--EXPECT--
a|i:1;
RuntimeException: boom
a|i:1;
bool(true)
array(1) {
  ["a"]=>
  int(1)
}
